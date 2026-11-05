<script setup>
import { reactive, computed, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import AlertAction from "@/Components/AlertAction.vue";
import Materials from "../Material/Index.vue";
import CreatePostTest from "../Quiz/Components/CreatePostTest.vue";
import CreateTrainingMaterials from "../Material/CreateModal.vue";

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

const training = computed(() => {
    return (
        props.training?.data?.data ??
        props.training?.data ??
        props.training ??
        {}
    );
});
const modules = computed(() => props.modules?.data ?? []);
const meta = computed(() => props.modules?.meta ?? {});

const openModalMaterials = ref(false);
const selectedMaterialModule = ref(null);
const selectedMaterial = ref(null);

const openMaterialModal = (module) => {
    selectedMaterialModule.value = module;
    selectedMaterial.value = null;
    openModalMaterials.value = true;
};

const emit = defineEmits([
    "create-module",
    "edit-module",
    "add-material",
    "add-quiz",
]);

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

const showPostTestModal = ref(false);
const processing = ref(false);

const handlePostTestSave = (payload) => {
    processing.value = true;

    router.post(route("quizzes.store"), payload, {
        onSuccess: () => {
            console.log("[POST TEST] Berhasil disimpan.");
            showPostTestModal.value = false;
        },
        onError: (errors) => {
            console.error("[POST TEST] Validasi gagal:", errors);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <div class="flex flex-col gap-4 p-4 sm:p-5">
        <div
            v-for="(module, index) in modules"
            :key="module.id"
            class="overflow-hidden rounded-xl border bg-card transition-colors hover:border-primary/40"
        >
            <!-- HEADER MODUL -->
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
                                ((meta.current_page ?? 1) - 1) *
                                    (meta.per_page ?? 10),
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
                        {{
                            module.materials_count ??
                            module.materials?.length ??
                            0
                        }}
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
                                    route(
                                        'training-modules.destroy',
                                        module.id,
                                    ),
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
                                'rotate-180': expandedModules.includes(
                                    module.id,
                                ),
                            }"
                        />
                    </Button>
                </div>
            </div>

            <!-- DROPDOWN TRAINING MATERIALS -->
            <div v-if="expandedModules.includes(module.id)">
                <Materials
                    :module="module"
                    :training="training"
                    :quizzes="module.quizzes ?? []"
                    @add-material="openMaterialModal"
                />
            </div>
        </div>

        <!-- FINAL ASSESSMENT (POST TEST) -->
        <div
            class="flex flex-col gap-4 rounded-xl border border-cyan-500 bg-cyan-500/5 p-3 transition-colors hover:bg-cyan-500/10 sm:flex-row sm:items-center sm:justify-between sm:px-4 sm:py-3"
        >
            <!-- Informasi Post Test -->
            <div class="flex min-w-0 items-center gap-3">
                <!-- Ikon Post Test -->
                <div
                    class="flex size-11 shrink-0 items-center justify-center rounded-lg border border-cyan-500/40 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400"
                >
                    <IconClipboardCheck class="size-5" />
                </div>

                <!-- Judul dan Deskripsi -->
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold">
                        Final Assessment (Post Test)
                    </h3>

                    <p class="mt-1 text-xs text-muted-foreground">
                        Evaluasi akhir untuk mendapatkan sertifikat pelatihan
                        ini.
                    </p>
                </div>
            </div>

            <!-- Aksi Post Test -->
            <div
                class="flex shrink-0 flex-wrap items-center gap-2 sm:justify-end"
            >
                <span
                    class="rounded-full bg-red-500/15 px-2.5 py-1 text-xs font-semibold text-red-600 dark:text-red-400"
                >
                    Wajib
                </span>

                <Button
                    type="button"
                    variant="blue"
                    size="sm"
                    @click="showPostTestModal = true"
                >
                    <IconPlus class="mr-1 size-4" />
                    Kelola Post Test
                </Button>

                <IconChevronRight
                    class="hidden size-4 text-muted-foreground sm:block"
                />
            </div>
        </div>

        <CreatePostTest
            v-model:open="showPostTestModal"
            :training="training"
            :module="null"
            assessment-type="POST_TEST"
            :post-test="postTest"
            :processing="processing"
            @save="handlePostTestSave"
        />

        <!-- Modal Tambah Materi -->
        <CreateTrainingMaterials
            v-model:open="openModalMaterials"
            :mode="'create'"
            :module="selectedMaterialModule"
            :material="selectedMaterial"
        />
    </div>
</template>
