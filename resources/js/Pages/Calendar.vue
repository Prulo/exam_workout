<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Layout from "@/Layouts/AuthenticatedLayout.vue";

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
</script>

<template>
    <Layout>
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
                                {{ workouts.isVisible ? "Hide" : "Show" }}
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
                                            v-for="exercise in workout.exercises"
                                            :key="exercise.id"
                                        >
                                            {{ exercise.exercise_name }} -
                                            {{ exercise.sets }} sets x
                                            {{ exercise.reps }} reps
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Layout>
</template>
