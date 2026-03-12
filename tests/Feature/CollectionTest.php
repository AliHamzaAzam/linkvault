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
            'is_public' => true,
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
            'is_public' => $collection->is_public,
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
            'is_public' => $collection->is_public,
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
