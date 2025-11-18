<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Redirect;

class TaskDetail extends Component
{
  public Task $task;

  public function render(): View
  {
    return view('livewire.tasks.task-detail');
  }

  #[Redirect]
  public function deleteTask()
  {
    $this->authorize('delete', $this->task);

    $this->task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
  }
}
