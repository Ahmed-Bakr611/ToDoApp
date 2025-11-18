<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagService
{
    private const PAGE_SIZE = 20;

    /**
     * Get paginated tags with task count
     */
    // public function getPaginatedTagsWithTaskCount(): LengthAwarePaginator
    // {
    //     return Tag::withCount('tasks')->paginate(self::PAGE_SIZE);
    // }
    public function getPaginatedTagsWithTaskCount(): LengthAwarePaginator
    {
        return Tag::withCount(['tasks as tasks_count' => function ($query) {
            $query->where('user_id', Auth::id()); // count only tasks of current user
        }])
            ->paginate(self::PAGE_SIZE);
    }

    /**
     * Search tags by name
     */
    public function searchTags(string $query, int $limit = 50): Collection
    {
        if (strlen($query) < 2) {
            return new Collection();
        }

        return Tag::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * Fetch tags by IDs
     */
    public function getTagsByIds(array $ids): Collection
    {
        if (empty($ids)) {
            return new Collection();
        }

        return Tag::whereIn('id', $ids)
            ->select('id', 'name')
            ->get();
    }

    /**
     * Update tag
     */
    public function updateTag(Tag $tag, array $validatedData): Tag
    {
        $tag->update($validatedData);
        return $tag->fresh(); // Return fresh instance
    }

    /**
     * Delete tag if not used
     */
    public function deleteTagIfUnused(Tag $tag): bool
    {
        if ($tag->tasks()->count() > 0) {
            return false;
        }

        return $tag->delete();
    }

    /**
     * Get tag with task count
     */
    public function getTagWithTaskCount(Tag $tag): Tag
    {
        return $tag->loadCount('tasks');
    }
}
