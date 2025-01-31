<?php

namespace App\Providers;

use App\Filament\Resources\CarouselResource;
use Filament\Panel;
use Filament\PanelProvider;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->resources([
                CarouselResource::class,
            ]);
    }

    protected function getPages(): array
{
    return [
        // ...
        \App\Filament\Resources\QuestionnaireResource\Pages\PreviewQuestionnaire::class,
        \App\Filament\Resources\QuestionnaireResource\Pages\ViewResponses::class
    ];
}

}
