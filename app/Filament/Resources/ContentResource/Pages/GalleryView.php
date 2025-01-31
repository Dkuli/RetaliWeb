<?php
// app/Filament/Resources/ContentResource/Pages/GalleryView.php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Resources\Pages\Page;
use App\Models\Content;
use Filament\Support\Enums\MaxWidth;

class GalleryView extends Page
{
    protected static string $resource = ContentResource::class;
    protected static string $view = 'filament.resources.content.pages.gallery-view';
    protected static ?string $title = 'Gallery View';

    // Fix untuk pagination yang menyebabkan layar hitam
    protected ?MaxWidth $maxWidth = MaxWidth::Full;

    public function getViewData(): array
    {
        return [
            'contents' => Content::with(['media', 'group', 'tourLeader'])
                ->latest()
                ->paginate(12)
        ];
    }
}
