<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutExerciseController;
use App\Models\Exercise;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/chirps/generate', [ChirpController::class, 'generate'])->name('chirps.generate');

Route::resource('chirps', ChirpController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware(['auth', 'verified']);

Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
Route::get('/workout', [ExerciseController::class, 'workout'])->name('workouts.index');; // For the Workout page
Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');
Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');

Route::get('/calendar', [WorkoutController::class, 'calendar'])->name('calendar.index');
Route::post('/workouts/{workout}/update-weight', [WorkoutController::class, 'updateWeight']);

Route::post('/workout-exercises/{exercise}/update-weight', [WorkoutExerciseController::class, 'updateWeight']);

require __DIR__.'/auth.php';
