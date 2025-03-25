<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained()->onDelete('cascade'); // Links to workouts table
            $table->string('exercise_name');
            $table->integer('weight')->nullable(); // Nullable for bodyweight exercises
            $table->integer('reps');
            $table->integer('sets');
            $table->integer('total_volume')->nullable(); // Optional: Store volume if needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};

