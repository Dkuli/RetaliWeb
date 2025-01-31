<?php
// app/Filament/Resources/TourLeaderResource/Pages/ListTourLeaders.php
namespace App\Filament\Resources\TourLeaderResource\Pages;

use App\Filament\Resources\TourLeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTourLeaders extends ListRecords
{
    protected static string $resource = TourLeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('New Tour Leader')
                ->modalWidth('lg')
                ->modalHeading('Create New Tour Leader')
                ->successNotificationTitle('Tour Leader created successfully'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TourLeaderResource\Widgets\TourLeaderStatsWidget::class,
            TourLeaderResource\Widgets\LocationTrackingWidget::class,
        ];
    }
}
