<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {

            Task::create([
                'title' => "Task $i",
                'description' => "Task description $i",
                'status' => ['todo','in_progress','done'][rand(0,2)],
                'project_id' => rand(1,3),
                'assigned_to' => rand(1,3),
                'priority' => rand(1,5),
                'created_at' => Carbon::now()->subDays(rand(0,5)),
                'updated_at' => now()
            ]);
        }
    }
}
