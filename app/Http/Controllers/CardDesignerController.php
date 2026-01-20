<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CardDesignerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CardDesigner/Index', [
            'templates' => $this->getTemplates(),
            'fonts' => $this->getFonts(),
            'sizes' => $this->getSizes(),
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'design' => 'required|array',
        ]);

        return response()->json([
            'success' => true,
            'design' => $validated['design'],
        ]);
    }

    private function getTemplates(): array
    {
        return [
            [
                'id' => 'elegant_gold',
                'name' => 'Elegant Gold',
                'style' => 'Clasic',
                'background' => [
                    'type' => 'gradient',
                    'colors' => ['#FFFEF7', '#F5F0E1'],
                    'direction' => '135deg',
                ],
                'primaryFont' => 'Playfair Display',
                'secondaryFont' => 'Montserrat',
                'textColor' => '#2C2C2C',
                'accentColor' => '#C9A959',
            ],
            [
                'id' => 'modern_minimal',
                'name' => 'Modern Minimal',
                'style' => 'Minimalist',
                'background' => [
                    'type' => 'solid',
                    'color' => '#FFFFFF',
                ],
                'primaryFont' => 'Montserrat',
                'secondaryFont' => 'Montserrat',
                'textColor' => '#1A1A1A',
                'accentColor' => '#666666',
            ],
            [
                'id' => 'romantic_blush',
                'name' => 'Romantic Blush',
                'style' => 'Romantic',
                'background' => [
                    'type' => 'gradient',
                    'colors' => ['#FDF2F8', '#EDE9FE'],
                    'direction' => '135deg',
                ],
                'primaryFont' => 'Great Vibes',
                'secondaryFont' => 'Lora',
                'textColor' => '#4A3728',
                'accentColor' => '#D4A5A5',
            ],
            [
                'id' => 'garden_party',
                'name' => 'Garden Party',
                'style' => 'Natural',
                'background' => [
                    'type' => 'solid',
                    'color' => '#F0F4F0',
                ],
                'primaryFont' => 'Cormorant',
                'secondaryFont' => 'Montserrat',
                'textColor' => '#2D4A3E',
                'accentColor' => '#7C9A8D',
            ],
            [
                'id' => 'classic_navy',
                'name' => 'Classic Navy',
                'style' => 'Sofisticat',
                'background' => [
                    'type' => 'solid',
                    'color' => '#1E3A5F',
                ],
                'primaryFont' => 'Playfair Display',
                'secondaryFont' => 'Montserrat',
                'textColor' => '#FFFFFF',
                'accentColor' => '#C9A959',
            ],
            [
                'id' => 'rustic_charm',
                'name' => 'Rustic Charm',
                'style' => 'Rustic',
                'background' => [
                    'type' => 'solid',
                    'color' => '#FAF6F1',
                ],
                'primaryFont' => 'Dancing Script',
                'secondaryFont' => 'Lora',
                'textColor' => '#5C4033',
                'accentColor' => '#8B7355',
            ],
        ];
    }

    private function getFonts(): array
    {
        return [
            ['id' => 'Playfair Display', 'name' => 'Playfair Display', 'category' => 'Serif'],
            ['id' => 'Montserrat', 'name' => 'Montserrat', 'category' => 'Sans-serif'],
            ['id' => 'Great Vibes', 'name' => 'Great Vibes', 'category' => 'Script'],
            ['id' => 'Dancing Script', 'name' => 'Dancing Script', 'category' => 'Script'],
            ['id' => 'Cormorant', 'name' => 'Cormorant', 'category' => 'Serif'],
            ['id' => 'Lora', 'name' => 'Lora', 'category' => 'Serif'],
        ];
    }

    private function getSizes(): array
    {
        return [
            [
                'id' => 'business_card',
                'name' => 'Business Card',
                'width' => 85,
                'height' => 55,
                'unit' => 'mm',
            ],
            [
                'id' => 'a7',
                'name' => 'A7',
                'width' => 74,
                'height' => 105,
                'unit' => 'mm',
            ],
            [
                'id' => 'a6',
                'name' => 'A6',
                'width' => 105,
                'height' => 148,
                'unit' => 'mm',
            ],
        ];
    }
}
