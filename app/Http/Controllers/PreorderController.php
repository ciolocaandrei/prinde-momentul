<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreorderRequest;
use App\Models\Preorder;
use Illuminate\Support\Facades\File;
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
            'sampleImages' => $this->getSampleImages(),
        ]);
    }

    private function getSampleImages(): array
    {
        $samplesPath = public_path('samples');
        $categories = [
            'nunta' => ['name' => 'Nuntă', 'samples' => []],
            'botez' => ['name' => 'Botez', 'samples' => []],
            'majorat' => ['name' => 'Majorat', 'samples' => []],
        ];

        foreach (array_keys($categories) as $category) {
            $categoryPath = $samplesPath . '/' . $category;

            if (File::isDirectory($categoryPath)) {
                $files = File::files($categoryPath);
                foreach ($files as $file) {
                    $extension = strtolower($file->getExtension());
                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $categories[$category]['samples'][] = [
                            'id' => $category . '_' . $file->getFilenameWithoutExtension(),
                            'path' => '/samples/' . $category . '/' . $file->getFilename(),
                        ];
                    }
                }

                // Sort numerically
                usort($categories[$category]['samples'], function ($a, $b) {
                    return strnatcmp($a['id'], $b['id']);
                });
            }
        }

        // Remove empty categories
        return array_filter($categories, fn($cat) => count($cat['samples']) > 0);
    }

    public function store(StorePreorderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload if provided
        if ($request->hasFile('qr_card_image')) {
            $validated['qr_card_image'] = $request->file('qr_card_image')
                ->store('preorder-images', 'public');
        }

        // Decode qr_card_design if it's a JSON string
        if (isset($validated['qr_card_design']) && is_string($validated['qr_card_design'])) {
            $validated['qr_card_design'] = json_decode($validated['qr_card_design'], true);
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
