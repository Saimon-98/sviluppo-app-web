<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Rotte API per le attività (in memoria)
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

// Endpoint di debug per visualizzare le attività attualmente in memoria
Route::get('/debug/tasks', function () {
    return TaskController::debugTasks();
});