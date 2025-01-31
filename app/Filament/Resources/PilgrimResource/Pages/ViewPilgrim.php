<?php

namespace App\Filament\Resources\PilgrimResource\Pages;

use App\Filament\Resources\PilgrimResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPilgrim extends ViewRecord
{
    protected static string $resource = PilgrimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Data'),
            Actions\Action::make('print')
                ->label('Cetak Kartu')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('print.pilgrim.card', $record))
                ->openUrlInNewTab(),
        ];
    }
}
