<script setup>
import { ref, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";

import { Card, CardContent } from "@/Components/ui/card";
import TableSubsystem from "../Subsystem/Index.vue";

import { Button } from "@/Components/ui/button";

import {
    IconArrowLeft,
    IconDeviceDesktop,
    IconPencil,
    IconLayersIntersect,
    IconTag,
    IconCalendar,
} from "@tabler/icons-vue";

import { formatDateIndo } from "@/lib/utils";
import CreateModalCategory from "./Create.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
    subsystems: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },
    pageSettings: {
        type: Object,
        required: true,
    },
    state: {
        type: Object,
        default: () => ({}),
    },
    items: {
        type: Array,
        default: () => [],
    },
});

const subsystems = computed(() => props.subsystems);
// Edit Category Modal
const openModal = ref(false);

const openEditModal = () => {
    openModal.value = true;
};

// Back to Category
const goBack = () => {
    router.get(route("categories.index"));
};
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head :title="`Detail ${category.name}`" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- Page Header -->
        <div class="flex items-start gap-4">
            <Button
                variant="outline"
                size="icon"
                class="shrink-0"
                title="Kembali"
                @click="goBack"
            >
                <IconArrowLeft class="size-5" />
            </Button>

            <div class="flex flex-col gap-y-1">
                <h1 class="text-2xl font-bold tracking-tight">
                    Detail Kategori
                </h1>

                <p class="text-sm text-muted-foreground">
                    Informasi kategori dan daftar subsystem yang termasuk di
                    dalamnya.
                </p>
            </div>
        </div>

        <!-- Category Information -->
        <Card class="overflow-hidden">
            <!-- Category Header -->
            <div
                class="flex flex-col justify-between gap-4 border-b p-5 sm:flex-row sm:items-center"
            >
                <div class="flex items-center gap-4">
                    <!-- Category Icon -->
                    <div
                        class="flex size-16 shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400"
                    >
                        <IconDeviceDesktop class="size-9" />
                    </div>

                    <!-- Category Name -->
                    <div class="flex flex-col gap-y-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl font-bold">
                                {{ category.name }}
                            </h2>

                            <span
                                class="inline-flex items-center rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-600 dark:text-blue-400"
                            >
                                {{ category.code }}
                            </span>
                        </div>

                        <p class="text-sm text-muted-foreground">
                            {{
                                category.description ||
                                "Tidak ada deskripsi kategori."
                            }}
                        </p>
                    </div>
                </div>

                <Button variant="success" size="lg" @click="openEditModal">
                    <IconPencil class="size-4" />
                    Edit Kategori
                </Button>
            </div>

            <!-- Category Details -->
            <CardContent class="p-5">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Category Code -->
                    <div class="flex items-center gap-4">
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground"
                        >
                            <IconLayersIntersect class="size-5" />
                        </div>

                        <div class="flex flex-col gap-y-1">
                            <span class="text-sm text-muted-foreground">
                                Kode Kategori
                            </span>

                            <span class="font-semibold">
                                {{ category.code }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Name -->
                    <div
                        class="flex items-center gap-4 border-y py-4 md:border-x md:border-y-0 md:px-6 md:py-0"
                    >
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground"
                        >
                            <IconTag class="size-5" />
                        </div>

                        <div class="flex flex-col gap-y-1">
                            <span class="text-sm text-muted-foreground">
                                Nama Kategori
                            </span>

                            <span class="font-semibold">
                                {{ category.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Created At -->
                    <div class="flex items-center gap-4">
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground"
                        >
                            <IconCalendar class="size-5" />
                        </div>

                        <div class="flex flex-col gap-y-1">
                            <span class="text-sm text-muted-foreground">
                                Dibuat
                            </span>

                            <span class="font-semibold">
                                {{ formatDateIndo(category.created_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <TableSubsystem
            :subsystems="subsystems"
            :state="state"
            :page-settings="pageSettings"
            :category-id="category.id"
        />

        <!-- Edit Category Modal -->
        <CreateModalCategory
            v-model:open="openModal"
            mode="edit"
            :category="category"
        />
    </div>
</template>
