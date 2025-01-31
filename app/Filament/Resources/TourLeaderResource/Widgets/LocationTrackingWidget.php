<?php
// app/Filament/Resources/TourLeaderResource/Widgets/TourLeaderStatsWidget.php
namespace App\Filament\Resources\TourLeaderResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\TourLeader;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;



class LocationTrackingWidget extends BaseWidget
{
    public $loadingIndicator = true;
    protected static string $view = 'filament.resources.tour-leader.widgets.location-tracking';

    protected int $refreshInterval = 30;

    public function getLocations()
    {
        return TourLeader::with(['currentGroup', 'locations' => function($query) {
            $query->latest('tracked_at')->limit(1);
        }])
        ->whereHas('locations', function($query) {
            $query->whereDate('tracked_at', today());
        })
        ->get()
        ->map(function($tourLeader) {
            $location = $tourLeader->locations->first();
            return [
                'name' => $tourLeader->name,
                'group' => $tourLeader->currentGroup?->name,
                'latitude' => $location?->latitude,
                'longitude' => $location?->longitude,
                'lastUpdate' => $location?->tracked_at->diffForHumans(),
            ];
        });
    }
}
