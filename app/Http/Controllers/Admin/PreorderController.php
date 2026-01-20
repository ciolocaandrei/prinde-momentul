<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PreorderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Preorder::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('contact_name', 'like', "%{$search}%")
                    ->orWhere('contact_email', 'like', "%{$search}%")
                    ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }

        // Event type filter
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $allowedSorts = ['created_at', 'event_date', 'contact_name'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $preorders = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Preorders/Index', [
            'preorders' => $preorders,
            'eventTypes' => Preorder::getEventTypeOptions(),
            'statuses' => Preorder::getStatusOptions(),
            'filters' => [
                'search' => $request->input('search', ''),
                'event_type' => $request->input('event_type', ''),
                'status' => $request->input('status', ''),
                'sort' => $sortField,
                'direction' => $sortDirection,
            ],
        ]);
    }

    public function show(Preorder $preorder): Response
    {
        return Inertia::render('Admin/Preorders/Show', [
            'preorder' => array_merge($preorder->toArray(), [
                'event_type_label' => $preorder->getEventTypeLabel(),
                'status_label' => $preorder->getStatusLabel(),
                'qr_card_image_url' => $preorder->getQrCardImageUrl(),
            ]),
            'eventTypes' => Preorder::getEventTypeOptions(),
            'statuses' => Preorder::getStatusOptions(),
        ]);
    }

    public function updateStatus(Request $request, Preorder $preorder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $preorder->update($validated);

        return redirect()->back()->with('success', 'Status actualizat cu succes.');
    }

    public function destroy(Preorder $preorder): RedirectResponse
    {
        // Delete associated image if exists
        if ($preorder->qr_card_image) {
            Storage::disk('r2')->delete($preorder->qr_card_image);
        }

        $preorder->delete();

        return redirect()->route('admin.preorders.index')
            ->with('success', 'Precomanda a fost ștearsă.');
    }
}
