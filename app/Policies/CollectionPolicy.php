<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;

class CollectionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Collection $collection): bool
    {
        if ($collection->user_id === $user?->id) {
            return true;
        }

        return $collection->is_public;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Collection $collection): bool
    {
        return $user->id === $collection->user_id;
    }

    public function delete(User $user, Collection $collection): bool
    {
        return $user->id === $collection->user_id;
    }
}