<script setup>
import { Tabs, TabsList, TabsTrigger } from "@/Components/ui/tabs";

import {
    IconList,
    IconCategory,
    IconMapPin,
    IconUsers,
    IconTool,
    IconPaperclip,
} from "@tabler/icons-vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <Tabs
        :model-value="props.modelValue"
        @update:model-value="emit('update:modelValue', $event)"
        class="w-full min-w-0"
    >
        <!-- Responsive Wrapper -->
        <div class="w-full min-w-0 overflow-x-auto overflow-y-hidden">
            <TabsList
                class="inline-flex !h-12 !w-max !min-w-max flex-nowrap items-center justify-start gap-1 rounded-xl border bg-muted/40 p-1"
            >
                <!-- DAFTAR ASET -->
                <TabsTrigger
                    value="asset"
                    class="h-10 shrink-0 min-w-[130px] gap-2 rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-active:!bg-cyan-500 data-active:!text-white data-active:!shadow-md dark:data-active:!bg-cyan-500 dark:data-active:!text-white"
                >
                    <IconList class="size-4 shrink-0" />
                    <span>Daftar Aset</span>
                </TabsTrigger>

                <!-- KATEGORI -->
                <TabsTrigger
                    value="category"
                    class="h-10 shrink-0 min-w-[160px] gap-2 whitespace-nowrap rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-[state=active]:!bg-cyan-500 data-[state=active]:!text-white data-[state=active]:!shadow-md"
                >
                    <IconCategory class="size-4 shrink-0" />
                    <span class="whitespace-nowrap">Kategori Aset</span>
                </TabsTrigger>

                <!-- LOKASI -->
                <TabsTrigger
                    value="location"
                    class="h-10 shrink-0 min-w-[130px] gap-2 rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-active:!bg-cyan-500 data-active:!text-white data-active:!shadow-md dark:data-active:!bg-cyan-500 dark:data-active:!text-white"
                >
                    <IconMapPin class="size-4 shrink-0" />
                    <span>Lokasi</span>
                </TabsTrigger>

                <!-- PENUGASAN -->
                <TabsTrigger
                    value="assignment"
                    class="h-10 shrink-0 min-w-[130px] gap-2 rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-active:!bg-cyan-500 data-active:!text-white data-active:!shadow-md dark:data-active:!bg-cyan-500 dark:data-active:!text-white"
                >
                    <IconUsers class="size-4 shrink-0" />
                    <span>Penugasan</span>
                </TabsTrigger>

                <!-- PERAWATAN -->
                <TabsTrigger
                    value="maintenance"
                    class="h-10 shrink-0 min-w-[130px] gap-2 rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-active:!bg-cyan-500 data-active:!text-white data-active:!shadow-md dark:data-active:!bg-cyan-500 dark:data-active:!text-white"
                >
                    <IconTool class="size-4 shrink-0" />
                    <span>Perawatan</span>
                </TabsTrigger>

                <!-- LAMPIRAN -->
                <TabsTrigger
                    value="attachment"
                    class="h-10 shrink-0 min-w-[130px] gap-2 rounded-lg px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-cyan-500/10 hover:text-cyan-600 data-active:!bg-cyan-500 data-active:!text-white data-active:!shadow-md dark:data-active:!bg-cyan-500 dark:data-active:!text-white"
                >
                    <IconPaperclip class="size-4 shrink-0" />
                    <span>Lampiran</span>
                </TabsTrigger>
            </TabsList>
        </div>
    </Tabs>
</template>
