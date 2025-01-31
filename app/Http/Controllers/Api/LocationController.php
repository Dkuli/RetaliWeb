<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrackingLocation;
use Illuminate\Support\Facades\Event;
use App\Events\LocationUpdated;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'battery_level' => 'nullable|integer|between:0,100',
        ]);

        $location = TrackingLocation::create([
            'tour_leader_id' => auth()->id(),
            'tracked_at' => now(),
            ...$validated
        ]);

        // Broadcast location update
        event(new LocationUpdated($location));

        return response()->json($location);
    }
}
