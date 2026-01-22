<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Preorder;
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
            'total_preorders' => Preorder::count(),
            'pending_preorders' => Preorder::where('status', 'pending')->count(),
        ];

        $recentWeddings = Wedding::with('user')
            ->withCount('photos')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPreorders = Preorder::latest()
            ->take(5)
            ->get()
            ->map(function ($preorder) {
                return [
                    'id' => $preorder->id,
                    'contact_name' => $preorder->contact_name,
                    'contact_email' => $preorder->contact_email,
                    'event_type' => $preorder->event_type,
                    'event_type_label' => $preorder->getEventTypeLabel(),
                    'event_date' => $preorder->event_date?->format('d.m.Y'),
                    'status' => $preorder->status,
                    'status_label' => $preorder->getStatusLabel(),
                    'created_at' => $preorder->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentWeddings' => $recentWeddings,
            'recentPreorders' => $recentPreorders,
        ]);
    }
}
