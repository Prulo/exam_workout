<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WorkoutExercise;

class WorkoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Workouts');
    }

    public function calendar()
    {
        // Fetch workouts for the authenticated user, including the exercises
        $workouts = Workout::where('user_id', Auth::id())
            ->with('exercises') // Eager load exercises
            ->orderBy('date', 'asc')
            ->get();
    
        return Inertia::render('Calendar', [
            'workouts' => $workouts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'exercises' => 'required|json',
            'notes' => 'nullable|string',
        ]);
    
        $exercises = json_decode($validated['exercises'], true); // Decode JSON
        unset($validated['exercises']); // Remove exercises before saving
    
        // Create the workout
        $workout = Workout::create($validated);
    
        // Insert exercises into `workout_exercises`
        foreach ($exercises as $exercise) {
            WorkoutExercise::create([
                'workout_id' => $workout->id,
                'exercise_name' => $exercise['name'],
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
            ]);
        }
    
        return redirect()->route('workouts.index')->with('success', 'Workout saved!');
    }

    public function destroy(Workout $exercise)
{
    // Delete the workout (exercise)
    $exercise->delete();
    
    // Redirect back with a success message
    return redirect()->route('workouts.index')->with('success', 'Exercise deleted successfully.');
}
}
