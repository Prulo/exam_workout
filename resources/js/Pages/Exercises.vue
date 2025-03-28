<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import Exercise from "@/Components/Exercise.vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const exercises = ref([]);
const searchQuery = ref("");
const expandedExercise = ref(null);

const fetchExercises = () => {
    const page = usePage();
    exercises.value = page.props.exercises || [];
};

const deleteExercise = (id) => {
    if (!confirm("Are you sure you want to delete this exercise?")) return;

    router.delete(`/exercises/${id}`, {
        onSuccess: () => {
            exercises.value = exercises.value.filter((e) => e.id !== id);
        },
        onError: (error) => {
            console.error("Error deleting exercise:", error);
        },
    });
};

const filteredExercises = computed(() => {
    return exercises.value.filter((exercise) => {
        const query = searchQuery.value.toLowerCase();
        return (
            exercise.name.toLowerCase().includes(query) ||
            exercise.muscle_group.toLowerCase().includes(query)
        );
    });
});

const toggleDetails = (id) => {
    expandedExercise.value = expandedExercise.value === id ? null : id;
};

onMounted(fetchExercises);
</script>

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
            <h1 class="text-2xl font-semibold mb-4">All Exercises</h1>

            <strong>Search(Exercise/Muscel group):</strong>
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by exercise name or muscle group..."
                class="w-full p-2 border rounded mb-4"
            />

            <ul v-if="filteredExercises.length" class="space-y-4 mt-4">
                <li
                    v-for="exercise in filteredExercises"
                    :key="exercise.id"
                    class="p-4 border rounded-lg shadow-md"
                >
                    <div
                        @click="toggleDetails(exercise.id)"
                        class="flex justify-between items-center cursor-pointer"
                    >
                        <h3 class="font-semibold text-lg">
                            {{ exercise.name }}
                        </h3>
                        <span class="text-blue-500">
                            {{ expandedExercise === exercise.id ? "▲" : "▼" }}
                        </span>
                    </div>

                    <div v-if="expandedExercise === exercise.id" class="mt-3">
                        <p class="text-gray-600">
                            <strong>Muscle Group:</strong>
                            {{ exercise.muscle_group }}
                        </p>
                        <p class="text-gray-600">{{ exercise.description }}</p>

                        <button
                            @click="deleteExercise(exercise.id)"
                            class="mt-2 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                        >
                            Delete
                        </button>
                    </div>
                </li>
            </ul>

            <!-- No Results Message -->
            <div v-else>
                <p>No exercises found.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
