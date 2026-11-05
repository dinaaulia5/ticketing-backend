<script setup>
import { computed, reactive, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import EmptyState from "@/Components/EmptyState.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";

import Create from "./Module/CreateModal.vue";
import Module from "./Module/Index.vue";

import { Button } from "@/Components/ui/button";

import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";

import { IconBook, IconPencil, IconPlane, IconPlus } from "@tabler/icons-vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    training: {
        type: Object,
        required: true,
    },
    modules: {
        type: Object,
        required: true,
    },
    pageSettings: {
        type: Object,
        required: true,
    },
    items: {
        type: Array,
        default: () => [],
    },
    state: {
        type: Object,
        default: () => ({}),
    },
});

// Data Training dan Module
const training = computed(() => props.training?.data ?? {});
const modules = computed(() => props.modules?.data ?? []);
const meta = computed(() => props.modules?.meta ?? {});
const links = computed(() => props.modules?.links ?? []);

// Filter
const params = reactive({
    ...props.state,
});

// Navigasi Tab
const activeTab = ref("modules");

const tabs = [
    { value: "information", label: "Informasi" },
    { value: "modules", label: "Modul & Materi" },
    { value: "quiz", label: "Quiz / Assessment" },
    { value: "participants", label: "Peserta" },
    { value: "certificates", label: "Sertifikat" },
    { value: "settings", label: "Pengaturan" },
];

const activeTabLabel = computed(
    () => tabs.find((tab) => tab.value === activeTab.value)?.label ?? "",
);

// Modal Module
const openModal = ref(false);
const modalMode = ref("create");
const selectedModule = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedModule.value = null;
    openModal.value = true;
};

const openEditModal = (module) => {
    modalMode.value = "edit";
    selectedModule.value = module;
    openModal.value = true;
};

