<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

Route::get('/h', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes (registration/login)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [UserController::class, 'logout']);

    // Tasks
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);
    Route::apiResource('tasks', TaskController::class);
});
