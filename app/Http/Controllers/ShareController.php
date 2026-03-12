<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class ShareController extends Controller
{
    /**
     * Display a shared collection by its token.
     */
    public function show(string $token)
    {
        $collection = Collection::where('share_token', $token)
            ->firstOrFail();

        $collection->load('user');

        $bookmarks = $collection->bookmarks()
            ->with('tags')
            ->orderByPivot('position')
            ->paginate(20);

        return view('share.show', compact('collection', 'bookmarks'));
    }
}
