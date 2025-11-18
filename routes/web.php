<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;



// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
  Route::get('/register', Register::class)->name('register');
  Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
  Route::get('/', fn() => redirect()->route('tasks.index'));

  Route::post('/logout', [UserController::class, 'logout'])->name('logout');

  Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])
    ->name('tasks.toggle');

  Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
  Route::get('/tags/fetch', [TagController::class, 'fetch'])->name('tags.fetch');

  Route::resource('tasks', TaskController::class);

  Route::resource('tags', TagController::class)->only(['index', 'edit', 'update', 'destroy']);
});
