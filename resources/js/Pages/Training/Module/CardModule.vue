<script setup>
import { reactive, computed, ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import AlertAction from "@/Components/AlertAction.vue";
import EmptyState from "@/Components/EmptyState.vue";
// import Create from "./Module/CreateModal.vue";
import CreateTrainingMaterials from "./Material/CreateModal.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";
import Materials from "./Material/Index.vue";

import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import {
    IconArrowsDownUp,
    IconBook,
    IconEye,
    IconFile,
    IconPencil,
    IconPlane,
    IconPlus,
    IconStack2,
    IconTrash,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";
import { watch } from "vue";

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
    materials: {
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

const training = computed(() => props.training?.data ?? {});
const modules = computed(() => props.modules?.data ?? []);
const materials = computed(() => props.materials?.data ?? []);
const meta = computed(() => props.modules?.meta ?? {});
const links = computed(() => props.modules?.links ?? []);

const params = reactive({
    ...props.state,
});

const openModal = ref(false);
const modalMode = ref("create");
const selectedModule = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedModule.value = null;
    openModal.value = true;
};

const openModalMaterials = ref(false);
const selectedMaterialModule = ref(null);
const selectedMaterial = ref(null);

const openMaterialModal = (module) => {
    selectedMaterialModule.value = module;
    selectedMaterial.value = null;
    openModalMaterials.value = true;
};

const onSortTable = (field) => {
    params.field = field;
    params.direction =
        params.field === field && params.direction === "asc" ? "desc" : "asc";

    router.get(route("training-modules.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["training-modules"],
    });
};

const formatDate = (date) => {
    if (!date) return "-";

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) return date;

    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    }).format(parsedDate);
};

const statusClass = (status) => {
    const styles = {
        DRAFT: "bg-amber-500/15 text-amber-400 ring-1 ring-amber-500/30",
        PUBLISHED:
            "bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/30",
        ONGOING: "bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30",
        COMPLETED: "bg-blue-500/15 text-blue-400 ring-1 ring-blue-500/30",
        CANCELLED: "bg-red-500/15 text-red-400 ring-1 ring-red-500/30",
    };

    return (
        styles[String(status ?? "").toUpperCase()] ??
        "bg-muted text-muted-foreground"
    );
};

const expandedModules = ref([]);

const toggleModule = (moduleId) => {
    if (expandedModules.value.includes(moduleId)) {
        expandedModules.value = expandedModules.value.filter(
            (id) => id !== moduleId,
        );
    } else {
        expandedModules.value.push(moduleId);
    }
};

watch(
    modules,
    (newModules) => {
        console.log("=== DEBUG TRAINING MODULES ===");
        console.log("Semua modules:", newModules);

        newModules.forEach((module, index) => {
            console.log(`Module ${index + 1}:`, module.title);
            console.log("Module ID:", module.id);
            console.log("Materials:", module.materials);
            console.log("Jumlah materials:", module.materials?.length ?? 0);
        });
    },
    { immediate: true, deep: true },
);
</script>

<template>
    <div class="flex items-center gap-3 px-3 py-3">
        <!-- Drag Handle -->
        <button
            type="button"
            class="shrink-0 cursor-grab text-muted-foreground"
            title="Atur urutan modul"
        >
            <IconGripVertical class="size-4" />
        </button>

        <!-- Nomor Modul -->
        <button
            type="button"
            class="flex size-11 shrink-0 items-center justify-center rounded-xl border bg-muted/60 text-lg font-bold transition-colors hover:bg-muted"
            :aria-expanded="expandedModules.includes(module.id)"
            :title="
                expandedModules.includes(module.id)
                    ? 'Tutup daftar materi'
                    : 'Klik untuk melihat materi'
            "
            @click="toggleModule(module.id)"
        >
            {{
                String(
                    index +
                        1 +
                        ((meta.current_page ?? 1) - 1) * (meta.per_page ?? 10),
                ).padStart(2, "0")
            }}
        </button>

        <!-- Informasi Modul -->
        <button
            type="button"
            class="min-w-0 flex-1 text-left"
            :aria-expanded="expandedModules.includes(module.id)"
            @click="toggleModule(module.id)"
        >
            <p class="truncate text-sm font-semibold">
                {{ module.title }}
            </p>

            <p class="mt-1 text-xs text-muted-foreground">
                {{ module.materials_count ?? module.materials?.length ?? 0 }}
                materi
                <span class="mx-1">·</span>
                {{ module.duration_minutes ?? 0 }} menit
                <span class="mx-1">·</span>
                {{ module.is_required ? "Wajib" : "Opsional" }}
            </p>

            <p class="mt-1 text-xs text-primary">
                {{
                    expandedModules.includes(module.id)
                        ? "Tutup materi"
                        : "Klik untuk melihat materi"
                }}
            </p>
        </button>

        <!-- Status -->
        <span
            class="hidden shrink-0 rounded-full px-2.5 py-1 text-xs font-medium sm:inline-flex"
            :class="
                module.is_active
                    ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400'
                    : 'bg-amber-500/15 text-amber-600 dark:text-amber-400'
            "
        >
            <span class="mr-1">●</span>
            {{ module.is_active ? "Aktif" : "Nonaktif" }}
        </span>

        <!-- Tombol Aksi -->
        <div class="flex shrink-0 items-center gap-1">
            <!-- Detail Modul -->
            <Button
                variant="blue"
                size="icon"
                class="size-8"
                title="Buka halaman detail modul"
                as-child
            >
                <Link :href="route('training-modules.show', module.id)">
                    <IconEye class="size-4" />
                </Link>
            </Button>

            <!-- Edit Modul -->
            <Button
                variant="blue"
                size="icon"
                class="size-8"
                title="Edit modul"
                @click="openEditModal(module)"
            >
                <IconPencil class="size-4" />
            </Button>

            <!-- Hapus Modul -->
            <AlertAction
                :action="
                    () =>
                        deleteAction(
                            route('training-modules.destroy', module.id),
                        )
                "
            >
                <Button
                    variant="red"
                    size="icon"
                    class="size-8"
                    title="Hapus modul"
                >
                    <IconTrash class="size-4" />
                </Button>
            </AlertAction>

            <!-- Panah Dropdown -->
            <Button
                type="button"
                variant="ghost"
                size="icon"
                class="ml-1 size-9 shrink-0"
                :aria-expanded="expandedModules.includes(module.id)"
                :title="
                    expandedModules.includes(module.id)
                        ? 'Tutup daftar materi'
                        : 'Buka daftar materi'
                "
                @click="toggleModule(module.id)"
            >
                <IconChevronDown
                    class="size-5 transition-transform duration-200"
                    :class="{
                        'rotate-180': expandedModules.includes(module.id),
                    }"
                />
            </Button>
        </div>
    </div>
</template>
