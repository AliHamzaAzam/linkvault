<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('bookmarks.index')
        : view('welcome');
});

// Public share route (outside auth middleware)
Route::get('share/{token}', [ShareController::class, 'show'])->name('share.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bookmarks
    Route::resource('bookmarks', BookmarkController::class);
    Route::post('bookmarks/{bookmark}/archive', [BookmarkController::class, 'archive'])
        ->name('bookmarks.archive');
    Route::get('search', [BookmarkController::class, 'search'])->name('bookmarks.search');

    // Collections
    Route::resource('collections', CollectionController::class);
    Route::post('collections/{collection}/share', [CollectionController::class, 'share'])
        ->name('collections.share');
    Route::delete('collections/{collection}/share', [CollectionController::class, 'unshare'])
        ->name('collections.unshare');

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

    // Import
    Route::get('import', [ImportController::class, 'create'])->name('import.create');
    Route::post('import', [ImportController::class, 'store'])->name('import.store');
});

require __DIR__.'/auth.php';
