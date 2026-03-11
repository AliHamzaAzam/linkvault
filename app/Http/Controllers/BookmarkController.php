<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Requests\UpdateBookmarkRequest;
use App\Models\Bookmark;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function __construct(
        private BookmarkService $bookmarkService
    ) {}

    public function index(Request $request)
    {
        $bookmarks = $this->bookmarkService->list(
            $request->user(),
            $request->only(['collection', 'tag', 'archived'])
        );

        $collections = $request->user()->collections()->orderBy('position')->get();
        $tags = $request->user()->tags()->orderBy('name')->get();

        return view('bookmarks.index', compact('bookmarks', 'collections', 'tags'));
    }

    public function create(Request $request)
    {
        $collections = $request->user()->collections()->orderBy('position')->get();
        $tags = $request->user()->tags()->orderBy('name')->get();

        return view('bookmarks.create', compact('collections', 'tags'));
    }

    public function store(StoreBookmarkRequest $request)
    {
        $this->bookmarkService->create($request->user(), $request->validated());

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark saved! Metadata will be fetched shortly.');
    }

    public function show(Bookmark $bookmark)
    {
        $this->authorize('view', $bookmark);
        $bookmark->load(['tags', 'collections']);

        return view('bookmarks.show', compact('bookmark'));
    }

    public function edit(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);
        $bookmark->load(['tags', 'collections']);

        $collections = $bookmark->user->collections()->orderBy('position')->get();
        $tags = $bookmark->user->tags()->orderBy('name')->get();

        return view('bookmarks.edit', compact('bookmark', 'collections', 'tags'));
    }

    public function update(UpdateBookmarkRequest $request, Bookmark $bookmark)
    {
        $this->bookmarkService->update($bookmark, $request->validated());

        return redirect()->route('bookmarks.show', $bookmark)
            ->with('success', 'Bookmark updated.');
    }

    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);
        $bookmark->delete();

        return redirect()->route('bookmarks.index')
            ->with('success', 'Bookmark deleted.');
    }

    public function archive(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);
        $this->bookmarkService->toggleArchive($bookmark);

        $status = $bookmark->is_archived ? 'archived' : 'unarchived';

        return redirect()->back()
            ->with('success', "Bookmark {$status}.");
    }

    public function search(Request $request): View
    {
        $query = $request->input('q', '');
        $bookmarks = empty($query)
            ? collect()
            : $this->bookmarkService->search($request->user(), $query);

        return view('bookmarks.search', compact('bookmarks', 'query'));
    }
}