<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessController extends Controller
{
    public function login(string $code): RedirectResponse
    {
        $wedding = Wedding::where('access_code', $code)->first();

        if (!$wedding) {
            abort(404);
        }

        // Create user if doesn't exist
        if (!$wedding->user) {
            $user = User::create([
                'name' => $wedding->couple_name,
                'email' => 'client_' . $wedding->id . '_' . Str::random(8) . '@client.local',
                'password' => Hash::make(Str::random(32)),
                'role' => 'couple',
            ]);

            $wedding->update(['user_id' => $user->id]);
            $wedding->refresh();
        }

        Auth::login($wedding->user);

        return redirect()->route('dashboard');
    }
}
