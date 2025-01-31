<?php
// app/Filament/Resources/ContentResource/Widgets/ContentStatsWidget.php
namespace App\Filament\Resources\ContentResource\Widgets;

use App\Models\Content;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Content', Content::count())
                ->description('All content items')
                ->chart(Content::query()
                    ->selectRaw('DATE(created_at) as date, count(*) as count')
                    ->groupBy('date')
                    ->limit(7)
                    ->pluck('count')
                    ->toArray()),

            Stat::make('Photos', Content::where('type', 'photo')->count())
                ->description('Total photos uploaded'),

            Stat::make('Videos', Content::where('type', 'video')->count())
                ->description('Total videos uploaded'),
        ];
    }
}
