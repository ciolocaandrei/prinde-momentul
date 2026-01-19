<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;
use App\Models\Wedding;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_weddings' => Wedding::count(),
            'active_weddings' => Wedding::where('is_active', true)->count(),
            'total_photos' => Photo::count(),
            'total_couples' => User::where('role', 'couple')->count(),
        ];

        $recentWeddings = Wedding::with('user')
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentWeddings' => $recentWeddings,
        ]);
    }
}
