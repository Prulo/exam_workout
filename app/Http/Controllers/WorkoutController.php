<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Workouts');
    }

    public function calendar()
    {
        $workouts = Workout::where('user_id', Auth::id())
            ->orderBy('date', 'asc')
            ->get();
    
        return Inertia::render('Calendar', [
            'workouts' => $workouts,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'exercises' => 'required|array',
            'exercises.*.name' => 'required|string',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        Workout::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'exercises' => $request->exercises,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard');
    }
}
