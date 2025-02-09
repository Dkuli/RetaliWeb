<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\QuestionnaireController;

use App\Http\Controllers\Api\CarouselController;
use App\Http\Controllers\Api\LuggageScanController;
use App\Http\Controllers\Api\LuggageHistoryController;


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('login', [LoginController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout']);

        Route::get('profile', [ProfileController::class, 'show']);
        Route::post('profile', [ProfileController::class, 'update']);
        Route::post('fcm-token', [ProfileController::class, 'updateFcmToken']);

        Route::middleware(['ensure.active.tourleader'])->group(function () {

            Route::post('/luggage_scans', [LuggageScanController::class, 'store']);
            Route::get('luggage/{luggageNumber}/history', [LuggageHistoryController::class, 'getByLuggageNumber']);
            Route::get('luggage/scans/mine', [LuggageHistoryController::class, 'getMyScans']);
            Route::get('luggage/stats/mine', [LuggageHistoryController::class, 'getMyStats']);
            // Group routes
            Route::get('group/current', [GroupController::class, 'current']);
            Route::get('group/pilgrims', [GroupController::class, 'pilgrims']);
            Route::get('group/schedule', [GroupController::class, 'schedule']);

            // Location routes
            Route::post('/location/update', [LocationController::class, 'update']);
            // kusioner
            Route::apiResource('questionnaires', QuestionnaireController::class)->only(['index', 'show']);
    Route::post('questionnaires/{questionnaire}/submit', [QuestionnaireController::class, 'submit']);

            // Content routes
            Route::get('contents', [ContentController::class, 'index']);
            Route::post('contents', [ContentController::class, 'store']);

            // Notification routes
            Route::get('notifications', [NotificationController::class, 'index']);
            Route::patch('notifications/{notification}', [NotificationController::class, 'markAsRead']);

            Route::get('carousels', [CarouselController::class, 'index']);
        });
    });
});
