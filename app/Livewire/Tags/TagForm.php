<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Redirect;

class TagForm extends Component
{
  public ?Tag $tag = null;
  public string $name = '';

  public function mount(?Tag $tag = null): void
  {
    if ($tag) {
      $this->tag = $tag;
      $this->name = $tag->name;
    }
  }

  public function render(): View
  {
    return view('livewire.tags.tag-form');
  }

  public function save()
  {
    $this->validate([
      'name' => 'required|string|max:255|unique:tags,name,' . ($this->tag->id ?? 'NULL'),
    ]);

    if ($this->tag) {
      $this->tag->update(['name' => $this->name]);
      return redirect()->route('tags.index')->with('success', 'Tag updated successfully!');
    }

    Tag::create(['name' => $this->name]);

    return redirect()->route('tags.index')->with('success', 'Tag created successfully!');
  }
}
