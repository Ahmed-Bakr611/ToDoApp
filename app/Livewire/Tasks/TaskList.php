<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TaskList extends Component
{
  use WithPagination;

  #[Url]
  public string $tab = 'active';

  public function render(): View
  {
    $tasks = auth()->user()->tasks();

    if ($this->tab === 'completed') {
      $tasks = $tasks->where('completed', true);
    } else {
      $tasks = $tasks->where('completed', false);
    }

    $tasks = $tasks->with('tags')->latest()->paginate(10);

    $activeCount = auth()->user()->tasks()->where('completed', false)->count();
    $completedCount = auth()->user()->tasks()->where('completed', true)->count();

    return view('livewire.tasks.task-list', [
      'tasks' => $tasks,
      'activeCount' => $activeCount,
      'completedCount' => $completedCount,
    ]);
  }

  public function setTab(string $tab): void
  {
    $this->tab = $tab;
    $this->resetPage();
  }

  public function toggleTask(Task $task): void
  {
    $this->authorize('update', $task);

    $task->update(['completed' => !$task->completed]);
    $this->dispatch('task-toggled', taskId: $task->id);
  }

  public function deleteTask(Task $task): void
  {
    $this->authorize('delete', $task);

    $task->delete();
    $this->dispatch('task-deleted', taskId: $task->id);
  }
}
