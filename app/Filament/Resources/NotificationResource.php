<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use App\Filament\Resources\Widgets\NotificationStatsWidget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\RawJs;
use App\Filament\Resources\NotificationResource\Widgets;
use App\Models\TourLeader;
use App\Services\FcmService;


class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationLabel = 'Kirim Notifikasi';

    protected static ?string $pluralModelLabel = 'Kirim Notifikasi';

    protected static ?string $modelLabel = 'Kirim Notifikasi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tour_leaders')
                    ->multiple()
                    ->label('Tour Leader')
                    ->options(TourLeader::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('Judul Notifikasi')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->maxLength(65535),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\SendNotification::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\NotificationStatsWidget::class,
            Widgets\UnreadNotificationsWidget::class,
            Widgets\FcmStatusWidget::class,
        ];
    }
}
