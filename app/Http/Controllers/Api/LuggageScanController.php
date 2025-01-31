<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Luggage;
use App\Models\LuggageScan;

class LuggageScanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->input('data'); // Data dari input QR code
        $parts = explode(';', $data);

        // Pastikan format data benar
        if (count($parts) == 4) {
            // Gabungkan nama grup dan tanggal
            $group = $parts[3];

            // Simpan data koper jika belum ada
            $luggage = Luggage::updateOrCreate(
                ['luggage_number' => $parts[0]],
                [
                    'pilgrim_name' => $parts[1],
                    'phone' => $parts[2],
                    'group' => $group,
                ]
            );

            // Simpan data histori pemindaian
            $luggageScan = LuggageScan::create([
                'luggage_id' => $luggage->id,
                'tour_leader_id' => $request->input('tour_leader_id'),
                'scanned_at' => now(), // atau $request->input('scanned_at')
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
            ]);

            return response()->json($luggageScan, 201);
        } else {
            return response()->json(['error' => 'Invalid data format'], 400);
        }
    }
}
