<script setup>
import { Button } from "@/Components/ui/button";
import { IconArrowLeft } from "@tabler/icons-vue";

defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    showBack: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["back"]);
</script>

<template>
    <div class="flex items-start gap-4">
        <Button
            v-if="showBack"
            variant="outline"
            size="icon"
            class="shrink-0"
            title="Kembali"
            @click="emit('back')"
        >
            <IconArrowLeft class="size-5" />
        </Button>

        <div class="flex flex-col gap-y-1">
            <h1 class="text-2xl font-bold tracking-tight">
                {{ title }}
            </h1>

            <p class="text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>
    </div>
</template>
