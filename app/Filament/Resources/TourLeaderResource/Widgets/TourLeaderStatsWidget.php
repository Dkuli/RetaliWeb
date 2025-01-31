<?php
// app/Filament/Resources/TourLeaderResource/Widgets/TourLeaderStatsWidget.php
namespace App\Filament\Resources\TourLeaderResource\Widgets;

use App\Models\TourLeader;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TourLeaderStatsWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Tour Leaders', TourLeader::count())
                ->description('Overall registered tour leaders')
                ->descriptionIcon('heroicon-s-user-group')
                ->color('primary'),
            Card::make('Active Tour Leaders', TourLeader::where('is_active', true)->count())
                ->description('Currently active tour leaders')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
            Card::make('Inactive Tour Leaders', TourLeader::where('is_active', false)->count())
                ->description('Currently inactive tour leaders')
                ->descriptionIcon('heroicon-s-x-circle')
                ->color('danger'),
        ];
    }
}
