<script setup>
import { Card } from "@/Components/ui/card";
import { cn } from "@/lib/utils";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Card
        :class="
            cn(
                'min-w-0 min-h-[100px] overflow-hidden rounded-xl border-0 p-4 text-white shadow-sm',
                data.background,
                data.className || '',
            )
        "
    >
        <div class="flex min-w-0 items-center gap-3">
            <!-- Icon -->
            <div
                :class="
                    cn(
                        'flex size-11 shrink-0 items-center justify-center rounded-xl',
                        data.iconBackground,
                    )
                "
            >
                <component v-if="data.icon" :is="data.icon" class="size-6" />
            </div>

            <!-- Content -->
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-1">
                    <p class="text-sm font-semibold leading-tight">
                        {{ data.title }}
                    </p>

                    <span
                        v-if="data.percentage !== undefined"
                        class="shrink-0 rounded-full bg-white/20 px-2 py-1 text-xs font-semibold"
                    >
                        {{ data.percentage }}%
                    </span>
                </div>

                <div class="mt-2 flex items-end justify-between gap-2">
                    <div class="text-2xl font-bold leading-none">
                        <slot />
                    </div>

                    <div
                        v-if="data.bars"
                        class="flex h-8 shrink-0 items-end gap-1"
                    >
                        <span
                            v-for="(bar, index) in data.bars"
                            :key="index"
                            class="w-1.5 rounded-t-sm bg-white/70"
                            :style="{ height: `${bar}%` }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>
