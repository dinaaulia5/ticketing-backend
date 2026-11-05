<script setup>
import { Link } from "@inertiajs/vue3";
import { cn } from "@/lib/utils";

defineProps({
    active: {
        type: Boolean,
        default: false,
    },

    url: {
        type: String,
        default: "#",
    },

    title: {
        type: String,
        required: true,
    },

    icon: {
        type: Object,
        default: null,
    },

    method: {
        type: String,
        default: "get",
    },

    as: {
        type: String,
        default: "a",
    },

    class: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <Link
        :href="url"
        :method="method"
        :as="as"
        :class="
            cn(
                active
                    ? 'bg-gradient-to-br from-cyan-500 via-cyan-500 to-bule-200 font-medium text-white'
                    : 'hover:bg-muted',
                'flex items-center gap-3 rounded-lg p-2.5 transition-all dark:text-white',
                $props.class,
            )
        "
    >
        <component :is="icon" v-if="icon" class="size-5" />

        {{ title }}
    </Link>
</template>
