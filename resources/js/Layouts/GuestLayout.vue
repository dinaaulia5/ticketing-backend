<script setup>
import { Head } from "@inertiajs/vue3";
import { Toaster } from "@/Components/ui/sonner";

import TopBar from "./Partials/TopBar.vue";
import Footer from "./Partials/Footer.vue";
import ThemeSwitcher from "@/Components/Dark/ThemeSwitcher.vue";
import { useAgentNotification } from "@/Composables/useAgentNotification";

defineProps({
    title: String,
});

useAgentNotification();
</script>

<template>
    <div class="min-h-screen flex flex-col bg-background">
        <Head :title="title" />

        <Toaster rich-colors position="top-center" />

        <TopBar />

        <main class="flex-1">
            <div class="px-4">
                <slot />
            </div>

            <div
                class="fixed flex justify-center w-full bottom-5 end-5 lg:justify-end"
            >
                <ThemeSwitcher />
            </div>
        </main>

        <Footer />
    </div>
</template>
