<?php
// app/Filament/Resources/NotificationResource/Widgets/FcmStatusWidget.php
namespace App\Filament\Resources\NotificationResource\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Kreait\Firebase\Messaging;
use App\Models\Notification;

class UnreadNotificationsWidget extends TableWidget
{
    protected int $defaultPaginationPageSize = 5;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Notification::query()
            ->where('is_read', false)
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('tourLeader.name'),
            Tables\Columns\TextColumn::make('message')
                ->html()
                ->limit(30),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('markAsRead')
                ->icon('heroicon-o-check')
                ->action(fn (Notification $record) => $record->markAsRead()),
        ];
    }
}