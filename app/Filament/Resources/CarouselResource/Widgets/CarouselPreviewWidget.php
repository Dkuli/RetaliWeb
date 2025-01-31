<?php

namespace App\Filament\Resources\CarouselResource\Widgets;

use App\Models\Carousel;
use Filament\Widgets\Widget;

class CarouselPreviewWidget extends Widget
{
    protected static string $view = 'filament.resources.carousel.widgets.preview';
    
    public function getActiveBanners()
    {
        return Carousel::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();
    }
}