<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    date: "",
    exercises: [],
    notes: "",
});

const submit = () => {
    form.post(route("workouts.store"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <div>
        <h1 class="text-2xl font-bold">Log Workout</h1>
        <form @submit.prevent="submit">
            <label>Date:</label>
            <input type="date" v-model="form.date" class="border p-2" />

            <label>Notes:</label>
            <textarea v-model="form.notes" class="border p-2"></textarea>

            <button
                type="submit"
                class="mt-4 bg-blue-500 text-white p-2 rounded"
            >
                Save Workout
            </button>
        </form>
    </div>
</template>
