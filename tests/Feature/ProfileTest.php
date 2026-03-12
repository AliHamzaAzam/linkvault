<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('Profile Management', function () {
    it('allows user to view their profile edit page', function () {
        $response = $this->actingAs($this->user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewHas('user');
    });

    it('allows user to update their profile information', function () {
        $response = $this->actingAs($this->user)->patch('/profile', [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('status', 'profile-updated');
        
        $this->user->refresh();
        expect($this->user->name)->toBe('Updated Name');
        expect($this->user->email)->toBe('newemail@example.com');
    });

    it('requires password confirmation to delete account', function () {
        $response = $this->actingAs($this->user)->delete('/profile', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('password', errorBag: 'userDeletion');
        $this->assertDatabaseHas('users', ['id' => $this->user->id]);
    });

    it('allows user to delete their account with correct password', function () {
        $response = $this->actingAs($this->user)->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
    });

    it('redirects guests to login', function () {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    });
});
