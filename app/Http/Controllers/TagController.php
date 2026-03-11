<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = $request->user()
            ->tags()
            ->withCount('bookmarks')
            ->orderBy('name')
            ->get();

        return view('tags.index', compact('tags'));
    }

    public function destroy(Tag $tag)
    {
        // Verify ownership
        abort_unless($tag->user_id === request()->user()->id, 403);
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted.');
    }
}