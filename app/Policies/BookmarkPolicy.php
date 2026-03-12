<?php

namespace App\Policies;

use App\Models\Bookmark;
use App\Models\User;

class BookmarkPolicy
{
    // Anyone can view the bookmark list (filtered by auth in controller)
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Bookmarks are always private to the owner
    public function view(User $user, Bookmark $bookmark): bool
    {
        return $user->id === $bookmark->user_id;
    }

    public function create(User $user): bool
    {
        return true; // any authenticated user
    }

    public function update(User $user, Bookmark $bookmark): bool
    {
        return $user->id === $bookmark->user_id;
    }

    public function delete(User $user, Bookmark $bookmark): bool
    {
        return $user->id === $bookmark->user_id;
    }
}
