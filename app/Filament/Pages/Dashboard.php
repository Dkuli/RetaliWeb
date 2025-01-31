<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Panel;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\GroupsOverTimeChart;
use App\Filament\Widgets\IncidentsByPriorityChart;
use App\Filament\Widgets\LatestIncidents;
use App\Filament\Widgets\UpcomingDepartures;
use App\Filament\Widgets\TourLeaderPerformance;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -2;
    protected static bool $shouldRegisterNavigation = true;



    public function getWidgets(): array
    {
        return [
            StatsOverview::class,

        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}
