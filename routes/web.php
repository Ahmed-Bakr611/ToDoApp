<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class, 'register']);

    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('tasks.index');
    });

    Route::resource('tags', TagController::class)->only([
        'index',
        'edit',
        'update',
        'destroy'
    ]);

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
    Route::resource('tasks', TaskController::class);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::resource('tasks', TaskController::class);
});
// Fallback for unauthenticated users trying to access tasks
// Route::get('/tasks', function () {
//     return redirect()->route('login');
// })->middleware('guest');

// Route::get('/', function () {
//     return redirect()->route('tasks.index');
// });
// Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
// Route::resource('tasks', TaskController::class);
// Route::middleware('guest')->group(function () {
//     Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [UserController::class, 'login']);

//     Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
//     Route::post('/register', [UserController::class, 'register']);
// });

// Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

// Route::middleware('auth')->group(function () {

//     Route::get('/', function () {
//         return redirect()->route('tasks.index');
//     });

//     Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])
//         ->name('tasks.toggle');

//     Route::resource('tasks', TaskController::class);
// });

// Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
// Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
// Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
// Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
// Route::put('/tasks/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
// Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
// Route::fallback(function () {
//     return 'NO Match';
// });
