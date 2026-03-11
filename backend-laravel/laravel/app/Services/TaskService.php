<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class TaskService
{
    /**
     * Get next tasks for a user sorted by:
     * 1. Overdue tasks first (todo + older than 3 days)
     * 2. Highest priority
     * 3. Today tasks sorted by project importance (more tasks = more important)
     */
    public function getNextTasks(User $user)
    {
        $tasks = $user->tasks()->with('project')->get();
        $today = Carbon::today();

        // Project importance: number of tasks per project
        $projectImportance = $tasks->groupBy('project_id')->map->count();

        $sorted = $tasks->sort(function ($a, $b) use ($projectImportance, $today) {
            $now = Carbon::now();

            // Overdue check
            $aOverdue = $a->status === 'todo' && $a->created_at->addDays(3)->lt($now);
            $bOverdue = $b->status === 'todo' && $b->created_at->addDays(3)->lt($now);
            if ($aOverdue && !$bOverdue) return -1;
            if (!$aOverdue && $bOverdue) return 1;

            // Priority
            if ($a->priority !== $b->priority) {
                return $b->priority <=> $a->priority;
            }

            // Today tasks: sort by project importance
            $aToday = $a->created_at->toDateString() === $today->toDateString();
            $bToday = $b->created_at->toDateString() === $today->toDateString();

            if ($aToday && $bToday) {
                $aImp = $projectImportance[$a->project_id] ?? 0;
                $bImp = $projectImportance[$b->project_id] ?? 0;
                return $bImp <=> $aImp;
            }

            // Ensure today tasks come before other non-overdue tasks
            if ($aToday && !$bToday) return -1;
            if (!$aToday && $bToday) return 1;

            return 0;
        });

        return $sorted->values();
    }
}
