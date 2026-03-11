<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class TopTasksTest extends TestCase
{
    
    public function it_prioritizes_overdue_tasks_first()
    {
        $tasks = [
            ['id'=>1,'project_id'=>1,'priority'=>3,'status'=>'todo','created_at'=>Carbon::now()->subDays(4)],
            ['id'=>2,'project_id'=>1,'priority'=>5,'status'=>'todo','created_at'=>Carbon::now()],
        ];

        $top = topTasks($tasks, 2);

        $this->assertEquals(1, $top[0]['id']); // Overdue first
        $this->assertEquals(2, $top[1]['id']); // Non-overdue next
    }

    
    public function it_sorts_by_priority_when_not_overdue()
    {
        $tasks = [
            ['id'=>1,'project_id'=>1,'priority'=>2,'status'=>'todo','created_at'=>Carbon::now()],
            ['id'=>2,'project_id'=>1,'priority'=>5,'status'=>'in_progress','created_at'=>Carbon::now()],
        ];

        $top = topTasks($tasks, 2);

        $this->assertEquals(2, $top[0]['id']); // Highest priority first
        $this->assertEquals(1, $top[1]['id']);
    }

   
    public function it_tie_breaks_by_project_importance()
    {
        $tasks = [
            ['id'=>1,'project_id'=>1,'priority'=>3,'status'=>'todo','created_at'=>Carbon::now()],
            ['id'=>2,'project_id'=>1,'priority'=>3,'status'=>'todo','created_at'=>Carbon::now()],
            ['id'=>3,'project_id'=>2,'priority'=>3,'status'=>'todo','created_at'=>Carbon::now()],
        ];

        $top = topTasks($tasks, 3);

        // Project 1 has 2 tasks, project 2 has 1 → project 1 tasks come first
        $this->assertEquals(1, $top[0]['id']);
        $this->assertEquals(2, $top[1]['id']);
        $this->assertEquals(3, $top[2]['id']);
    }

   
    public function it_respects_top_n_limit()
    {
        $tasks = [
            ['id'=>1,'project_id'=>1,'priority'=>5,'status'=>'todo','created_at'=>Carbon::now()->subDays(4)],
            ['id'=>2,'project_id'=>1,'priority'=>4,'status'=>'todo','created_at'=>Carbon::now()],
            ['id'=>3,'project_id'=>2,'priority'=>3,'status'=>'todo','created_at'=>Carbon::now()],
        ];

        $top = topTasks($tasks, 2);

        $this->assertCount(2, $top); // Only top 2 tasks returned
        $this->assertEquals(1, $top[0]['id']); // Overdue first
    }

    
    public function it_handles_mixed_overdue_priority_and_project_importance()
    {
        $tasks = [
            ['id'=>1,'project_id'=>1,'priority'=>2,'status'=>'todo','created_at'=>Carbon::now()->subDays(5)], // overdue
            ['id'=>2,'project_id'=>1,'priority'=>5,'status'=>'todo','created_at'=>Carbon::now()], // today
            ['id'=>3,'project_id'=>2,'priority'=>5,'status'=>'todo','created_at'=>Carbon::now()], // today, different project
            ['id'=>4,'project_id'=>2,'priority'=>3,'status'=>'in_progress','created_at'=>Carbon::now()],
        ];

        $top = topTasks($tasks, 4);

        // Check overdue first
        $this->assertEquals(1, $top[0]['id']);

        // Today tasks: project importance tie-break → project 1 has 2 tasks, project 2 has 2 tasks → then by id order
        $this->assertEquals(2, $top[1]['id']);
        $this->assertEquals(3, $top[2]['id']);

        // In-progress low priority
        $this->assertEquals(4, $top[3]['id']);
    }
}
