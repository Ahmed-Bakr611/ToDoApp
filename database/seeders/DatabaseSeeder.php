<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure user with ID = 1 exists
        $user = User::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Ahmed Bakr',
                'email' => 'ahmedbakr2904@example.com',
                'password' => bcrypt('Bakr@1234'),
            ]
        );

        // Create 50 tags
        $tags = Tag::factory()->count(50)->create();
        $tagIds = $tags->pluck('id')->all();

        // Disable query log for performance
        DB::disableQueryLog();

        // Create 10,000 tasks for the user
        $tasks = Task::factory()->count(10000)->create([
            'user_id' => $user->id,
        ]);

        // Prepare pivot data in memory
        $pivotData = [];

        foreach ($tasks as $task) {
            // Choose 10 random tag IDs
            $randomTags = collect($tagIds)->random(10);
            foreach ($randomTags as $tagId) {
                $pivotData[] = [
                    'task_id' => $task->id,
                    'tag_id'  => $tagId,
                ];
            }
        }

        // Insert all pivot relations in bulk
        // Split into chunks of 10,000 to avoid hitting DB limits
        foreach (array_chunk($pivotData, 10000) as $chunk) {
            DB::table('task_tag')->insert($chunk);
        }

        $count = count($pivotData);
        $this->command->info("✅ Seeded {$tasks->count()} tasks, {$tags->count()} tags, and {$count} task-tag relations.");
    }
}
