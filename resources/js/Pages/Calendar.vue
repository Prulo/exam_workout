<script setup>
import { ref } from "vue";
import Layout from "@/Layouts/AuthenticatedLayout.vue";
import { usePage, router } from "@inertiajs/vue3";

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

const updateWeight = (exercise) => {
    if (exercise.newWeight === undefined || exercise.newWeight === "") {
        alert("Please enter a weight.");
        return;
    }

    router.post(
        `/workout-exercises/${exercise.id}/update-weight`,
        {
            weight: exercise.newWeight,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                exercise.weight = exercise.newWeight; // Update locally after success
            },
        }
    );
};

const deleteWorkout = (workoutId, workoutDate) => {
    if (confirm("Are you sure you want to delete this workout?")) {
        router.delete(`/workouts/${workoutId}`, {
            preserveScroll: true,
            onSuccess: () => {
                // Remove the deleted workout from the calendar data
                calendar.value[workoutDate].workouts = calendar.value[
                    workoutDate
                ].workouts.filter((workout) => workout.id !== workoutId);
            },
        });
    }
};

const calculateTotalWeight = (workout) => {
    return workout.exercises.reduce((total, exercise) => {
        if (exercise.weight) {
            return total + exercise.weight * exercise.reps * exercise.sets;
        }
        return total;
    }, 0);
};
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
</script>

<template>
    <Head title="Calendar" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Workout Calendar
            </h2>
        </template>
        <div class="max-w-5xl mx-auto p-6">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Date</th>
                        <th class="border p-2">Workout</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(workouts, date) in calendar" :key="date">
                        <td class="border p-2 font-bold">
                            <div class="flex items-center">
                                {{ date }}
                                <button
                                    @click="toggleDetails(date)"
                                    class="text-blue-500 ml-2"
                                >
                                    {{ workouts.isVisible ? "Hide" : "Show" }}
                                </button>
                            </div>
                            <p class="font-semibold">
                                Workout:
                                {{
                                    workouts.workouts.length > 0
                                        ? workouts.workouts[0].notes ||
                                          "No notes"
                                        : "No workout"
                                }}
                            </p>
                        </td>
                        <td class="border p-2">
                            <ul v-show="workouts.isVisible">
                                <li
                                    v-for="workout in workouts.workouts"
                                    :key="workout.id"
                                    class="mb-4"
                                >
                                    <ul>
                                        <li
                                            v-for="exercise in workout.exercises"
                                            :key="exercise.id"
                                        >
                                            {{ exercise.exercise_name }} -
                                            {{ exercise.sets }} sets x
                                            {{ exercise.reps }} reps
                                            <span v-if="exercise.weight">
                                                -@-
                                                {{ exercise.weight }} kg
                                            </span>

                                            <input
                                                v-model="exercise.newWeight"
                                                type="number"
                                                class="ml-2 border p-1 w-16"
                                                placeholder="kg"
                                            />

                                            <button
                                                @click="updateWeight(exercise)"
                                                class="ml-2 px-2 py-1 bg-blue-500 text-white rounded"
                                            >
                                                Save
                                            </button>
                                        </li>
                                    </ul>
                                    <p class="font-bold">
                                        Total Weight:
                                        {{ calculateTotalWeight(workout) }} kg
                                    </p>
                                    <button
                                        @click="deleteWorkout(workout.id, date)"
                                        class="mt-2 px-2 py-1 bg-red-500 text-white rounded"
                                    >
                                        Delete Workout
                                    </button>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
