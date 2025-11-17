<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Repositories\Eloquent\EloquentGenericCrudRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private EloquentGenericCrudRepository $repo;

    // Fixed page size constant
    private const PAGE_SIZE = 20;

    public function __construct()
    {
        $this->repo = new EloquentGenericCrudRepository(new Tag());
    }

    /**
     * Display a listing of the user's tags.
     */
    public function index()
    {
        // Get paginated tags with task count
        $tags = Tag::withCount('tasks')->paginate(self::PAGE_SIZE);

        return view('tags.index', compact('tags'));


        // $tags = DB::table('tags')
        //     ->select(
        //         'tags.id',
        //         'tags.name',
        //         'tags.created_at',
        //         'tags.updated_at',
        //         DB::raw('COUNT(task_tag.task_id) as tasks_count')
        //     )
        //     ->leftJoin('task_tag', 'task_tag.tag_id', '=', 'tags.id')
        //     ->groupBy('tags.id', 'tags.name', 'tags.created_at', 'tags.updated_at')
        //     ->paginate(self::PAGE_SIZE);

        // return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag)
    {
        if (!$tag) {
            abort(404, 'Tag not found.');
        }

        // Only allow editing of the user's own tags (if tags belong to users)
        if (method_exists($tag, 'user') && $tag->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $tag->loadCount('tasks');

        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        if (!isset($tag) || !isset($request)) {
            abort(404, '');
        }

        if (!$tag) {
            abort(404, 'Tag not found.');
        }

        // Optional: user ownership check
        if (method_exists($tag, 'user') && $tag->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $this->repo->update($tag->id, $validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag updated successfully! All tasks using this tag now show the new name.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag)
    {
        if (!isset($tag)) {
            abort(404, '');
        }

        if (!$tag) {
            abort(404, 'Tag not found.');
        }

        // Optional: user ownership check
        if (method_exists($tag, 'user') && $tag->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if tag is used in any task
        if ($tag->tasks()->count() > 0) {
            return redirect()
                ->route('tags.index')
                ->with('error', 'Cannot delete this tag because it is assigned to one or more tasks.');
        }

        $this->repo->delete($tag->id);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    /**
     * Search tags by name
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        // Minimum 2 characters for search
        if (strlen($query) < 2) {
            return response()->json(['tags' => []]);
        }

        // Search tags with pagination/limit
        $tags = Tag::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name')
            ->orderBy('name')
            ->limit(50) // Limit results to 50 for performance
            ->get();

        return response()->json(['tags' => $tags]);
    }

    /**
     * Fetch tags by IDs
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch(Request $request)
    {
        $ids = $request->input('ids', '');

        if (empty($ids)) {
            return response()->json(['tags' => []]);
        }

        // Convert comma-separated string to array
        $idsArray = explode(',', $ids);

        // Fetch tags by IDs
        $tags = Tag::whereIn('id', $idsArray)
            ->select('id', 'name')
            ->get();

        return response()->json(['tags' => $tags]);
    }
}
