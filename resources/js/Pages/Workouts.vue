<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-semibold text-center mb-6">Add New Workout</h1>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
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
                <label for="exercises" class="text-sm font-medium"
                    >Exercises</label
                >
                <textarea
                    v-model="workout.exercises"
                    id="exercises"
                    rows="5"
                    class="border rounded p-2 w-full"
                    placeholder="Enter exercises (e.g., Pushups, Squats, etc.)"
                    required
                ></textarea>
            </div>

            <div class="flex flex-col space-y-2">
                <label for="notes" class="text-sm font-medium"
                    >Notes (optional)</label
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
</template>

<script>
export default {
    data() {
        return {
            workout: {
                date: "",
                exercises: "",
                notes: "",
            },
        };
    },
    methods: {
        submitForm() {
            // Handle the form submission by sending the data to the backend
            this.$inertia
                .post("/workouts", this.workout)
                .then(() => {
                    // Optionally redirect after submitting the form
                    this.$inertia.visit("/workouts");
                })
                .catch((error) => {
                    console.error("Error submitting form:", error);
                });
        },
    },
};
</script>

<style scoped>
/* Custom styles can go here */
</style>
