<?php

use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/lists')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('lists', [TaskListController::class, 'index'])
        ->name('task-lists.index');

    Route::post('lists', [TaskListController::class, 'store'])
        ->name('task-lists.store');

    Route::get('lists/{list}', [TaskListController::class, 'show'])
        ->whereNumber('list')
        ->name('task-lists.show');

    Route::post('lists/{list}/tasks', [TaskController::class, 'store'])
        ->whereNumber('list')
        ->name('tasks.store');

    Route::delete('lists/{list}/tasks/{task}', [TaskController::class, 'destroy'])
        ->whereNumber('list')
        ->whereNumber('task')
        ->name('tasks.destroy');

    Route::patch('lists/{list}/tasks/{task}/move', [TaskController::class, 'move'])
        ->whereNumber('list')
        ->whereNumber('task')
        ->name('tasks.move');

    Route::patch('lists/{list}/tasks/{task}/completion',[TaskController::class, 'updateCompletion'])
        ->whereNumber('list')
        ->whereNumber('task')
        ->name('tasks.completion');
});

require __DIR__.'/settings.php';