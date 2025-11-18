<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function __construct(
        private TagService $tagService
    ) {
        // Auto-apply policies using middleware
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Display a listing of the user's tags.
     */
    public function index(): View
    {
        $tags = $this->tagService->getPaginatedTagsWithTaskCount();

        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag): View
    {
        // Policy automatically checks authorization via middleware
        $tag = $this->tagService->getTagWithTaskCount($tag);

        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        // Authorization handled by policy via middleware

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $this->tagService->updateTag($tag, $validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag updated successfully! All tasks using this tag now show the new name.');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        // Authorization handled by policy via middleware

        if (!$this->tagService->deleteTagIfUnused($tag)) {
            return redirect()
                ->route('tags.index')
                ->with('error', 'Cannot delete this tag because it is assigned to one or more tasks.');
        }

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    /**
     * Search tags by name
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $tags = $this->tagService->searchTags($query);

        return response()->json(['tags' => $tags]);
    }

    /**
     * Fetch tags by IDs
     */
    public function fetch(Request $request): JsonResponse
    {
        $ids = $request->input('ids', '');
        $idsArray = empty($ids) ? [] : explode(',', $ids);

        $tags = $this->tagService->getTagsByIds($idsArray);

        return response()->json(['tags' => $tags]);
    }
}
