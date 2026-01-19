<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class CoupleController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $wedding = $request->user()->wedding;

        if (!$wedding) {
            abort(404, 'No wedding associated with this account.');
        }

        $photos = $wedding->photos()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'original_name' => $photo->original_name,
                    'uploaded_by_name' => $photo->uploaded_by_name,
                    'url' => $photo->getUrl(),
                    'size' => $photo->size,
                    'created_at' => $photo->created_at->toIso8601String(),
                ];
            });

        return Inertia::render('Couple/Dashboard', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_name' => $wedding->couple_name,
                'event_date' => $wedding->event_date->format('Y-m-d'),
                'is_active' => $wedding->is_active,
                'upload_url' => $wedding->getUploadUrl(),
            ],
            'photos' => $photos,
        ]);
    }

    public function photos(Request $request): JsonResponse
    {
        $wedding = $request->user()->wedding;

        if (!$wedding) {
            return response()->json(['photos' => []]);
        }

        $since = $request->query('since');

        $query = $wedding->photos()->orderBy('created_at', 'desc');

        if ($since) {
            $query->where('created_at', '>', $since);
        }

        $photos = $query->get()->map(function ($photo) {
            return [
                'id' => $photo->id,
                'original_name' => $photo->original_name,
                'uploaded_by_name' => $photo->uploaded_by_name,
                'url' => $photo->getUrl(),
                'size' => $photo->size,
                'created_at' => $photo->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'photos' => $photos,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        $wedding = $request->user()->wedding;

        if (!$wedding) {
            abort(404, 'No wedding associated with this account.');
        }

        $photos = $wedding->photos;

        if ($photos->isEmpty()) {
            abort(404, 'No photos to download.');
        }

        $zipFileName = str_replace(' ', '_', $wedding->couple_name) . '_photos.zip';

        return response()->streamDownload(function () use ($photos) {
            $tempFile = tempnam(sys_get_temp_dir(), 'wedding_photos_');
            $zip = new ZipArchive();
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($photos as $photo) {
                $filePath = Storage::disk('public')->path($photo->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $photo->original_name);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    public function gallery(Request $request): Response
    {
        $wedding = $request->user()->wedding;

        if (!$wedding) {
            abort(404, 'No wedding associated with this account.');
        }

        $photos = $wedding->photos()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'url' => $photo->getUrl(),
                    'path' => $photo->path,
                    'original_name' => $photo->original_name,
                    'size' => $photo->size,
                    'uploaded_by_name' => $photo->uploaded_by_name,
                    'created_at' => $photo->created_at->toIso8601String(),
                ];
            });

        return Inertia::render('Gallery', [
            'wedding' => [
                'id' => $wedding->id,
                'couple_name' => $wedding->couple_name,
                'event_type' => $wedding->event_type,
                'event_date' => $wedding->event_date->format('Y-m-d'),
                'is_active' => $wedding->is_active,
            ],
            'photos' => $photos,
            'isAdmin' => false,
            'backUrl' => route('dashboard'),
        ]);
    }

    public function downloadSelected(Request $request): StreamedResponse
    {
        $wedding = $request->user()->wedding;

        if (!$wedding) {
            abort(404, 'No wedding associated with this account.');
        }

        $photoIds = explode(',', $request->query('photos', ''));
        $photoIds = array_filter($photoIds, fn($id) => is_numeric($id));

        if (empty($photoIds)) {
            abort(400, 'No photos selected.');
        }

        $photos = $wedding->photos()->whereIn('id', $photoIds)->get();

        if ($photos->isEmpty()) {
            abort(404, 'No photos found.');
        }

        $zipFileName = str_replace(' ', '_', $wedding->couple_name) . '_selectate.zip';

        return response()->streamDownload(function () use ($photos) {
            $tempFile = tempnam(sys_get_temp_dir(), 'selected_photos_');
            $zip = new ZipArchive();
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($photos as $photo) {
                $filePath = Storage::disk('public')->path($photo->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $photo->original_name);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
