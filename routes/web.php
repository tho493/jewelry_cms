<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController as PublicProductController;
use App\Http\Controllers\Public\CategoryController as PublicCategoryController;
use App\Http\Controllers\Public\AboutController as PublicAboutController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/gioi-thieu', [PublicAboutController::class, 'index'])->name('about');
Route::get('/san-pham', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [PublicProductController::class, 'show'])->name('products.show');
Route::get('/danh-muc/{slug}', [PublicCategoryController::class, 'show'])->name('categories.show');

// ── Auth + Profile (Breeze) ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ── Admin Routes ───────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile');

        // Products CRUD
        Route::resource('products', AdminProductController::class);

        // Categories CRUD
        Route::resource('categories', AdminCategoryController::class);

        // About & Team Members
        Route::get('about', [AdminAboutController::class, 'edit'])->name('about.edit');
        Route::put('about', [AdminAboutController::class, 'update'])->name('about.update');
        Route::resource('team-members', AdminTeamMemberController::class)->except('show');

        // Media
        Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
        Route::patch('media/{media}/cover', [MediaController::class, 'setCover'])->name('media.cover');
        Route::post('media/reorder', [MediaController::class, 'reorder'])->name('media.reorder');
    });
