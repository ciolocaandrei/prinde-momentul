<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function login(string $code): RedirectResponse
    {
        $wedding = Wedding::where('access_code', $code)->first();

        if (!$wedding) {
            abort(404);
        }

        Auth::login($wedding->user);

        return redirect()->route('dashboard');
    }
}
