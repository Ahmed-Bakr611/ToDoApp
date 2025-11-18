<?php

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Tasks\TaskList;
use App\Livewire\Tasks\TaskForm;
use App\Livewire\Tasks\TaskDetail;
use App\Livewire\Tags\TagList;
use App\Livewire\Tags\TagForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// Root route
Route::get('/', function () {
  return redirect()->route(Auth::check() ? 'tasks.index' : 'login');
});

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
  Route::get('/register', Register::class)->name('register');
  Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {

  Route::post('/logout', [UserController::class, 'logout'])->name('logout');

  // Task routes with Livewire
  Route::get('/tasks', TaskList::class)->name('tasks.index');
  Route::get('/tasks/create', TaskForm::class)->name('tasks.create');
  Route::get('/tasks/{task}', TaskDetail::class)->name('tasks.show');
  Route::get('/tasks/{task}/edit', TaskForm::class)->name('tasks.edit');

  // Tag routes with Livewire
  Route::get('/tags', TagList::class)->name('tags.index');
  Route::get('/tags/create', TagForm::class)->name('tags.create');
  Route::get('/tags/{tag}/edit', TagForm::class)->name('tags.edit');
  Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

  // Keep search endpoint for task form autocomplete
  Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
});
