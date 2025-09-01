<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
use App\Http\Controllers\TaskController;

Route::get('/tasks', [TaskController::class, 'index'])->middleware(['auth'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->middleware(['auth'])->name('tasks.store');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware(['auth'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->middleware(['auth'])->name('tasks.destroy');

require __DIR__.'/auth.php';