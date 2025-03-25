<script setup>
import { ref, computed } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import { usePage } from "@inertiajs/vue3";

const workouts = usePage().props.workouts;

const events = computed(() =>
    workouts.map((workout) => ({
        title: `Workout`,
        start: workout.date,
        extendedProps: { exercises: workout.exercises, notes: workout.notes },
    }))
);

const handleEventClick = (info) => {
    alert(`Exercises: ${info.event.extendedProps.exercises
        .map((e) => e.name)
        .join(", ")}
Notes: ${info.event.extendedProps.notes}`);
};
</script>

<template>
    <FullCalendar
        :plugins="[dayGridPlugin]"
        :initialView="'dayGridMonth'"
        :events="events"
        @eventClick="handleEventClick"
    />
</template>

<style>
.fc-event {
    background-color: #2563eb !important; /* Tailwind Blue */
    color: white !important;
}
</style>
