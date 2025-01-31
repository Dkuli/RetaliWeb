<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\TourLeader;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ApiResponse;

    public function show()
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();
        $tourLeader->load('currentGroup');
        $tourLeader->loadMedia('avatar');

        return $this->successResponse($tourLeader);
    }

    public function update(UpdateProfileRequest $request)
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        try {
            $tourLeader->update($request->validated());

            if ($request->hasFile('avatar')) {
                $tourLeader->clearMediaCollection('avatar');
                $tourLeader->addMediaFromRequest('avatar')
                    ->toMediaCollection('avatar');
            }

            $tourLeader = $tourLeader->fresh();
            $tourLeader->loadMedia('avatar');

            return $this->successResponse(
                $tourLeader->fresh(),
                'Profile updated successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update profile',
                500
            );
        }
    }

    public function updateFcmToken(Request $request)
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        $validated = $request->validate([
            'fcm_token' => 'required|string'
        ]);

        try {
            $tourLeader->update(['fcm_token' => $validated['fcm_token']]);
            return $this->successResponse(null, 'FCM token updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to update FCM token',
                500
            );
        }
    }
}
