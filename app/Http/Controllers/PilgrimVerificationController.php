<?php

namespace App\Http\Controllers;

use App\Models\Pilgrim;
use Illuminate\Http\Request;

class PilgrimVerificationController extends Controller
{
    public function verify(Pilgrim $pilgrim)
    {
        return view('pilgrim-verification', [
            'pilgrim' => $pilgrim->load('groups'),
            'currentGroup' => $pilgrim->groups()->where('is_active', true)->first(),
            'healthInfo' => !empty($pilgrim->health_notes),
        ]);
    }
}