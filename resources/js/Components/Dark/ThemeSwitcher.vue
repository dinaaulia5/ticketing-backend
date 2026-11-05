<script setup>
import { Moon, Sun } from "lucide-vue-next";
import { useTheme } from "@/Components/Dark/useTheme";

const { theme, toggleTheme } = useTheme();
</script>

<template>
    <button
        type="button"
        @click="toggleTheme"
        class="ml-auto flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500 text-white hover:bg-cyan-600"
    >
        <Sun v-if="theme === 'dark'" class="h-7 w-7" />
        <Moon v-else class="h-7 w-7" />
    </button>
</template>
