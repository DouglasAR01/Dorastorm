<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function check() {
        return response()->json([
            'result' => Auth::check()
        ]);
    }
}
