<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class ProjectController extends Controller
{
    
    public function index()
    {
        $projects = Project::with('tasks')->get()->map(function ($project) {
            $totalTasks = $project->tasks->count();
            $openTasks = $project->tasks->whereIn('status', ['todo', 'in_progress'])->count();
            $completedTasks = $project->tasks->where('status', 'done')->count();
            $highestPriorityTask = $project->tasks->sortByDesc('priority')->first();

            return [
                'id' => $project->id,
                'name' => $project->name,
                'owner_id' => $project->owner_id,
                'total_tasks' => $totalTasks,
                'open_tasks' => $openTasks,
                'completed_tasks' => $completedTasks,
                'highest_priority_task' => $highestPriorityTask ? $highestPriorityTask->title : null,
            ];
        });

        return response()->json($projects);
    }

    // Create a project
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($request->only(['name', 'owner_id']));

        return response()->json($project, 201);
    }
}
