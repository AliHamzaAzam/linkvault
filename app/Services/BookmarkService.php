<?php

namespace App\Services;

use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class BookmarkService
{
    public function list(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = $user->bookmarks()->with(['tags', 'collections'])->latest();

        if (isset($filters['collection'])) {
            $query->whereHas('collections', fn ($q) =>
                $q->where('collections.id', $filters['collection'])
            );
        }

        if (isset($filters['tag'])) {
            $query->whereHas('tags', fn ($q) =>
                $q->where('tags.id', $filters['tag'])
            );
        }

        if (isset($filters['archived'])) {
            $query->where('is_archived', $filters['archived']);
        } else {
            $query->where('is_archived', false); // hide archived by default
        }

        return $query->paginate(20);
    }

    public function create(User $user, array $data): Bookmark
    {
        $bookmark = $user->bookmarks()->create([
            'url' => $data['url'],
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'is_public' => $data['is_public'] ?? false,
        ]);

        $this->syncTags($user, $bookmark, $data['tags'] ?? []);
        $this->syncCollections($bookmark, $data['collection_ids'] ?? []);

        // Event dispatch will be added in Phase 3
        // event(new BookmarkCreated($bookmark));

        return $bookmark;
    }

    public function update(Bookmark $bookmark, array $data): Bookmark
    {
        $bookmark->update([
            'title' => $data['title'] ?? $bookmark->title,
            'description' => $data['description'] ?? $bookmark->description,
            'is_public' => $data['is_public'] ?? $bookmark->is_public,
        ]);

        if (isset($data['tags'])) {
            $this->syncTags($bookmark->user, $bookmark, $data['tags']);
        }

        if (isset($data['collection_ids'])) {
            $this->syncCollections($bookmark, $data['collection_ids']);
        }

        return $bookmark->fresh(['tags', 'collections']);
    }

    public function toggleArchive(Bookmark $bookmark): Bookmark
    {
        $bookmark->update(['is_archived' => !$bookmark->is_archived]);
        return $bookmark;
    }

    /**
     * Sync tags by name — creates new tags if they don't exist.
     * Accepts an array of tag name strings.
     */
    private function syncTags(User $user, Bookmark $bookmark, array $tagNames): void
    {
        if (empty($tagNames)) {
            $bookmark->tags()->detach();
            return;
        }

        $tagIds = collect($tagNames)->map(function ($name) use ($user) {
            $name = trim($name);
            if (empty($name)) return null;

            return Tag::firstOrCreate(
                ['user_id' => $user->id, 'slug' => \Illuminate\Support\Str::slug($name)],
                ['user_id' => $user->id, 'name' => $name]
            )->id;
        })->filter()->all();

        $bookmark->tags()->sync($tagIds);
    }

    private function syncCollections(Bookmark $bookmark, array $collectionIds): void
    {
        $bookmark->collections()->sync($collectionIds);
    }
}
