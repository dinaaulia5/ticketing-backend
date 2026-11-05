<script setup>
import { Link } from "@inertiajs/vue3";
import { IconCash, IconPlaneArrival } from "@tabler/icons-vue";

defineProps({
    url: {
        type: String,
        default: "#",
    },
});
</script>

<template>
    <Link :href="url" class="mt-6 flex items-center gap-x-2">
        <div
            class="rounded-xl bg-gradient-to-br from-cyan-400 via-cyan-500 to-blue-200 p-2"
        >
            <IconPlaneArrival class="size-6 text-white" />
        </div>

        <span
            class="text-xl font-semibold leading-relaxed tracking-wide dark:text-cyan-500"
        >
            Travel Agentic
            <span class="text-lg text-cyan-500 dark:text-white"> + </span>
        </span>
    </Link>
</template>
