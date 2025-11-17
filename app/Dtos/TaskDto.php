<?php

namespace App\Dtos;

use App\Models\Task;

class TaskDTO
{
  public function __construct(
    public int $id,
    public string $title,
    public bool $completed,
    public array $tags
  ) {}

  public static function fromModel(Task $task): self
  {
    // Only include tags' names
    return new self(
      id: $task->id,
      title: $task->title,
      completed: $task->completed,
      tags: $task->tags->pluck('name')->toArray()
    );
  }

  public static function collection($tasks)
  {
    return $tasks->map(fn($task) => self::fromModel($task));
  }
}
