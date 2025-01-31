<?php

namespace App\Filament\Widgets;

use App\Models\Group;
use App\Models\Incident;
use App\Models\TourLeader;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Tour Leaders', TourLeader::count())
                ->description('All registered tour leaders')
                ->descriptionIcon('heroicon-s-user-circle'),

            Card::make('Total Groups', Group::count()) // Changed to total groups
                ->description('All groups including active and inactive')
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),



                Card::make('Total Pilgrims',
                Group::withSum('pilgrims as total_pilgrims', DB::raw('1'))->first()?->total_pilgrims ?? 0)
                ->description('Across All Groups')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
