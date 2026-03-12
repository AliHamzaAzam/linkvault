<?php

use App\Http\Controllers\Api\V1\BookmarkController;
use App\Http\Controllers\Api\V1\CollectionController;
use App\Http\Controllers\Api\V1\ImportController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\TokenController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Token management
    Route::post('tokens', [TokenController::class, 'store']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User
        Route::get('user', function () {
            return response()->json([
                'data' => [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'username' => auth()->user()->username,
                ],
            ]);
        });

        // Revoke token
        Route::delete('tokens', [TokenController::class, 'destroy']);

        // Bookmarks (API namespaced to avoid conflicts)
        Route::apiResource('bookmarks', BookmarkController::class)->names([
            'index' => 'api.bookmarks.index',
            'store' => 'api.bookmarks.store',
            'show' => 'api.bookmarks.show',
            'update' => 'api.bookmarks.update',
            'destroy' => 'api.bookmarks.destroy',
        ]);
        Route::post('bookmarks/{bookmark}/archive', [BookmarkController::class, 'archive'])->name('api.bookmarks.archive');
        Route::get('search', [BookmarkController::class, 'search'])->name('api.bookmarks.search');

        // Collections (API namespaced to avoid conflicts)
        Route::apiResource('collections', CollectionController::class)->names([
            'index' => 'api.collections.index',
            'store' => 'api.collections.store',
            'show' => 'api.collections.show',
            'update' => 'api.collections.update',
            'destroy' => 'api.collections.destroy',
        ]);

        // Tags
        Route::get('tags', [TagController::class, 'index']);
        Route::delete('tags/{tag}', [TagController::class, 'destroy']);

        // Import
        Route::post('import', [ImportController::class, 'store']);
    });
});
