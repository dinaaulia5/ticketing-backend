<script setup>
import { Head } from "@inertiajs/vue3";
import { Toaster } from "@/Components/ui/sonner";
import ThemeSwitcher from "@/Components/Dark/ThemeSwitcher.vue";

defineProps({
    title: String,
});
</script>

<template>
    <div
        class="flex min-h-screen items-center justify-center bg-background px-6 py-10"
    >
        <Head :title="title" />

        <Toaster rich-colors position="top-center" />

        <div class="w-full max-w-5xl">
            <slot />
        </div>

        <div class="fixed bottom-5 right-5 flex justify-end">
            <ThemeSwitcher />
        </div>
    </div>
</template>
