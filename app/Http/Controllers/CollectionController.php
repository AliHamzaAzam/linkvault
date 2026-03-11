<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollectionRequest;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $collections = $request->user()
            ->collections()
            ->withCount('bookmarks')
            ->orderBy('position')
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function store(StoreCollectionRequest $request)
    {
        $request->user()->collections()->create($request->validated());

        return redirect()->route('collections.index')
            ->with('success', 'Collection created.');
    }

    public function show(Collection $collection, Request $request)
    {
        $this->authorize('view', $collection);

        $bookmarks = $collection->bookmarks()
            ->with('tags')
            ->orderByPivot('position')
            ->paginate(20);

        return view('collections.show', compact('collection', 'bookmarks'));
    }

    public function edit(Collection $collection)
    {
        $this->authorize('update', $collection);
        return view('collections.edit', compact('collection'));
    }

    public function update(StoreCollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);
        $collection->update($request->validated());

        return redirect()->route('collections.show', $collection)
            ->with('success', 'Collection updated.');
    }

    public function destroy(Collection $collection)
    {
        $this->authorize('delete', $collection);
        $collection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted.');
    }
}