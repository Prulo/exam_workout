<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExerciseController extends Controller
{
    /**
     * Display a listing of exercises.
     */
    
    public function index(Request $request)
    {
        $exercises = Exercise::all();
    
        // If it's an API request, return JSON instead of an Inertia response
        if ($request->wantsJson()) {
            return response()->json($exercises);
        }
    
        // Otherwise, return an Inertia response
        return Inertia::render('Exercises', [
            'exercises' => $exercises,
        ]);

    }

    public function workout(Request $request)
{
    $exercises = Exercise::all();

    // If it's an API request, return JSON instead of an Inertia response
    if ($request->wantsJson()) {
        return response()->json($exercises);
    }

    // Otherwise, return an Inertia response for the "WorkoutPage"
    return Inertia::render('Workouts', [
        'exercises' => $exercises,
    ]);
}
    /**
     * Store a newly created exercise.
     */
    public function store(Request $request)
    {
        $exercise = Exercise::create($request->validate([
            'name' => 'required|string',
            'muscle_group' => 'nullable|string',
            'description' => 'nullable|string',
        ]));
    
        return redirect()->route('exercises.index')->with('success', 'Exercise added successfully!');
    }

    /**
     * Display the specified exercise.
     */
    public function show(Exercise $exercise)
    {
        return response()->json($exercise, 200);
    }

    /**
     * Update an existing exercise.
     */
    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $exercise->update($validated);

        return response()->json($exercise, 200);
    }

    /**
     * Remove the specified exercise.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
    
        return redirect()->route('exercises.index')->with('success', 'Exercise deleted successfully.');
    }
}

