<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeddingRequest;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class WeddingController extends Controller
{
    public function index(): Response
    {
        $weddings = Wedding::with('user')
            ->withCount('photos')
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Events/Index', [
            'weddings' => $weddings,
            'eventTypes' => Wedding::getEventTypeOptions(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Events/Create', [
            'eventTypes' => Wedding::getEventTypeOptions(),
        ]);
    }

    public function store(StoreWeddingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Create couple user account
        $user = User::create([
            'name' => $validated['couple_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'couple',
        ]);

        // Create wedding/event
        $wedding = Wedding::create([
            'couple_name' => $validated['couple_name'],
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'upload_code' => $this->generateUniqueCode(),
            'client_password' => $validated['password'],
            'user_id' => $user->id,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.events.show', $wedding)
            ->with('success', 'Eveniment creat cu succes.');
    }

    public function show(Wedding $wedding): Response
    {
        $wedding->load(['user', 'photos']);

        $weddingData = $wedding->toArray();
        $weddingData['event_type_label'] = $wedding->getEventTypeLabel();
        $weddingData['photos'] = $wedding->photos->map(function ($photo) {
            return array_merge($photo->toArray(), [
                'url' => $photo->getUrl(),
            ]);
        });

        return Inertia::render('Admin/Events/Show', [
            'clientPassword' => $wedding->client_password,
            'wedding' => $weddingData,
            'uploadUrl' => $wedding->getUploadUrl(),
            'eventTypes' => Wedding::getEventTypeOptions(),
        ]);
    }

    public function update(Request $request, Wedding $wedding): RedirectResponse
    {
        $validated = $request->validate([
            'couple_name' => 'sometimes|string|max:255',
            'event_type' => 'sometimes|in:nunta,majorat,zi_de_nastere,botez,altele',
            'event_date' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $wedding->update($validated);

        // Also update the associated user's name if couple_name changed
        if (isset($validated['couple_name'])) {
            $wedding->user->update(['name' => $validated['couple_name']]);
        }

        return redirect()->back()->with('success', 'Eveniment actualizat cu succes.');
    }

    public function destroy(Wedding $wedding): RedirectResponse
    {
        // Delete associated user account
        $wedding->user->delete();

        // Wedding will be cascade deleted due to foreign key constraint

        return redirect()->route('admin.events.index')
            ->with('success', 'Eveniment și cont asociat șterse.');
    }

    public function regenerateCode(Wedding $wedding): RedirectResponse
    {
        $wedding->update([
            'upload_code' => $this->generateUniqueCode(),
        ]);

        return redirect()->back()->with('success', 'Link-ul de upload a fost regenerat.');
    }

    public function gallery(Wedding $wedding): Response
    {
        $wedding->load('photos');

        $photos = $wedding->photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->getUrl(),
                'path' => $photo->path,
                'original_name' => $photo->original_name,
                'size' => $photo->size,
                'uploaded_by_name' => $photo->uploaded_by_name,
                'created_at' => $photo->created_at,
            ];
        });

        return Inertia::render('Gallery', [
            'wedding' => $wedding,
            'photos' => $photos,
            'isAdmin' => true,
            'backUrl' => route('admin.events.index'),
        ]);
    }

    public function download(Wedding $wedding): StreamedResponse
    {
        $wedding->load('photos');

        $zipFileName = Str::slug($wedding->couple_name) . '-fotografii.zip';

        return response()->streamDownload(function () use ($wedding) {
            $zip = new ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'wedding_photos_');
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($wedding->photos as $photo) {
                if (Storage::disk('r2')->exists($photo->path)) {
                    $content = Storage::disk('r2')->get($photo->path);
                    $zip->addFromString($photo->original_name, $content);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    public function downloadSelected(Request $request, Wedding $wedding): StreamedResponse
    {
        $photoIds = explode(',', $request->query('photos', ''));
        $photoIds = array_filter($photoIds, fn($id) => is_numeric($id));

        if (empty($photoIds)) {
            abort(400, 'No photos selected.');
        }

        $photos = $wedding->photos()->whereIn('id', $photoIds)->get();

        if ($photos->isEmpty()) {
            abort(404, 'No photos found.');
        }

        $zipFileName = Str::slug($wedding->couple_name) . '-selectate.zip';

        return response()->streamDownload(function () use ($photos) {
            $zip = new ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'selected_photos_');
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($photos as $photo) {
                if (Storage::disk('r2')->exists($photo->path)) {
                    $content = Storage::disk('r2')->get($photo->path);
                    $zip->addFromString($photo->original_name, $content);
                }
            }

            $zip->close();

            readfile($tempFile);
            unlink($tempFile);
        }, $zipFileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = Str::random(8);
        } while (Wedding::where('upload_code', $code)->exists());

        return $code;
    }
}
