<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
    class: {
        type: [Boolean, null, String, Object, Array],
        required: false,
        skipCheck: true,
    },
    size: { type: String, required: false, default: "default" },
});
</script>

<template>
    <div
        data-slot="card"
        :data-size="size"
        :class="
            cn(
                'rounded-xl border bg-card text-card-foreground dark:border-0',
                props.class,
            )
        "
    >
        <slot />
    </div>
</template>
