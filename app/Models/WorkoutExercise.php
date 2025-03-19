<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = ['workout_id', 'exercise_name', 'weight', 'reps', 'sets', 'total_volume'];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}