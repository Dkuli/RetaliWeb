<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\TourLeader;
use App\Models\TrackingLocation;
use Illuminate\Support\Facades\Cache;

class LiveTracking extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'filament.pages.live-tracking';

    public $tourLeaders = [];
    public $selectedLeader = null;

    public function mount()
    {
        $this->loadTourLeaders();
    }

    public function loadTourLeaders()
    {
        $this->tourLeaders = Cache::remember('active_tour_leaders', 60, function () {
            return TourLeader::with(['currentGroup', 'locations' => function ($query) {
                $query->latest('tracked_at')->limit(1);
            }])
            ->where('is_active', true)
            ->where('last_active_at', '>', now()->subHours(24))
            ->get()
            ->map(function ($leader) {
                $lastLocation = $leader->locations->first();
                return [
                    'id' => $leader->id,
                    'name' => $leader->name,
                    'group' => $leader->currentGroup?->name,
                    'phone' => $leader->phone,
                    'avatar_url' => $leader->avatar_url,
                    'last_location' => $lastLocation ? [
                        'latitude' => $lastLocation->latitude,
                        'longitude' => $lastLocation->longitude,
                        'accuracy' => $lastLocation->accuracy,
                        'speed' => $lastLocation->speed,
                        'battery_level' => $lastLocation->battery_level,
                        'address' => $lastLocation->address,
                        'tracked_at' => $lastLocation->tracked_at->diffForHumans()
                    ] : null
                ];
            })
            ->toArray();
        });
    }

    public function handleLocationUpdate($data)
{
    $this->loadTourLeaders();

}
}
