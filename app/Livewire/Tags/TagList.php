<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TagList extends Component
{
  use WithPagination;

  public function render(): View
  {
    $tags = Tag::withCount('tasks')->latest()->paginate(20);

    return view('livewire.tags.tag-list', [
      'tags' => $tags,
    ]);
  }

  public function deleteTag(Tag $tag): void
  {
    // Check if tag is assigned to any tasks
    if ($tag->tasks()->count() > 0) {
      $taskCount = $tag->tasks()->count();
      $taskWord = $taskCount === 1 ? 'task' : 'tasks';

      session()->flash('error', "Cannot delete tag '{$tag->name}' because it is assigned to {$taskCount} {$taskWord}. Please remove the tag from all tasks first.");
      return;
    }

    $tagName = $tag->name;
    $tag->delete();

    session()->flash('success', "Tag '{$tagName}' has been deleted successfully.");
    $this->dispatch('tag-deleted', tagId: $tag->id);
  }
}
