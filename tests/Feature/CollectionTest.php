<?php

use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Collection Management', function () {
    it('allows creating collections', function () {
        $response = $this->post('/collections', [
            'name' => 'My Collection',
            'description' => 'A test collection',
            'color' => '#3B82F6',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collections', [
            'name' => 'My Collection',
            'user_id' => $this->user->id,
        ]);
    });

    it('allows viewing own collections', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get("/collections/{$collection->id}");

        $response->assertStatus(200);
        $response->assertViewHas('collection');
    });

    it('allows updating own collections', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put("/collections/{$collection->id}", [
            'name' => 'Updated Collection',
            'description' => $collection->description,
            'color' => $collection->color,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collections', [
            'id' => $collection->id,
            'name' => 'Updated Collection',
        ]);
    });

    it('prevents updating other users collections', function () {
        $otherUser = User::factory()->create();
        $collection = Collection::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->put("/collections/{$collection->id}", [
            'name' => 'Hacked Collection',
            'description' => $collection->description,
            'color' => $collection->color,
        ]);

        $response->assertStatus(403);
    });

    it('allows deleting own collections', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete("/collections/{$collection->id}");

        $response->assertRedirect('/collections');
        $this->assertDatabaseMissing('collections', ['id' => $collection->id]);
    });

    it('prevents deleting other users collections', function () {
        $otherUser = User::factory()->create();
        $collection = Collection::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->delete("/collections/{$collection->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('collections', ['id' => $collection->id]);
    });

    it('does not delete bookmarks when collection is deleted', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);
        $bookmark = Bookmark::factory()->create(['user_id' => $this->user->id]);
        $bookmark->collections()->attach($collection->id);

        $this->delete("/collections/{$collection->id}");

        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id]);
        $this->assertDatabaseMissing('collections', ['id' => $collection->id]);
    });
});

describe('Collection Sharing', function () {
    it('generates a share token', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);

        $response = $this->post(route('collections.share', $collection));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $collection->refresh();
        expect($collection->share_token)->not->toBeNull();
        expect(strlen($collection->share_token))->toBe(32);
    });

    it('revokes a share token', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);
        $collection->generateShareToken();

        $response = $this->delete(route('collections.unshare', $collection));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $collection->refresh();
        expect($collection->share_token)->toBeNull();
    });

    it('allows viewing shared collection by token', function () {
        $collection = Collection::factory()->create(['user_id' => $this->user->id]);
        $collection->generateShareToken();
        
        $bookmark = Bookmark::factory()->create(['user_id' => $this->user->id]);
        $bookmark->collections()->attach($collection->id);

        // Logout to test public access
        auth()->logout();

        $response = $this->get(route('share.show', $collection->share_token));

        $response->assertStatus(200);
        $response->assertViewHas('collection');
        $response->assertViewHas('bookmarks');
        $response->assertSee($collection->name);
        $response->assertSee($bookmark->title);
    });

    it('returns 404 for invalid share token', function () {
        $response = $this->get(route('share.show', 'invalid-token-123'));

        $response->assertStatus(404);
    });

    it('prevents sharing other users collections', function () {
        $otherUser = User::factory()->create();
        $collection = Collection::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->post(route('collections.share', $collection));

        $response->assertStatus(403);
    });

    it('prevents revoking share on other users collections', function () {
        $otherUser = User::factory()->create();
        $collection = Collection::factory()->create(['user_id' => $otherUser->id]);
        $collection->generateShareToken();

        $response = $this->delete(route('collections.unshare', $collection));

        $response->assertStatus(403);
    });
});