// Format Date
const formatDate = (date) => {
    if (!date) return "-";

    const parsedDate = new Date(`${date}`.slice(0, 10) + "T00:00:00");

    if (Number.isNaN(parsedDate.getTime())) return "-";

    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    }).format(parsedDate);
};
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <Head title="Training Master" />

        <BreadcrumbHeader :items="items" />

        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- Informasi Utama Pelatihan -->
        <Card class="overflow-hidden">
            <CardContent class="p-4 sm:p-5">
                <div class="flex flex-col gap-5">
                    <!-- Header Pelatihan -->
                    <div
                        class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"
                    >
                        <!-- Thumbnail dan Informasi -->
                        <div class="flex min-w-0 flex-1 items-start gap-4">
                            <!-- Thumbnail -->
                            <div
                                class="flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-cyan-500/30 bg-cyan-950 text-cyan-400 sm:size-24"
                            >
                                <img
                                    v-if="training.thumbnail"
                                    :src="`/storage/${training.thumbnail}`"
                                    :alt="
                                        training.title ?? 'Thumbnail pelatihan'
                                    "
                                    class="size-full object-cover"
                                />

                                <IconBook
                                    v-else
                                    :size="38"
                                    stroke-width="1.5"
                                />
                            </div>

                            <!-- Judul, Kode, Status, Deskripsi -->
                            <div class="min-w-0 flex-1 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2
                                        class="text-xl font-bold tracking-tight sm:text-2xl"
                                    >
                                        {{ training.title ?? "-" }}
                                    </h2>

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="
                                            training.status === 'PUBLISHED'
                                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300'
                                                : 'bg-muted text-muted-foreground'
                                        "
                                    >
                                        <span
                                            class="size-1.5 rounded-full"
                                            :class="
                                                training.status === 'PUBLISHED'
                                                    ? 'bg-emerald-500'
                                                    : 'bg-muted-foreground'
                                            "
                                        ></span>

                                        {{
                                            training.status === "PUBLISHED"
                                                ? "Published"
                                                : (training.status ?? "-")
                                        }}
                                    </span>
                                </div>

                                <div>
                                    <span
                                        class="inline-flex rounded-md bg-muted px-2 py-1 text-xs font-medium text-muted-foreground"
                                    >
                                        {{ training.code ?? "-" }}
                                    </span>
                                </div>

                                <p
                                    class="max-w-3xl text-sm leading-relaxed text-muted-foreground"
                                >
                                    {{
                                        training.description ||
                                        "Belum ada deskripsi pelatihan."
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Tombol Edit -->
                        <div class="shrink-0">
                            <Button variant="blue" size="xl" as-child>
                                <Link
                                    :href="
                                        route('trainings.edit', {
                                            training: training.id,
                                        })
                                    "
                                >
                                    <IconPencil class="size-4" />
                                    Edit Pelatihan
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div
                        class="grid grid-cols-2 gap-x-4 gap-y-4 border-t pt-4 sm:grid-cols-3 lg:grid-cols-5"
                    >
                        <!-- Periode Pelatihan -->
                        <div class="flex min-w-0 items-start gap-2.5">
                            <IconCalendar
                                class="mt-0.5 size-5 shrink-0 text-muted-foreground"
                            />

                            <div class="min-w-0">
                                <p class="text-xs text-muted-foreground">
                                    Periode Pelatihan
                                </p>
                                <p class="mt-1 text-sm font-medium">
                                    {{ formatDate(training.start_date) }}
                                    <span class="text-muted-foreground">-</span>
                                    {{ formatDate(training.end_date) }}
                                </p>
                            </div>
                        </div>

                        <!-- Tipe Pelatihan -->
                        <div class="flex min-w-0 items-start gap-2.5">
                            <IconClock
                                class="mt-0.5 size-5 shrink-0 text-muted-foreground"
                            />

                            <div class="min-w-0">
                                <p class="text-xs text-muted-foreground">
                                    Tipe Pelatihan
                                </p>
                                <p class="mt-1 text-sm font-medium">
                                    {{
                                        training.type?.name ??
                                        training.type?.label ??
                                        "-"
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- Level -->
                        <div class="flex min-w-0 items-start gap-2.5">
                            <IconBook2
                                class="mt-0.5 size-5 shrink-0 text-muted-foreground"
                            />

                            <div class="min-w-0">
                                <p class="text-xs text-muted-foreground">
                                    Level
                                </p>
                                <p class="mt-1 text-sm font-medium">
                                    {{ training.level ?? "-" }}
                                </p>
                            </div>
                        </div>

                        <!-- Durasi -->
                        <div class="flex min-w-0 items-start gap-2.5">
                            <IconClockHour4
                                class="mt-0.5 size-5 shrink-0 text-muted-foreground"
                            />

                            <div class="min-w-0">
                                <p class="text-xs text-muted-foreground">
                                    Durasi
                                </p>
                                <p class="mt-1 text-sm font-medium">
                                    {{ training.duration_minutes ?? 0 }} menit
                                </p>
                            </div>
                        </div>

                        <!-- Kapasitas Peserta -->
                        <div class="flex min-w-0 items-start gap-2.5">
                            <IconUsers
                                class="mt-0.5 size-5 shrink-0 text-muted-foreground"
                            />

                            <div class="min-w-0">
                                <p class="text-xs text-muted-foreground">
                                    Kapasitas Peserta
                                </p>
                                <p class="mt-1 text-sm font-medium">
                                    {{ training.capacity ?? 0 }} peserta
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Navigasi Tab -->
        <div class="border-b">
            <nav
                class="-mb-px flex gap-6 overflow-x-auto px-4"
                aria-label="Navigasi pelatihan"
            >
                <button
                    v-for="tab in tabs"
                    :key="tab.value"
                    type="button"
                    @click="activeTab = tab.value"
                    class="shrink-0 border-b-2 px-1 pb-3 pt-2 text-sm font-medium transition-colors"
                    :class="
                        activeTab === tab.value
                            ? 'border-cyan-600 text-cyan-600 dark:text-cyan-400'
                            : 'border-transparent text-muted-foreground hover:border-muted-foreground/40 hover:text-foreground'
                    "
                >
                    {{ tab.label }}
                </button>
            </nav>
        </div>

        <!-- Tab Informasi -->
        <Card v-if="activeTab === 'information'" class="overflow-hidden">
            <CardHeader>
                <HeaderTitle
                    title="Informasi Pelatihan"
                    sub-title="Detail informasi pelatihan yang sedang dikelola."
                    :icon="IconBook"
                />
            </CardHeader>

            <CardContent class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-muted-foreground">Nama Pelatihan</p>
                    <p class="mt-1 font-medium">
                        {{ training.title ?? "-" }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">Kode Pelatihan</p>
                    <p class="mt-1 font-medium">
                        {{ training.code ?? "-" }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">Status</p>
                    <p class="mt-1 font-medium">
                        {{ training.status ?? "-" }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">Deskripsi</p>
                    <p class="mt-1 font-medium">
                        {{
                            training.description ||
                            "Belum ada deskripsi pelatihan."
                        }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <!-- Tab Modul & Materi -->
        <Card v-else-if="activeTab === 'modules'" class="overflow-hidden">
            <CardHeader class="space-y-4 p-0">
                <div
                    class="flex flex-col items-start justify-between gap-4 p-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconPlane"
                    />

                    <Button variant="red" size="xl" @click="openCreateModal">
                        <IconPlus class="size-4" />
                        Tambah Modul
                    </Button>
                </div>

                <Filter v-model:params="params" :state="state" />

                <ShowFilter :params="params" />
            </CardHeader>

            <CardContent class="p-0">
                <EmptyState
                    v-if="modules.length === 0"
                    :icon="IconBook"
                    title="Belum Ada Modul"
                    subtitle="Tambahkan modul untuk mulai menyusun materi pembelajaran dalam pelatihan ini."
                />

                <div v-else class="flex flex-col gap-3">
                    <Module
                        :training="training"
                        :modules="props.modules"
                        :page-settings="pageSettings"
                        :state="state"
                        @create-module="openCreateModal"
                        @edit-module="openEditModal"
                        @add-material="openMaterialModal"
                    />
                </div>
            </CardContent>

            <CardFooter
                class="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row"
            >
                <p class="text-sm text-muted-foreground">
                    Menampilkan
                    <span class="font-medium text-cyan-600">
                        {{ meta.from ?? 0 }}
                    </span>
                    dari
                    <span class="font-medium">
                        {{ meta.total ?? 0 }}
                    </span>
                    Pelatihan
                </p>

                <div class="overflow-x-auto">
                    <PaginationTable
                        v-if="meta.has_pages"
                        :meta="meta"
                        :links="links"
                    />
                </div>
            </CardFooter>
        </Card>

        <!-- Tab Lainnya: Placeholder -->
        <Card v-else class="overflow-hidden">
            <CardContent class="p-8">
                <EmptyState
                    :icon="IconBook"
                    :title="activeTabLabel"
                    subtitle="Fitur ini akan tersedia pada tahap pengembangan berikutnya."
                />
            </CardContent>
        </Card>

        <!-- Modal Tambah / Edit Modul -->
        <Create
            v-model:open="openModal"
            :mode="modalMode"
            :module="selectedModule"
            :training="training"
        />
    </div>
</template>
