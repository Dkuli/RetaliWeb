<?php

namespace App\Http\Controllers;

use App\Models\Pilgrim;
use Illuminate\Http\Request;

class PilgrimCardController extends Controller
{
    public function print(Pilgrim $pilgrim)
    {
        return view('prints.pilgrim-card', compact('pilgrim'));
    }
}
