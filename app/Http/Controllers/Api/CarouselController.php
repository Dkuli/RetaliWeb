<?php
// app/Http/Controllers/Api/CarouselController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Traits\ApiResponse;

class CarouselController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $carousels = Carousel::with('media')->get();

        return $this->successResponse($carousels);
    }
}
