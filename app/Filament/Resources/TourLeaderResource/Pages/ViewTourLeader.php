<?php

namespace App\Filament\Resources\TourLeaderResource\Pages;

use App\Filament\Resources\TourLeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;


class ViewTourLeader extends ViewRecord
{
    protected static string $resource = TourLeaderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Profile Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('avatar_url')
                            ->label('Profile Picture')
                            ->circular(),
                        Infolists\Components\TextEntry::make('name')
                            ->label('Full Name'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('phone'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Status Information')
                    ->schema([
                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Account Status')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('currentGroup.name')
                            ->label('Current Group'),
                        Infolists\Components\TextEntry::make('last_active_at')
                            ->label('Last Active')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('changePassword')
                ->icon('heroicon-m-key')
                ->form([
                    Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->confirmed(),
                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'password' => Hash::make($data['new_password']),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Password updated successfully')
                        ->send();
                }),
        ];
    }
}
