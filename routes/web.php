<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\CardDesignerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PreorderController as AdminPreorderController;
use App\Http\Controllers\Admin\WeddingController;
use App\Http\Controllers\CoupleController;
use App\Http\Controllers\GuestUploadController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

// Guest Upload Routes (Public - No Auth Required)
Route::get('/upload/{code}', [GuestUploadController::class, 'show'])->name('guest.upload');
Route::post('/upload/{code}', [GuestUploadController::class, 'upload'])->name('guest.upload.store');

// Auto-Login Access Route (Public - No Auth Required)
Route::get('/access/{code}', [AccessController::class, 'login'])->name('access.login');

// Preorder Routes (Public)
Route::get('/precomanda', [PreorderController::class, 'create'])->name('preorder.create');
Route::post('/precomanda', [PreorderController::class, 'store'])->name('preorder.store');
Route::get('/precomanda/succes', [PreorderController::class, 'success'])->name('preorder.success');

// Card Designer Routes (Public)
Route::get('/card-designer', [CardDesignerController::class, 'index'])->name('card-designer.index');
Route::post('/card-designer/save', [CardDesignerController::class, 'save'])->name('card-designer.save');

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', WeddingController::class)->except(['edit'])->parameters(['events' => 'wedding']);
    Route::post('/events/{wedding}/regenerate-code', [WeddingController::class, 'regenerateCode'])->name('events.regenerate-code');
    Route::post('/events/{wedding}/regenerate-access-code', [WeddingController::class, 'regenerateAccessCode'])->name('events.regenerate-access-code');
    Route::get('/events/{wedding}/gallery', [WeddingController::class, 'gallery'])->name('events.gallery');
    Route::get('/events/{wedding}/download', [WeddingController::class, 'download'])->name('events.download');
    Route::get('/events/{wedding}/download-selected', [WeddingController::class, 'downloadSelected'])->name('events.download-selected');

    // Admin Preorder Routes
    Route::get('/preorders', [AdminPreorderController::class, 'index'])->name('preorders.index');
    Route::get('/preorders/{preorder}', [AdminPreorderController::class, 'show'])->name('preorders.show');
    Route::patch('/preorders/{preorder}/status', [AdminPreorderController::class, 'updateStatus'])->name('preorders.update-status');
    Route::delete('/preorders/{preorder}', [AdminPreorderController::class, 'destroy'])->name('preorders.destroy');
});

// Couple Routes
Route::middleware(['auth', 'verified', 'couple'])->group(function () {
    Route::get('/dashboard', [CoupleController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/photos', [CoupleController::class, 'photos'])->name('couple.photos');
    Route::get('/dashboard/download', [CoupleController::class, 'download'])->name('couple.download');
    Route::get('/dashboard/download-selected', [CoupleController::class, 'downloadSelected'])->name('couple.download-selected');
    Route::get('/gallery', [CoupleController::class, 'gallery'])->name('couple.gallery');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
