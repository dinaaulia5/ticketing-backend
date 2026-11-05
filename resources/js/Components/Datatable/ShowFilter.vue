<script setup>
import { computed } from "vue";

import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/Components/ui/separator";
import { cn } from "@/lib/utils";
import { IconFilter } from "@tabler/icons-vue";

const props = defineProps({
    params: {
        type: Object,
        default: () => ({}),
    },

    className: {
        type: String,
        default: "",
    },
});

const hasFilter = computed(() => {
    return Object.keys(props.params ?? {}).some((key) => props.params[key]);
});
</script>

<template>
    <div class="py-4">
        <div
            v-if="hasFilter"
            :class="cn('flex w-full flex-wrap gap-y-2 bg-muted p-3', className)"
        >
            <span class="flex items-center gap-1 text-sm">
                <IconFilter class="size-4" />
                Filters:
            </span>

            <Separator orientation="vertical" class="mx-2 h-6" />

            <Badge
                v-for="(value, key) in params"
                v-show="value"
                :key="key"
                variant="white"
                class="mr-2"
            >
                {{ key.charAt(0).toUpperCase() + key.slice(1) }} :
                {{ value }}
            </Badge>
        </div>
    </div>
</template>
