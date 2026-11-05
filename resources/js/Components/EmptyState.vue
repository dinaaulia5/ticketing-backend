<script setup>
defineProps({
    title: {
        type: String,
        default: "Tidak ada data",
    },

    subtitle: {
        type: String,
        default: "Mulailah dengan membuat data baru",
    },

    icon: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="flex flex-col items-center border border-dashed border-secondary p-4"
    >
        <component :is="icon" class="size-12 text-cyan-500" />

        <h3 class="mt-2 text-lg font-semibold text-foreground">
            {{ title }}
        </h3>

        <p class="mt-1 text-sm text-muted-foreground">
            {{ subtitle }}
        </p>
    </div>
</template>
