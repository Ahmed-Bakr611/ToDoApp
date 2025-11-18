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
    $tag->delete();
    $this->dispatch('tag-deleted', tagId: $tag->id);
  }
}
