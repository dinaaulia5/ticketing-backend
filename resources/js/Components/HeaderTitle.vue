<script setup>
defineProps({
    title: {
        type: String,
        default: "",
    },

    subTitle: {
        type: String,
        default: "",
    },

    icon: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-col">
        <div class="flex flex-row items-center gap-x-3">
            <div class="rounded-xl bg-cyan-50 p-1">
                <component :is="icon" class="size-6 text-cyan-500" />
            </div>

            <h1
                class="text-2xl font-medium leading-relaxed tracking-wide text-foreground"
            >
                {{ title }}
            </h1>
        </div>

        <p class="text-sm font-light text-muted-foreground">
            {{ subTitle }}
        </p>
    </div>
</template>
