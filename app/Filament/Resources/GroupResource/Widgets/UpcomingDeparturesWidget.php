<?php
// app/Filament/Resources/Group/Widgets/GroupStatsWidget.php
namespace App\Filament\Resources\Group\Widgets;

use App\Models\Group;
use App\Models\Pilgrim;
use App\Models\TaskResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class UpcomingDeparturesWidget extends BaseWidget
{
    protected static string $view = 'filament.resources.group.widgets.upcoming-departures';

    public function getUpcomingGroups()
    {
        return Group::where('departure_date', '>', now())
            ->where('departure_date', '<', now()->addDays(30))
            ->withCount('pilgrims')
            ->orderBy('departure_date')
            ->get();
    }
}
