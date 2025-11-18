<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Tasks\TaskList;
use App\Livewire\Tasks\TaskForm;
use App\Livewire\Tasks\TaskDetail;
use Illuminate\Support\Facades\Route;



// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
  Route::get('/register', Register::class)->name('register');
  Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {
  Route::get('/', fn() => redirect()->route('tasks.index'));

  Route::post('/logout', [UserController::class, 'logout'])->name('logout');

  // Task routes with Livewire
  Route::get('/tasks', TaskList::class)->name('tasks.index');
  Route::get('/tasks/create', TaskForm::class)->name('tasks.create');
  Route::post('/tasks', [UserController::class, 'logout']); // Handled by Livewire
  Route::get('/tasks/{task}', TaskDetail::class)->name('tasks.show');
  Route::get('/tasks/{task}/edit', TaskForm::class)->name('tasks.edit');

  // Tag routes (keep API endpoints for task form autocomplete)
  Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
  Route::resource('tags', TagController::class)->only(['index', 'edit', 'update', 'destroy']);
});
