<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Eloquent\EloquentGenericCrudRepository;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
  private EloquentGenericCrudRepository $repo;

  // Fixed page size constant
  private const PAGE_SIZE = 5;

  public function __construct()
  {
    $this->repo = new EloquentGenericCrudRepository(new Task());
  }

  /**
   * Display a listing of the user's tasks.
   */
  public function index(Request $request)
  {
    // Get the tab parameter (default to 'active')
    $tab = $request->get('tab', 'active');

    // Base query for user's tasks
    $query = Auth::user()->tasks()->orderBy('created_at', 'desc');

    // Filter based on tab
    if ($tab === 'completed') {
      $query->where('completed', true);
    } else {
      $query->where('completed', false);
    }

    // Paginate with the tab parameter
    $tasks = $query->paginate(self::PAGE_SIZE)->appends(['tab' => $tab]);

    // Get counts for both tabs
    $activeCount = Auth::user()->tasks()->where('completed', false)->count();
    $completedCount = Auth::user()->tasks()->where('completed', true)->count();

    return view('tasks.index', compact('tasks', 'tab', 'activeCount', 'completedCount'));
  }

  /**
   * Show the form for creating a new task.
   */
  public function create()
  {
    $tags = Tag::all();
    return view('tasks.create', compact('tags'));
  }

  /**
   * Store a newly created task in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    if (isset($request)) {
      $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|string',
        'priority' => 'nullable|string',
        'due_date' => 'nullable|date',
        'tags' => 'nullable|array',           // tags can be empty
        'tags.*' => 'exists:tags,id',         // each tag must exist in the tags table
      ]);
    }

    // Add the authenticated user's ID to the task
    $validated['user_id'] = Auth::id();
    $validated['completed'] = false;

    $task = $this->repo->create($validated);
    // Attach tags if any
    if (!empty($validated['tags'])) {
      $task->tags()->attach($validated['tags']);
    }

    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
  }

  /**
   * Display the specified task.
   *
   * @param  \App\Models\Task  $task
   * @return \Illuminate\View\View
   */
  public function show(Task $task)
  {
    if (!isset($task)) {
      return abort(404);
    }
    // Manual authorization check
    if (isset($task) && $task->user_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    // Load the tags relationship
    $task->load('tags');

    return view('tasks.show', compact('task'));
  }

  /**
   * Show the form for editing the specified task.
   *
   * @param  \App\Models\Task  $task
   * @return \Illuminate\View\View
   */
  public function edit(Task $task)
  {
    // Manual authorization check(
    if (!isset($task)) {
      abort(404, '');
    }
    if ($task->user_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    $tags = Tag::all(); // all available tags
    $selectedTags = $task->tags->pluck('id')->toArray(); // selected tags for this task

    return view('tasks.edit', compact('task', 'tags', 'selectedTags'));
  }

  /**
   * Update the specified task in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Task  $task
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Task $task)
  {
    // Manual authorization check
    if (isset($task) && $task->user_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    if (isset($request)) {
      $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|string',
        'priority' => 'nullable|string',
        'due_date' => 'nullable|date',
        'tags' => 'nullable|array',
        'tags.*' => 'exists:tags,id',
      ]);
    }
    if (isset($task)) {
      $this->repo->update($task->id, $validated);

      // Sync tags
      if (isset($validated['tags'])) {
        $task->tags()->sync($validated['tags']);
      } else {
        $task->tags()->sync([]); // remove all tags if none selected
      }
    }

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
  }

  /**
   * Remove the specified task from storage.
   *
   * @param  \App\Models\Task  $task
   * @return \Illuminate\Http\RedirectResponse
   */
  public function destroy(Task $task)
  {
    if (!isset($task)) {
      abort(0, '');
    }
    // Manual authorization check
    if ($task->user_id !== Auth::id()) {
      abort(403, 'Unauthorized action.');
    }

    $this->repo->delete($task->id);

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
  }

  /**
   * Toggle the completion status of a task.
   *
   * @param  \App\Models\Task  $task
   * @return \Illuminate\Http\JsonResponse
   */
  public function toggleComplete(Task $task)
  {
    if (!isset($task)) {
      return response()->json(['success' => false, 'message' => 'Task not found'], 404);
    }

    // Authorization check
    if ($task->user_id !== Auth::id()) {
      return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    //comment
    $task->completed = !$task->completed;
    $task->save();

    Log::info('Task toggled', [
      'task_id' => $task->id,
      'completed' => $task->completed,
      'user_id' => Auth::id()
    ]);

    return response()->json([
      'success' => true,
      'completed' => $task->completed,
      'message' => 'Task status updated',
    ]);
  }
}
