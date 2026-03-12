<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookmarkRequest;
use App\Http\Requests\UpdateBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Services\BookmarkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookmarkController extends Controller
{
    public function __construct(
        private BookmarkService $bookmarkService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $bookmarks = $this->bookmarkService->list(
            $request->user(),
            $request->only(['collection', 'tag', 'archived'])
        );

        return BookmarkResource::collection($bookmarks);
    }

    public function store(StoreBookmarkRequest $request): BookmarkResource
    {
        $bookmark = $this->bookmarkService->create($request->user(), $request->validated());
        $bookmark->load(['tags', 'collections']);

        return new BookmarkResource($bookmark);
    }

    public function show(Bookmark $bookmark): BookmarkResource
    {
        $this->authorize('view', $bookmark);
        $bookmark->load(['tags', 'collections']);

        return new BookmarkResource($bookmark);
    }

    public function update(UpdateBookmarkRequest $request, Bookmark $bookmark): BookmarkResource
    {
        $this->authorize('update', $bookmark);
        $bookmark = $this->bookmarkService->update($bookmark, $request->validated());

        return new BookmarkResource($bookmark);
    }

    public function destroy(Bookmark $bookmark): JsonResponse
    {
        $this->authorize('delete', $bookmark);
        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted successfully']);
    }

    public function archive(Bookmark $bookmark): BookmarkResource
    {
        $this->authorize('update', $bookmark);
        $bookmark = $this->bookmarkService->toggleArchive($bookmark);

        return new BookmarkResource($bookmark);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return BookmarkResource::collection(collect());
        }

        $bookmarks = $this->bookmarkService->search($request->user(), $query);

        return BookmarkResource::collection($bookmarks);
    }
}
