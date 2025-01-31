<?php
// app/Filament/Resources/NotificationResource/Widgets/NotificationStatsWidget.php
namespace App\Filament\Resources\NotificationResource\Widgets;

use App\Models\Notification;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NotificationStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Notifications', Notification::count())
                ->description('All notifications')
                ->chart(Notification::query()
                    ->selectRaw('DATE(created_at) as date, count(*) as count')
                    ->groupBy('date')
                    ->limit(7)
                    ->pluck('count')
                    ->toArray()),

            Stat::make('Unread', Notification::where('is_read', false)->count())
                ->description('Unread notifications')
                ->color('danger'),

            Stat::make('Read Rate', function() {
                $total = Notification::count();
                if (!$total) return '0%';
                $read = Notification::where('is_read', true)->count();
                return round(($read / $total) * 100) . '%';
            })
                ->color('success'),
        ];
    }
}