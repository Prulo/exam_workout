<script setup>
import { ref } from "vue";

const props = defineProps({
    workouts: Array,
});

// Organize workouts by date
const calendar = ref({});
props.workouts.forEach((workout) => {
    if (!calendar.value[workout.date]) {
        calendar.value[workout.date] = {
            workouts: [],
            isVisible: false,
        };
    }
    calendar.value[workout.date].workouts.push(workout);
});

// Function to toggle visibility of workout details
const toggleDetails = (date) => {
    calendar.value[date].isVisible = !calendar.value[date].isVisible;
};

// Function to handle the input of weight for each set
const updateSetWeight = (workoutId, exerciseName, setIndex, weight) => {
    const workout = props.workouts.find((w) => w.id === workoutId);
    const exercise = workout.exercises.find((ex) => ex.name === exerciseName);
    exercise.sets[setIndex].weight = weight; // Update the weight for the specific set
};
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Workout Calendar</h1>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Date</th>
                    <th class="border p-2">Workout</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(workouts, date) in calendar" :key="date">
                    <td class="border p-2 font-bold flex items-center">
                        {{ date }}
                        <button
                            @click="toggleDetails(date)"
                            class="text-blue-500 ml-2"
                        >
                            {{
                                workouts.isVisible
                                    ? "Hide Details"
                                    : "Show Details"
                            }}
                        </button>
                    </td>
                    <td class="border p-2">
                        <ul v-show="workouts.isVisible">
                            <li
                                v-for="workout in workouts.workouts"
                                :key="workout.id"
                            >
                                <p class="font-semibold">
                                    Note: {{ workout.notes || "No notes" }}
                                </p>
                                <ul>
                                    <li
                                        v-for="(
                                            exercise, exerciseIndex
                                        ) in workout.exercises"
                                        :key="exercise.name"
                                    >
                                        <p>
                                            {{ exercise.name }} -
                                            {{ exercise.sets.length }} sets
                                        </p>
                                        <ul>
                                            <li
                                                v-for="(
                                                    set, setIndex
                                                ) in exercise.sets"
                                                :key="setIndex"
                                            >
                                                Set {{ setIndex + 1 }}:
                                                {{ set.reps }} reps
                                                <input
                                                    type="number"
                                                    v-model.number="set.weight"
                                                    placeholder="Weight (kg)"
                                                    class="ml-2 p-1 border rounded"
                                                    @input="
                                                        updateSetWeight(
                                                            workout.id,
                                                            exercise.name,
                                                            setIndex,
                                                            set.weight
                                                        )
                                                    "
                                                />
                                                kg
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
