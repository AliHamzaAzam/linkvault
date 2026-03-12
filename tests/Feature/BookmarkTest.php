<?php

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\Tag;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Bookmark Management', function () {
    it('allows user to view their bookmarks', function () {
        Bookmark::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->get('/bookmarks');

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks');
    });

    it('creates bookmark with auto-created tags', function () {
        $response = $this->post('/bookmarks', [
            'url' => 'https://example.com',
            'title' => 'Test Bookmark',
            'tags' => 'laravel, php, coding',
        ]);

        $response->assertRedirect('/bookmarks');
        
        $this->assertDatabaseHas('bookmarks', [
            'url' => 'https://example.com',
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'laravel',
            'user_id' => $this->user->id,
        ]);
    });

    it('prevents viewing other users bookmarks', function () {
        $otherUser = User::factory()->create();
        $bookmark = Bookmark::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->get(route('bookmarks.show', $bookmark));

        $response->assertStatus(403);
    });

    it('toggles archive status', function () {
        $bookmark = Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'is_archived' => false,
        ]);

        $response = $this->post("/bookmarks/{$bookmark->id}/archive");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'is_archived' => true,
        ]);
    });

    it('filters bookmarks by collection', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);
        $bookmarkInCollection = Bookmark::factory()->create(['user_id' => $this->user->id]);
        $bookmarkInCollection->collections()->attach($collection->id);
        
        Bookmark::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get("/bookmarks?collection={$collection->id}");

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) use ($bookmarkInCollection) {
            return $bookmarks->contains($bookmarkInCollection);
        });
    });

    it('rejects duplicate URL for same user', function () {
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'url' => 'https://example.com',
        ]);

        $response = $this->post('/bookmarks', [
            'url' => 'https://example.com',
            'title' => 'Duplicate Bookmark',
        ]);

        $response->assertSessionHasErrors('url');
    });

    it('allows same URL for different users', function () {
        $otherUser = User::factory()->create();
        Bookmark::factory()->create([
            'user_id' => $otherUser->id,
            'url' => 'https://example.com',
        ]);

        $response = $this->post('/bookmarks', [
            'url' => 'https://example.com',
            'title' => 'My Bookmark',
        ]);

        $response->assertRedirect('/bookmarks');
        $this->assertDatabaseHas('bookmarks', [
            'url' => 'https://example.com',
            'user_id' => $this->user->id,
        ]);
    });

    it('allows user to delete their bookmark', function () {
        $bookmark = Bookmark::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/bookmarks/{$bookmark->id}");

        $response->assertRedirect('/bookmarks');
        $this->assertDatabaseMissing('bookmarks', ['id' => $bookmark->id]);
    });

    it('prevents deleting other users bookmarks', function () {
        $otherUser = User::factory()->create();
        $bookmark = Bookmark::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->delete("/bookmarks/{$bookmark->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id]);
    });
});
