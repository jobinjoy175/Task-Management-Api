<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class DashboardController extends Controller
{
   
     public function index(Request $request)
    {
        // Eager load project and user
        $tasks = Task::with(['project', 'user'])->get()->map(function ($task) {
            $project = $task->project;

            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'priority' => $task->priority,
                'project_id' => $task->project_id,
                'project_name' => $project?->name,
                'project_total_tasks' => $project?->tasks()->count() ?? 0,
                'assigned_to' => $task->assigned_to,
                'assigned_user_name' => $task->user?->name ?? 'Unassigned',
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
            ];
        });

        return response()->json($tasks);
    }
}
