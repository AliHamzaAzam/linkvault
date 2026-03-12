<?php

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Public Profiles', function () {
    it('displays public bookmarks on profile', function () {
        $publicBookmark = Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
            'title' => 'Public Tutorial',
        ]);

        $response = $this->get("/@{$this->user->username}");

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) use ($publicBookmark) {
            return $bookmarks->contains($publicBookmark);
        });
    });

    it('hides private bookmarks on profile', function () {
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => false,
            'title' => 'Private Secret',
        ]);

        $response = $this->get("/@{$this->user->username}");

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 0;
        });
    });

    it('returns 404 for non-existent username', function () {
        $response = $this->get('/@nonexistent-user-12345');

        $response->assertStatus(404);
    });

    it('displays public collection page', function () {
        $collection = Collection::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
            'slug' => 'tutorials',
        ]);

        $bookmark = Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
        ]);
        $bookmark->collections()->attach($collection->id);

        $response = $this->get("/@{$this->user->username}/tutorials");

        $response->assertStatus(200);
        $response->assertViewHas('collection');
        $response->assertViewHas('bookmarks');
    });

    it('hides private bookmarks in public collection', function () {
        $collection = Collection::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
            'slug' => 'mixed',
        ]);

        $publicBookmark = Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => true,
            'title' => 'Public',
        ]);
        $publicBookmark->collections()->attach($collection->id);

        $privateBookmark = Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_public' => false,
            'title' => 'Private',
        ]);
        $privateBookmark->collections()->attach($collection->id);

        $response = $this->get("/@{$this->user->username}/mixed");

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 1 && $bookmarks->first()->title === 'Public';
        });
    });
});
