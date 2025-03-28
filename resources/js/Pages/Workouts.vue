<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const workout = ref({
    date: "",
    exercises: [],
    notes: "",
});

const exercises = ref([]);
const selectedExercise = ref({ id: "", name: "", sets: "", reps: "" });

const successMessage = ref("");
const errorMessage = ref("");

const fetchExercises = () => {
    const page = usePage();
    exercises.value = page.props.exercises || [];
};

const addExercise = () => {
    const selected = exercises.value.find(
        (exercise) => exercise.id === selectedExercise.value.id
    );
    if (
        selected &&
        selectedExercise.value.sets &&
        selectedExercise.value.reps
    ) {
        workout.value.exercises.push({
            name: selected.name,
            sets: selectedExercise.value.sets,
            reps: selectedExercise.value.reps,
        });

        selectedExercise.value = { id: "", name: "", sets: "", reps: "" };
    }
};

const removeExercise = (index) => {
    workout.value.exercises.splice(index, 1);
};

const submitForm = () => {
    const page = usePage();
    const userId = page.props.auth?.user?.id;

    if (!userId) {
        console.error(
            "User ID is missing. Make sure the user is authenticated."
        );
        return;
    }

    if (!workout.value.exercises.length) {
        console.error("Workout must contain at least one exercise.");
        return;
    }

    const workoutData = {
        user_id: userId,
        date: workout.value.date,
        exercises: JSON.stringify(workout.value.exercises),
        notes: workout.value.notes || null,
    };

    router.post("/workouts", workoutData, {
        onSuccess: () => {
            successMessage.value = "Workout added successfully!";
            errorMessage.value = "";

            workout.value = { date: "", exercises: [], notes: "" };
            selectedExercise.value = { id: "", name: "", sets: "", reps: "" };

            fetchExercises();

            setTimeout(() => {
                successMessage.value = "";
            }, 5000);
        },
        onError: (errors) => {
            successMessage.value = "";
            errorMessage.value = "Failed to add workout. Please try again.";
            console.error("Error submitting form:", errors);
        },
    });
};

onMounted(fetchExercises);
</script>

<template>
    <Head title="Workout" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add New Workout
            </h2>
        </template>
        <div class="max-w-5xl mx-auto p-6">
            <form @submit.prevent="submitForm" class="space-y-6">
                <div
                    v-if="successMessage"
                    class="bg-green-100 text-green-700 p-2 rounded"
                >
                    {{ successMessage }}
                </div>
                <div
                    v-if="errorMessage"
                    class="bg-red-100 text-red-700 p-2 rounded"
                >
                    {{ errorMessage }}
                </div>

                <div class="flex flex-col space-y-2">
                    <label for="date" class="text-sm font-medium"
                        >Workout Date</label
                    >
                    <input
                        v-model="workout.date"
                        type="date"
                        id="date"
                        class="border rounded p-2 w-full"
                        required
                    />
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-medium"
                        >Select an Exercise</label
                    >
                    <div class="flex gap-2">
                        <select
                            v-model="selectedExercise.id"
                            class="border rounded p-2 w-full"
                            :disabled="!exercises.length"
                        >
                            <option disabled value="">
                                Select an exercise
                            </option>
                            <option
                                v-for="exercise in exercises"
                                :key="exercise.id"
                                :value="exercise.id"
                            >
                                {{ exercise.name }}
                            </option>
                        </select>
                    </div>

                    <div class="flex gap-2 mt-2">
                        <input
                            v-model="selectedExercise.sets"
                            type="number"
                            min="1"
                            class="border rounded p-2 w-1/3"
                            placeholder="Sets"
                        />
                        <input
                            v-model="selectedExercise.reps"
                            type="number"
                            min="1"
                            class="border rounded p-2 w-1/3"
                            placeholder="Reps"
                        />
                        <button
                            type="button"
                            @click="addExercise"
                            :disabled="
                                !selectedExercise.sets ||
                                !selectedExercise.reps ||
                                !selectedExercise.id
                            "
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition"
                        >
                            Add
                        </button>
                    </div>
                </div>

                <div
                    v-if="workout.exercises.length"
                    class="flex flex-col space-y-2"
                >
                    <label class="text-sm font-medium">Workout Exercises</label>
                    <ul class="border rounded p-2 w-full bg-gray-100">
                        <li
                            v-for="(exercise, index) in workout.exercises"
                            :key="index"
                            class="flex justify-between items-center p-2 border-b"
                        >
                            <span>
                                {{ exercise.name }} - {{ exercise.sets }} sets x
                                {{ exercise.reps }} reps
                            </span>
                            <button
                                @click="removeExercise(index)"
                                class="text-red-500 hover:underline"
                            >
                                Remove
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-col space-y-2">
                    <label for="notes" class="text-sm font-medium"
                        >Workout Name (Pull, Push, Legs):</label
                    >
                    <textarea
                        v-model="workout.notes"
                        id="notes"
                        rows="3"
                        class="border rounded p-2 w-full"
                        placeholder="Enter any additional notes"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition"
                >
                    Save Workout
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
