<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // e.g., "Buy groceries today"
            'description' => $this->faker->sentence(),
            'long_description' => $this->faker->paragraph(),
            'completed' => $this->faker->boolean(30), // 30% chance completed
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
