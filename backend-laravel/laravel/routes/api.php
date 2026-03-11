<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;

use App\Http\Controllers\DashboardController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('projects')->group(function () {
    Route::post('/', [ProjectController::class, 'store']);         // Create project
    Route::get('/', [ProjectController::class, 'index']);          // List projects
    Route::post('{id}/tasks', [TaskController::class, 'store']);   // Create task for project
});

Route::patch('tasks/{id}', [TaskController::class, 'update']);    // Update task status

Route::get('/dashboard-tasks', [DashboardController::class, 'index']);
