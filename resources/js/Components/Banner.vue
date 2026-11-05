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
});
</script>

<template>
    <div
        class="flex flex-col p-4 text-white rounded-xl bg-gradient-to-br from-cyan-500 via-cyan-500 to-blue-100"
    >
        <h2 class="text-2xl font-medium leading-relaxed tracking-tight">
            {{ title }}
        </h2>

        <p class="text-sm leading-relaxed">
            {{ subTitle }}
        </p>
    </div>
</template>
