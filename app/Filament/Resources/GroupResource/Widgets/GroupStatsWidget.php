<?php

namespace App\Filament\Resources\GroupResource\Widgets;

use App\Models\Group;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class GroupStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Groups', Group::count())
                ->description('Active & Inactive Groups')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart(Group::query()
                    ->selectRaw('DATE(created_at) as date, count(*) as count')
                    ->groupBy('date')
                    ->pluck('count')
                    ->toArray()),

            Stat::make('Active Groups', 
                Group::where('is_active', true)->count())
                ->description('Currently Active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Pilgrims', 
                Group::withSum('pilgrims as total_pilgrims', DB::raw('1'))->first()?->total_pilgrims ?? 0)
                ->description('Across All Groups')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
