<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TaskService;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }

   
    public function it_sorts_tasks_by_overdue_first()
    {
        $user = User::factory()->create();

        $project = Project::factory()->create();

       
        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 3,
            'created_at' => now()->subDays(4),
        ]);

        
        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 5,
            'created_at' => now(),
        ]);

        $tasks = $this->service->getNextTasks($user);

        $this->assertTrue($tasks->first()->created_at->lt(now()->subDays(3)));
    }

   
    public function it_sorts_tasks_by_priority_when_not_overdue()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 2,
            'created_at' => now(),
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 5,
            'created_at' => now(),
        ]);

        $tasks = $this->service->getNextTasks($user);

        $this->assertEquals(5, $tasks->first()->priority);
    }

    
    public function it_sorts_today_tasks_by_project_importance()
    {
        $user = User::factory()->create();

        $projA = Project::factory()->create();
        $projB = Project::factory()->create();

        // projA has 2 tasks today
        Task::factory()->count(2)->create([
            'project_id' => $projA->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 3,
            'created_at' => now(),
        ]);

        // projB has 1 task today
        Task::factory()->create([
            'project_id' => $projB->id,
            'assigned_to' => $user->id,
            'status' => 'todo',
            'priority' => 3,
            'created_at' => now(),
        ]);

        $tasks = $this->service->getNextTasks($user);
        $this->assertEquals($projA->id, $tasks->first()->project_id);
    }
}
