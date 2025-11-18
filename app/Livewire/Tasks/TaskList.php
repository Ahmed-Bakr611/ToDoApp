<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class TaskList extends Component
{
  use WithPagination;

  public string $tab = 'active';

  private readonly TaskService $taskService;

  public function boot(TaskService $taskService): void
  {
    // Livewire injects the service here on hydration AND first load
    $this->taskService = $taskService;
  }

  public function render(): View
  {
    $data = $this->taskService->getTasksWithCounts(
      $this->tab === 'completed' ? 'completed' : 'active'
    );

    return view('livewire.tasks.task-list', [
      'tasks' => $data['tasks'],
      'activeCount' => $data['counts']->active_count ?? 0,
      'completedCount' => $data['counts']->completed_count ?? 0,
    ]);
  }
  public function setTab(string $tab): void
  {
    $this->tab = $tab;
    $this->resetPage();
  }

  public function toggleTask(int $taskId): void
  {
    $task = Task::findOrFail($taskId);

    $this->authorize('update', $task);

    $this->taskService->toggleComplete($task);

    $this->dispatch('task-toggled', taskId: $task->id);
  }

  public function deleteTask(int $taskId): void
  {
    $task = Task::findOrFail($taskId);

    $this->authorize('delete', $task);

    $task->delete();

    $this->dispatch('task-deleted', taskId: $taskId);
  }
}
