<?php
namespace App\Http\Controllers;

use App\Models\WorkoutExercise;
use Illuminate\Http\Request;

class WorkoutExerciseController extends Controller
{
    public function updateWeight(Request $request, WorkoutExercise $workout)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'exercise_name' => 'required|string',
            'weight' => 'required|numeric',
        ]);
    
        // Find the exercise within the workout
        $exercise = $workout->exercises()->where('exercise_name', $validated['exercise_name'])->first();
    
        if ($exercise) {
            $exercise->weight = $validated['weight'];
            $exercise->save();
            return response()->json(['message' => 'Weight updated successfully.']);
        }
    
        return response()->json(['message' => 'Exercise not found.'], 404);
    }
    
}

