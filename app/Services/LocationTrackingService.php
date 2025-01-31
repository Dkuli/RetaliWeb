<?php

namespace App\Services;

use App\Models\TourLeader;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Events\LocationUpdated;

class LocationTrackingService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * Update lokasi tour leader
     */
    public function updateLocation(TourLeader $tourLeader, array $data)
    {
        $locationData = $this->prepareLocationData($tourLeader, $data);

        // Simpan lokasi terbaru di Redis dengan TTL 24 jam
        $this->redis->setex(
            "location:tourleader:{$tourLeader->id}:latest",
            86400,
            json_encode($locationData)
        );

        // Simpan di history lokasi (simpan 1000 point terakhir)
        $this->redis->lpush(
            "location:tourleader:{$tourLeader->id}:history",
            json_encode($locationData)
        );
        $this->redis->ltrim("location:tourleader:{$tourLeader->id}:history", 0, 999);

        // Simpan status online dengan TTL 5 menit
        $this->updateOnlineStatus($tourLeader);

        // Broadcast ke dashboard admin
        broadcast(new LocationUpdated($locationData))->toOthers();

        // Update last activity di database
        $tourLeader->update(['last_active_at' => now()]);

        return $locationData;
    }

    /**
     * Mempersiapkan data lokasi
     */
    protected function prepareLocationData(TourLeader $tourLeader, array $data)
    {
        $baseData = [
            'tour_leader_id' => $tourLeader->id,
            'tour_leader_name' => $tourLeader->name,
            'avatar_url' => $tourLeader->avatar_url,
            'group_name' => $tourLeader->currentGroup?->name,
            'group_id' => $tourLeader->current_group_id,
            'timestamp' => $data['timestamp'] ?? now()->toIso8601String(),
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => $data['accuracy'] ?? null,
            'speed' => $data['speed'] ?? null,
            'heading' => $data['heading'] ?? null,
            'altitude' => $data['altitude'] ?? null,
            'battery_level' => $data['battery_level'] ?? null,
            'activity_type' => $data['activity_type'] ?? null,
            'is_moving' => $data['is_moving'] ?? false,
            'phone' => $tourLeader->phone,
            'status' => $this->determineStatus($tourLeader, $data)
        ];

        // Tambahkan reverse geocoding jika ada koordinat
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $address = $this->getAddressFromCoordinates(
                $data['latitude'],
                $data['longitude']
            );
            return array_merge($baseData, $address);
        }

        return $baseData;
    }

    /**
     * Menentukan status tour leader
     */
    protected function determineStatus(TourLeader $tourLeader, array $data)
    {
        if (!$tourLeader->is_active) {
            return 'inactive';
        }

        if ($data['is_moving'] ?? false) {
            return 'moving';
        }

        if ($data['activity_type'] == 'still') {
            return 'stationary';
        }

        return 'unknown';
    }

    /**
     * Update status online tour leader
     */
    protected function updateOnlineStatus(TourLeader $tourLeader)
    {
        $this->redis->setex(
            "tourleader:online:{$tourLeader->id}",
            300, // 5 menit
            json_encode([
                'last_seen' => now()->toIso8601String(),
                'name' => $tourLeader->name,
                'group' => $tourLeader->currentGroup?->name
            ])
        );
    }

    /**
     * Mendapatkan lokasi terbaru tour leader
     */
    public function getLatestLocation(TourLeader $tourLeader)
    {
        $data = $this->redis->get("location:tourleader:{$tourLeader->id}:latest");
        return $data ? json_decode($data, true) : null;
    }

    /**
     * Mendapatkan lokasi terbaru berdasarkan ID
     */
    public function getLatestLocationById($tourLeaderId)
    {
        $data = $this->redis->get("location:tourleader:{$tourLeaderId}:latest");
        return $data ? json_decode($data, true) : null;
    }

    /**
     * Mendapatkan history lokasi tour leader
     */
    public function getLocationHistory(TourLeader $tourLeader, int $limit = 100)
    {
        $history = $this->redis->lrange("location:tourleader:{$tourLeader->id}:history", 0, $limit - 1);
        return array_map(fn($item) => json_decode($item, true), $history);
    }

    /**
     * Mendapatkan semua tour leader yang aktif
     */
    public function getActiveLeaders(?int $groupId = null)
    {
        $keys = $this->redis->keys('location:tourleader:*:latest');
        $leaders = [];

        foreach ($keys as $key) {
            preg_match('/location:tourleader:(.*?):latest/', $key, $matches);
            if (isset($matches[1])) {
                $tourLeaderId = $matches[1];
                $location = $this->getLatestLocationById($tourLeaderId);

                // Filter berdasarkan group jika ada
                if ($location && (!$groupId || $location['group_id'] == $groupId)) {
                    $isOnline = $this->isOnline($tourLeaderId);

                    $leaders[] = array_merge($location, [
                        'is_online' => $isOnline,
                        'last_seen' => $location['timestamp'],
                        'last_active' => Carbon::parse($location['timestamp'])->diffForHumans()
                    ]);
                }
            }
        }

        // Sort berdasarkan status online dan timestamp
        usort($leaders, function($a, $b) {
            if ($a['is_online'] !== $b['is_online']) {
                return $b['is_online'] - $a['is_online'];
            }
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        return $leaders;
    }

    /**
     * Cek apakah tour leader sedang online
     */
    public function isOnline($tourLeaderId): bool
    {
        return $this->redis->exists("tourleader:online:{$tourLeaderId}");
    }

    /**
     * Mendapatkan alamat dari koordinat
     */
    protected function getAddressFromCoordinates($latitude, $longitude)
    {
        $cacheKey = "geocoding:{$latitude}:{$longitude}";

        return Redis::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
            try {
                $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                    'format' => 'json',
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'zoom' => 18,
                    'addressdetails' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'address' => $data['display_name'] ?? null,
                        'city' => $data['address']['city'] ??
                                $data['address']['town'] ??
                                $data['address']['village'] ?? null,
                        'district' => $data['address']['suburb'] ??
                                    $data['address']['district'] ??
                                    $data['address']['neighbourhood'] ?? null,
                        'province' => $data['address']['state'] ?? null,
                        'country' => $data['address']['country'] ?? null,
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Geocoding failed', [
                    'error' => $e->getMessage(),
                    'coordinates' => [$latitude, $longitude]
                ]);
            }

            return [
                'address' => null,
                'city' => null,
                'district' => null,
                'province' => null,
                'country' => null
            ];
        });
    }

    /**
     * Mendapatkan statistik tracking tour leader
     */
    public function getTrackingStats(TourLeader $tourLeader)
    {
        $history = $this->getLocationHistory($tourLeader);

        if (empty($history)) {
            return [
                'total_distance' => 0,
                'average_speed' => 0,
                'moving_time' => 0,
                'stationary_time' => 0,
                'last_update' => null
            ];
        }

        $totalDistance = 0;
        $speeds = [];
        $movingTime = 0;
        $stationaryTime = 0;
        $lastLocation = null;

        foreach ($history as $location) {
            if ($lastLocation) {
                $distance = $this->calculateDistance(
                    $lastLocation['latitude'],
                    $lastLocation['longitude'],
                    $location['latitude'],
                    $location['longitude']
                );
                $totalDistance += $distance;

                if ($location['is_moving']) {
                    $movingTime++;
                    if ($location['speed']) {
                        $speeds[] = $location['speed'];
                    }
                } else {
                    $stationaryTime++;
                }
            }
            $lastLocation = $location;
        }

        return [
            'total_distance' => round($totalDistance, 2), // dalam kilometer
            'average_speed' => !empty($speeds) ? round(array_sum($speeds) / count($speeds), 2) : 0,
            'moving_time' => $movingTime,
            'stationary_time' => $stationaryTime,
            'last_update' => $lastLocation['timestamp'] ?? null
        ];
    }

    /**
     * Hitung jarak antara dua koordinat
     */
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles * 1.609344; // Konversi ke kilometer
    }
}
