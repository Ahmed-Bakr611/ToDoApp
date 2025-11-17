<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class TaskService
{
    private const PAGE_SIZE = 5;

    public function getTasksWithCounts(string $tab)
    {
        $query = Auth::user()->tasks()
            ->when($tab === 'completed', fn($q) => $q->where('completed', true))
            ->when($tab !== 'completed', fn($q) => $q->where('completed', false))
            ->with('tags:id,name')
            ->latest();

        $tasks = $query->paginate(self::PAGE_SIZE)->appends(['tab' => $tab]);

        $counts = Auth::user()->tasks()
            ->selectRaw('SUM(CASE WHEN completed = 0 THEN 1 ELSE 0 END) as active_count,
                         SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed_count')
            ->first();

        return compact('tasks', 'counts');
    }

    public function createTask(array $data): Task
    {
        $task = Auth::user()->tasks()->create(array_merge($data, [
            'completed' => false,
        ]));

        $task->tags()->sync($data['tags'] ?? []);

        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        $task->tags()->sync($data['tags'] ?? []);

        return $task;
    }

    public function toggleComplete(Task $task): Task
    {
        $task->update(['completed' => !$task->completed]);
        return $task;
    }
}
