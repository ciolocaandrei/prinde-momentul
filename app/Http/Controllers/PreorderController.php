<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreorderRequest;
use App\Models\Preorder;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class PreorderController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Preorder/Create', [
            'eventTypes' => Preorder::getEventTypeOptions(),
        ]);
    }

    public function store(StorePreorderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload if provided
        if ($request->hasFile('qr_card_image')) {
            $validated['qr_card_image'] = $request->file('qr_card_image')
                ->store('preorder-images', 'r2');
        }

        Preorder::create($validated);

        return redirect()->route('preorder.success')
            ->with('success', 'Precomanda ta a fost înregistrată cu succes!');
    }

    public function success(): Response
    {
        return Inertia::render('Preorder/Success');
    }
}
