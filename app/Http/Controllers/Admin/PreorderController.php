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
    public function index(): Response
    {
        $preorders = Preorder::orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Preorders/Index', [
            'preorders' => $preorders,
            'eventTypes' => Preorder::getEventTypeOptions(),
            'statuses' => Preorder::getStatusOptions(),
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
            Storage::disk('public')->delete($preorder->qr_card_image);
        }

        $preorder->delete();

        return redirect()->route('admin.preorders.index')
            ->with('success', 'Precomanda a fost ștearsă.');
    }
}
