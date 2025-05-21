<template>
    <div class="max-w-5xl mx-auto p-6">
        <!-- Success Message -->
        <div
            v-if="successMessage"
            class="bg-green-500 text-white p-4 rounded mb-6"
        >
            {{ successMessage }}
        </div>

        <div v-if="errorMessage" class="bg-red-500 text-white p-4 rounded mb-6">
            {{ errorMessage }}
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label for="name" class="text-sm font-medium"
                    >Exercise Name</label
                >
                <input
                    v-model="exercise.name"
                    type="text"
                    id="name"
                    class="border rounded p-2 w-full"
                    required
                />
            </div>

            <div class="flex flex-col space-y-2">
                <label for="muscle_group" class="text-sm font-medium"
                    >Muscle Group</label
                >
                <input
                    v-model="exercise.muscle_group"
                    type="text"
                    id="muscle_group"
                    class="border rounded p-2 w-full"
                />
            </div>

            <div class="flex flex-col space-y-2">
                <label for="description" class="text-sm font-medium"
                    >Description/Video Link</label>
                <textarea
                    v-model="exercise.description"
                    id="description"
                    class="border rounded p-2 w-full"
                    rows="4"
                ></textarea>
            </div>

            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
            >
                Save Exercise
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const exercise = ref({
    name: "",
    muscle_group: "",
    description: "",
});

const successMessage = ref("");
const errorMessage = ref("");

const submitForm = () => {
    router.post("/exercises", exercise.value, {
        onSuccess: () => {
            successMessage.value = "Exercise added successfully!";
            errorMessage.value = "";
            exercise.value = { name: "", muscle_group: "", description: "" };
        },
        onError: (errors) => {
            successMessage.value = "";
            errorMessage.value = "Failed to add exercise. Please try again.";
            console.error("Error submitting form:", errors);
        },
    });
};
</script>

<style scoped>
/* Tailwind CSS styles or custom styles can be applied here */
</style>
