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

    collapsed: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Link
        :href="url"
        :method="method"
        :as="as"
        :title="collapsed ? title : undefined"
        :aria-label="title"
        :aria-current="active ? 'page' : undefined"
        :class="
            cn(
                'group flex w-full min-w-0 items-center rounded-lg transition-all duration-200',
                collapsed
                    ? 'justify-center px-0 py-3'
                    : 'justify-start gap-3 px-3 py-2.5',
                active
                    ? 'bg-gradient-to-br from-cyan-500 via-cyan-500 to-cyan-300 font-semibold text-white shadow-sm'
                    : 'text-foreground hover:bg-muted',
                $props.class,
            )
        "
    >
        <!-- ICON -->
        <component
            :is="icon"
            v-if="icon"
            class="size-5 shrink-0"
            :class="active ? 'text-white' : 'text-current'"
        />

        <!-- TITLE -->
        <span v-if="!collapsed" class="min-w-0 flex-1 truncate text-sm">
            {{ title }}
        </span>
    </Link>
</template>
