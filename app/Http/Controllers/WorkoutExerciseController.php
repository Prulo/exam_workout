<?php
namespace App\Http\Controllers;

use App\Models\WorkoutExercise;
use Illuminate\Http\Request;

class WorkoutExerciseController extends Controller
{
    
    public function updateWeight(Request $request, WorkoutExercise $exercise)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0'
        ]);
    
        $exercise->update(['weight' => $request->weight]);
    
        return back()->with('success', 'Weight updated!');
    }
}

