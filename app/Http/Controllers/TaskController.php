<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
  private TaskService $taskService;

  public function __construct(TaskService $taskService)
  {
    $this->taskService = $taskService;
    $this->authorizeResource(Task::class, 'task');
  }

  public function index(Request $request)
  {
    $tab = $request->get('tab', 'active');
    ['tasks' => $tasks, 'counts' => $counts] = $this->taskService->getTasksWithCounts($tab);

    return view('tasks.index', [
      'tasks' => $tasks,
      'tab' => $tab,
      'activeCount' => $counts->active_count,
      'completedCount' => $counts->completed_count,
    ]);
  }

  public function create()
  {
    return view('tasks.create', ['tags' => Tag::all()]);
  }

  public function store(StoreTaskRequest $request)
  {
    $this->taskService->createTask($request->validated());
    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
  }

  public function show(Task $task)
  {
    $task->load('tags');
    return view('tasks.show', compact('task'));
  }

  public function edit(Task $task)
  {
    $task->load('tags:id,name');
    return view('tasks.edit', compact('task'));
  }

  public function update(UpdateTaskRequest $request, Task $task)
  {
    $this->taskService->updateTask($task, $request->validated());
    return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
  }

  public function destroy(Task $task)
  {
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
  }

  public function toggleComplete(Task $task)
  {
    $task = $this->taskService->toggleComplete($task);

    return response()->json([
      'success' => true,
      'completed' => $task->completed,
      'message' => 'Task status updated',
    ]);
  }
}
