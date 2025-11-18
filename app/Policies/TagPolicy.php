<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine if the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Or your business logic
    }

    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, Tag $tag): bool
    {
        return $this->belongsToUser($user, $tag);
    }

    /**
     * Determine if the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, Tag $tag): bool
    {
        return $this->belongsToUser($user, $tag);
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $this->belongsToUser($user, $tag) && $tag->tasks_count === 0;
    }

    /**
     * Check if tag belongs to user
     */
    private function belongsToUser(User $user, Tag $tag): bool
    {
        // If tags are user-specific, check ownership
        if (method_exists($tag, 'user')) {
            return $tag->user_id === $user->id;
        }

        // If tags are global, you might want different logic
        return true;
    }
}
