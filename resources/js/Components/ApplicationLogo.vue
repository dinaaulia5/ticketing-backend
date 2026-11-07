<script setup>
import { Link } from "@inertiajs/vue3";
import { IconPlaneArrival } from "@tabler/icons-vue";

const props = defineProps({
    url: {
        type: String,
        default: "#",
    },

    collapsed: {
        type: Boolean,
        default: false,
    },

    collapsible: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["toggle-collapse"]);

const handleClick = (event) => {
    if (props.collapsed && props.collapsible) {
        event.preventDefault();
        emit("toggle-collapse");
    }
};
</script>

<template>
    <Link
        :href="url"
        class="flex min-w-0 items-center gap-x-2 overflow-hidden"
        :class="collapsed ? 'justify-center' : 'justify-start'"
        :title="collapsed ? 'Buka sidebar' : 'Travel Agentic'"
        @click="handleClick"
    >
        <!-- LOGO ICON -->
        <div
            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-400 via-cyan-500 to-blue-200"
        >
            <IconPlaneArrival class="size-6 text-white" />
        </div>

        <!-- LOGO TEXT -->
        <div
            class="overflow-hidden whitespace-nowrap transition-all duration-300 ease-in-out"
            :class="
                collapsed
                    ? 'max-w-0 translate-x-[-8px] opacity-0'
                    : 'max-w-[190px] translate-x-0 opacity-100 delay-100'
            "
        >
            <span
                class="text-xl font-semibold leading-relaxed tracking-wide text-foreground dark:text-cyan-500"
            >
                Travel Agentic
                <span class="text-lg text-cyan-500 dark:text-white"> + </span>
            </span>
        </div>
    </Link>
</template>
