<?php

use App\Models\Bookmark;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Search Functionality', function () {
    it('finds bookmarks by title', function () {
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Laravel Tutorial',
            'description' => 'A comprehensive guide',
        ]);
        
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Vue.js Guide',
            'description' => 'Frontend framework',
        ]);

        $response = $this->get('/search?q=Laravel');

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 1 && $bookmarks->first()->title === 'Laravel Tutorial';
        });
    });

    it('finds bookmarks by description', function () {
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Tutorial',
            'description' => 'Learn Laravel programming',
        ]);

        $response = $this->get('/search?q=programming');

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 1;
        });
    });

    it('returns empty for non-matching query', function () {
        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Laravel Tutorial',
        ]);

        $response = $this->get('/search?q=nonexistent');

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 0;
        });
    });

    it('does not return other users bookmarks', function () {
        $otherUser = User::factory()->create();
        Bookmark::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Laravel Secret',
        ]);

        Bookmark::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Laravel Tutorial',
        ]);

        $response = $this->get('/search?q=Laravel');

        $response->assertStatus(200);
        $response->assertViewHas('bookmarks', function ($bookmarks) {
            return $bookmarks->count() === 1 && $bookmarks->first()->title === 'Laravel Tutorial';
        });
    });
});
