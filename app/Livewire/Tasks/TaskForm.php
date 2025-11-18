<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Tag;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class TaskForm extends Component
{
  public ?Task $task = null;
  public string $title = '';
  public string $description = '';
  public string $tagSearch = '';
  public bool $showResults = false;
  public array $searchResults = [];
  public array $selectedTags = [];

  protected TaskService $taskService;

  /**
   * Inject the service via boot method
   */
  public function boot(TaskService $taskService): void
  {
    $this->taskService = $taskService;
  }

  /**
   * Initialize properties when component is mounted
   */
  public function mount($task = null): void
  {
    Log::info('TaskForm mounted', ['task' => $task?->id, 'type' => gettype($task)]);

    if ($task instanceof Task) {
      $this->task = $task;
      $this->title = $task->title;
      $this->description = $task->description ?? '';
      $this->selectedTags = $task->tags->pluck('id')->toArray();

      Log::info('Loaded existing task', [
        'task_id' => $task->id,
        'selected_tags' => $this->selectedTags
      ]);
    } else {
      Log::info('Creating new task');
    }
  }

  public function render(): View
  {
    return view('livewire.tasks.task-form');
  }

  public function updatedTagSearch($value): void
  {
    if (strlen($value) < 2) {
      $this->showResults = false;
      $this->searchResults = [];
      return;
    }

    $results = Tag::where('name', 'like', '%' . $value . '%')
      ->whereNotIn('id', $this->selectedTags)
      ->limit(5)
      ->get()
      ->map(fn($tag) => ['id' => $tag->id, 'name' => $tag->name])
      ->toArray();

    $this->searchResults = $results;
    $this->showResults = true;
  }

  public function addTag($tagId): void
  {
    $tagId = (int)$tagId;

    if (!in_array($tagId, $this->selectedTags)) {
      $this->selectedTags[] = $tagId;
    }

    $this->tagSearch = '';
    $this->showResults = false;
    $this->searchResults = [];
  }

  public function removeTag($tagId): void
  {
    $tagId = (int)$tagId;
    $this->selectedTags = array_filter($this->selectedTags, fn($id) => $id !== $tagId);
  }

  public function save()
  {
    $this->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    if ($this->task) {
      // Use the service to update
      $this->taskService->updateTask($this->task, [
        'title' => $this->title,
        'description' => $this->description,
        'tags' => $this->selectedTags,
      ]);

      return redirect()->route('tasks.show', $this->task)
        ->with('success', 'Task updated successfully!');
    }

    // Use the service to create
    $task = $this->taskService->createTask([
      'title' => $this->title,
      'description' => $this->description,
      'user_id' => auth()->id(),
      'tags' => $this->selectedTags,
    ]);

    return redirect()->route('tasks.show', $task)
      ->with('success', 'Task created successfully!');
  }
}
