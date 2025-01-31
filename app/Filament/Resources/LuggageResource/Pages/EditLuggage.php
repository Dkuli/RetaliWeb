<?php

namespace App\Filament\Resources\LuggageResource\Pages;

use App\Filament\Resources\LuggageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLuggage extends EditRecord
{
    protected static string $resource = LuggageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
