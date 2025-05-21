<script setup>
import { Head, Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <GuestLayout>
        <Head title="Welcome" />

        <div class="py-12 px-6 text-center">
            <h1
                class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-4"
            >
                Welcome to Your Fitness App
            </h1>

            <p class="text-gray-600 dark:text-gray-300 mb-8 max-w-xl mx-auto">
                This platform helps you track workouts, measure progress, and
                stay motivated.
            </p>

            <div
                class="flex flex-col sm:flex-row justify-center items-center gap-4"
            >
                <template v-if="canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        Go to Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-flex items-center px-6 py-3 bg-gray-800 text-white text-sm font-medium rounded-md shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-800 text-sm font-medium rounded-md shadow hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                        >
                            Register
                        </Link>
                    </template>
                </template>
            </div>
        </div>
    </GuestLayout>
</template>
