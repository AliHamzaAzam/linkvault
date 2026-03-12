<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CollectionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $collections = $request->user()
            ->collections()
            ->orderBy('position')
            ->get();

        return CollectionResource::collection($collections);
    }

    public function store(StoreCollectionRequest $request): CollectionResource
    {
        $collection = $request->user()->collections()->create($request->validated());

        return new CollectionResource($collection);
    }

    public function show(Collection $collection): CollectionResource
    {
        $this->authorize('view', $collection);

        return new CollectionResource($collection);
    }

    public function update(StoreCollectionRequest $request, Collection $collection): CollectionResource
    {
        $this->authorize('update', $collection);
        $collection->update($request->validated());

        return new CollectionResource($collection);
    }

    public function destroy(Collection $collection): JsonResponse
    {
        $this->authorize('delete', $collection);
        $collection->delete();

        return response()->json(['message' => 'Collection deleted successfully']);
    }
}
