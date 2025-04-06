<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilgrimCardController;
use App\Filament\Resources\GroupResource\Pages\GroupCalendar;
use App\Http\Controllers\TrackingMapController;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/');

Route::get('/print/pilgrim-card/{pilgrim}', [PilgrimCardController::class, 'print'])
    ->name('print.pilgrim.card');

