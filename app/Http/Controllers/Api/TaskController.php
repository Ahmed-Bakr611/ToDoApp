<?php

namespace App\Http\Controllers\Api;

use App\Dtos\TaskDTO;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {
        $this->middleware('auth:sanctum'); // protect all routes
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'active');
        ['tasks' => $tasks, 'counts' => $counts] = $this->taskService->getTasksWithCounts($tab);

        $lightTasks = TaskDTO::collection($tasks->getCollection());

        return response()->json(
            new ApiResponse(
                data: [
                    'tasks' => [
                        'items' => $lightTasks,
                        'pagination' => [
                            'total'        => $tasks->total(),
                            'per_page'     => $tasks->perPage(),
                            'current_page' => $tasks->currentPage(),
                            'has_more'     => $tasks->hasMorePages(),
                        ],
                    ],
                    'tab' => $tab,
                    'counts' => [
                        'active'    => $counts->active_count,
                        'completed' => $counts->completed_count,
                    ],
                ],
                message: 'Tasks retrieved successfully'
            )
        );
    }


    /**
     * Store a new task
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json(
            new ApiResponse(
                data: $task,
                message: 'Task created successfully'
            ),
            201
        );
    }

    /**
     * Show single task
     */
    public function show(Task $task)
    {
        $task->load('tags');

        return response()->json(
            new ApiResponse(
                data: $task,
                message: 'Task retrieved successfully'
            )
        );
    }

    /**
     * Update a task
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json(
            new ApiResponse(
                data: $task,
                message: 'Task updated successfully'
            )
        );
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(
            new ApiResponse(
                data: null,
                message: 'Task deleted successfully'
            )
        );
    }

    /**
     * Toggle task completion
     */
    public function toggleComplete(Task $task)
    {
        $task = $this->taskService->toggleComplete($task);

        return response()->json(
            new ApiResponse(
                data: ['completed' => $task->completed],
                message: 'Task status updated'
            )
        );
    }
}
