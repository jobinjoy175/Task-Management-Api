<?php
// app/Helpers/tasks.php

use Carbon\Carbon;

if (!function_exists('topTasks')) {
    function topTasks(array $tasks, int $n): array
    {
        $now = Carbon::now();
        $projectImportance = [];
        foreach ($tasks as $task) {
            $projectId = $task['project_id'];
            $projectImportance[$projectId] = ($projectImportance[$projectId] ?? 0) + 1;
        }

        usort($tasks, function ($a, $b) use ($projectImportance, $now) {
            $aOverdue = $a['status'] === 'todo' && Carbon::parse($a['created_at'])->addDays(3)->lt($now);
            $bOverdue = $b['status'] === 'todo' && Carbon::parse($b['created_at'])->addDays(3)->lt($now);

            if ($aOverdue && !$bOverdue) return -1;
            if (!$aOverdue && $bOverdue) return 1;

            if ($a['priority'] !== $b['priority']) {
                return $b['priority'] <=> $a['priority'];
            }

            $aImp = $projectImportance[$a['project_id']] ?? 0;
            $bImp = $projectImportance[$b['project_id']] ?? 0;
            return $bImp <=> $aImp;
        });

        return array_slice($tasks, 0, $n);
    }
}
