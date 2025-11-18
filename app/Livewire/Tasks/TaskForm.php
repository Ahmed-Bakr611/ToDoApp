<?php

namespace App\Livewire\Tasks;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TaskForm extends Component
{
  public ?Task $task = null;

  public string $title = '';
  public string $description = '';
  public string $tagSearch = '';
  public array $selectedTags = [];
  public array $searchResults = [];
  public bool $showResults = false;

  public function mount(?Task $task = null): void
  {
    if ($task) {
      $this->task = $task;
      $this->title = $task->title;
      $this->description = $task->description;
      $this->selectedTags = $task->tags->pluck('id')->map(fn($id) => (string)$id)->toArray();
    }
  }

  public function render(): View
  {
    return view('livewire.tasks.task-form');
  }

  public function updatedTagSearch(string $query): void
  {
    if (strlen($query) < 2) {
      $this->showResults = false;
      $this->searchResults = [];
      return;
    }

    $existingTagIds = array_map(fn($id) => (int)$id, $this->selectedTags);

    $this->searchResults = Tag::where('name', 'like', "%$query%")
      ->whereNotIn('id', $existingTagIds)
      ->limit(10)
      ->get()
      ->map(fn($tag) => [
        'id' => (string)$tag->id,
        'name' => $tag->name,
      ])
      ->toArray();

    $this->showResults = !empty($this->searchResults);
  }

  public function addTag(string $tagId, string $tagName): void
  {
    if (!in_array($tagId, $this->selectedTags)) {
      $this->selectedTags[] = $tagId;
    }

    $this->tagSearch = '';
    $this->showResults = false;
    $this->searchResults = [];
  }

  public function removeTag(string $tagId): void
  {
    $this->selectedTags = array_filter(
      $this->selectedTags,
      fn($id) => $id !== $tagId
    );
  }

  public function save(): void
  {
    $this->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'selectedTags' => 'array',
    ]);

    if ($this->task) {
      $this->authorize('update', $this->task);
      $this->task->update([
        'title' => $this->title,
        'description' => $this->description,
      ]);
      $this->task->tags()->sync(array_map('intval', $this->selectedTags));

      return redirect()->route('tasks.show', $this->task)->with('success', 'Task updated successfully!');
    }

    $task = auth()->user()->tasks()->create([
      'title' => $this->title,
      'description' => $this->description,
    ]);

    $task->tags()->attach(array_map('intval', $this->selectedTags));

    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
  }
}
