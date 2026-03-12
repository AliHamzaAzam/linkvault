<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $tags = $request->user()
            ->tags()
            ->orderBy('name')
            ->get();

        return TagResource::collection($tags);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $this->authorize('delete', $tag);
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }
}
