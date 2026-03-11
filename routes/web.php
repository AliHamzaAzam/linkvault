<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('bookmarks.index')
        : view('welcome');
});

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

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
});

require __DIR__.'/auth.php';