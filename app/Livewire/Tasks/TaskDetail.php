<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Redirect;

class TaskDetail extends Component
{
  public Task $task;

  private readonly TaskService $taskService;

  /**
   * Inject TaskService via boot method
   */
  public function boot(TaskService $taskService): void
  {
    $this->taskService = $taskService;
  }

  public function render(): View
  {
    return view('livewire.tasks.task-detail');
  }
}
