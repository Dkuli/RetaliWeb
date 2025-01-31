<?php
// app/Filament/Resources/GroupResource/Pages/ViewGroup.php
namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;

class ViewGroup extends ViewRecord
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Group Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('departure_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('return_date')
                            ->date(),
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean(),
                        Infolists\Components\TextEntry::make('max_capacity'),
                    ])->columns(2),

                Infolists\Components\Section::make('Description')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->markdown(),
                    ]),

                Infolists\Components\Section::make('Itinerary')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('itinerary'),
                    ]),
            ]);
    }
}
