<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TourLeader;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        
        $tourLeader = TourLeader::where('email', $request->email)->first();

        if (!$tourLeader || !Hash::check($request->password, $tourLeader->password)) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        // Pastikan tour leader aktif
        if (!$tourLeader->is_active) {
            return $this->errorResponse('Tour leader account is not active', 403);
        }

        // Gunakan guard tourleader untuk token
        $token = $tourLeader->createToken('tourleader-token', ['tourleader'])->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => $tourLeader->load('currentGroup')
        ], 'Login successful');
    }

    public function logout()
    {
        // Gunakan guard tourleader
        /** @var \App\Models\TourLeader $tourLeader */
        $tourLeader = Auth::guard('tourleader')->user();

        // Hapus semua token untuk tour leader ini
        $tourLeader->tokens()->delete();

        return $this->successResponse(null, 'Logged out successfully');
    }
}
