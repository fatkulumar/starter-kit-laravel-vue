<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if(!Auth::user()) return redirect()->back();
        return Inertia::render('Profile', [
            'status' => $request->session()->get('status'),
        ]);
    }
}
