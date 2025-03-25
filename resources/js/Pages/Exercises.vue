<template>
    <Head title="Exercise" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Exercise
            </h2>
        </template>
        <Exercise />
        <div class="max-w-5xl mx-auto p-6">
            <h1 class="text-2xl font-semibold mb-6">All Exercises</h1>

            <ul v-if="exercises.length" class="space-y-4 mt-6">
                <li
                    v-for="exercise in exercises"
                    :key="exercise.id"
                    class="p-4 border rounded flex justify-between items-center"
                >
                    <div>
                        <h3 class="font-semibold">{{ exercise.name }}</h3>
                        <p>{{ exercise.muscle_group }}</p>
                        <p>{{ exercise.description }}</p>
                    </div>
                    <button
                        @click="deleteExercise(exercise.id)"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
                    >
                        Delete
                    </button>
                </li>
            </ul>

            <div v-else>
                <p>No exercises available.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import Exercise from "@/Components/Exercise.vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// Define a ref to store the list of exercises and the selected exercise name
const exercises = ref([]);
const selectedExercise = ref("");

// Fetch exercises data
const fetchExercises = () => {
    const page = usePage();
    exercises.value = page.props.exercises || [];
};

// Delete an exercise
const deleteExercise = (id) => {
    if (!confirm("Are you sure you want to delete this exercise?")) {
        return;
    }

    router.delete(`/exercises/${id}`, {
        onSuccess: () => {
            exercises.value = exercises.value.filter((e) => e.id !== id); // Remove from UI
        },
        onError: (error) => {
            console.error("Error deleting exercise:", error);
        },
    });
};

// Fetch exercises on mount
onMounted(fetchExercises);
</script>
