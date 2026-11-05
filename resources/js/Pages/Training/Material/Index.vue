<script setup>
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import EmptyState from "@/Components/EmptyState.vue";
import CreatePostTest from "../Quiz/Components/CreatePostTest.vue";
import { Button } from "@/Components/ui/button";

import {
    IconBook,
    IconPlus,
    IconVideo,
    IconClipboardCheck,
    IconFileText,
    IconEye,
    IconPencil,
    IconTrash,
} from "@tabler/icons-vue";

const props = defineProps({
    module: {
        type: Object,
        required: true,
    },
    quizzes: {
        type: Array,
        default: () => [],
    },
    training: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([
    "add-material",
    "view-material",
    "edit-material",
    "delete-material",
    "add-quiz",
    "view-quiz",
    "edit-quiz",
    "delete-quiz",
]);

const materials = computed(() => props.module?.materials ?? []);
const quizzes = computed(() => props.quizzes);

const openMaterialModal = () => {
    emit("add-material", props.module);
};

const getMaterialIcon = (type) => {
    switch (type) {
        case "VIDEO":
            return IconVideo;
        case "QUIZ":
            return IconClipboardCheck;
        default:
            return IconFileText;
    }
};

const formatMaterialType = (type) => {
    const types = {
        TEXT: "TEXT",
        VIDEO: "VIDEO",
        FILE: "FILE",
        LINK: "LINK",
        QUIZ: "QUIZ",
    };

    return types[type] ?? type ?? "MATERI";
};

const formatDuration = (duration) => {
    return `${duration ?? 0} menit`;
};

const showQuizModal = ref(false);
const processing = ref(false);
const selectedQuiz = ref(null);

const openQuizModal = () => {
    selectedQuiz.value = null;
    showQuizModal.value = true;
};

const handleQuizSave = (payload) => {
    processing.value = true;

    router.post(route("quizzes.store"), payload, {
        onSuccess: () => {
            console.log("[QUIZ] Berhasil disimpan.");
            showQuizModal.value = false;
            selectedQuiz.value = null;
        },
        onError: (errors) => {
            console.error("[QUIZ] Validasi gagal:", errors);
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <div class="border-t bg-muted/20 px-4 py-4 sm:px-5">
        <!-- Header Materi -->
        <div
            class="mb-3 flex flex-col justify-between gap-3 sm:flex-row sm:items-center"
        >
            <div>
                <h3 class="text-sm font-semibold">Training Materials</h3>

                <p class="mt-1 text-xs text-muted-foreground">
                    Daftar materi dalam modul {{ module.title }}.
                </p>
            </div>

            <Button size="sm" class="shrink-0" @click="openMaterialModal">
                <IconPlus class="mr-1 size-4" />
                Tambah Materi
            </Button>
        </div>

        <!-- Daftar Materi -->
        <EmptyState
            v-if="materials.length === 0"
            :icon="IconBook"
            title="Belum Ada Materi"
            subtitle="Tambahkan materi pembelajaran untuk modul ini."
        />

        <div v-else class="flex flex-col gap-2">
            <div
                v-for="material in materials"
                :key="material.id"
                class="flex items-center gap-3 rounded-xl border bg-background px-3 py-2.5 transition-colors hover:bg-muted/30 sm:px-4"
            >
                <!-- Ikon Materi -->
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground"
                >
                    <component
                        :is="getMaterialIcon(material.type)"
                        class="size-5"
                    />
                </div>

                <!-- Informasi Materi -->
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">
                        {{ material.title ?? "-" }}
                    </p>

                    <p class="mt-1 text-xs text-muted-foreground">
                        {{ formatMaterialType(material.type) }}
                        <span class="mx-1">·</span>
                        {{ formatDuration(material.duration_minutes) }}
                    </p>
                </div>

                <!-- Aksi Materi -->
                <div class="flex shrink-0 items-center gap-1">
                    <Button
                        type="button"
                        variant="blue"
                        size="icon"
                        class="size-8"
                        title="Lihat materi"
                        @click="emit('view-material', material)"
                    >
                        <IconEye class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="blue"
                        size="icon"
                        class="size-8"
                        title="Edit materi"
                        @click="emit('edit-material', module, material)"
                    >
                        <IconPencil class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="destructive"
                        size="icon"
                        class="size-8"
                        title="Hapus materi"
                        @click="emit('delete-material', material)"
                    >
                        <IconTrash class="size-4" />
                    </Button>
                </div>
            </div>
        </div>

        <!-- Pemisah Section -->
        <div class="my-4 border-t"></div>

        <!-- Header Quiz -->
        <div
            class="mb-3 flex flex-col justify-between gap-3 sm:flex-row sm:items-center"
        >
            <div>
                <h3 class="text-sm font-semibold">Quiz dalam Modul</h3>

                <p class="mt-1 text-xs text-muted-foreground">
                    Daftar quiz pada modul {{ module.title }}.
                </p>
            </div>

            <Button
                type="button"
                variant="outline"
                size="sm"
                class="shrink-0"
                @click="openQuizModal"
            >
                <IconPlus class="mr-1 size-4" />
                Tambah Quiz
            </Button>
        </div>

        <!-- Quiz Kosong -->
        <EmptyState
            v-if="quizzes.length === 0"
            :icon="IconClipboardCheck"
            title="Belum Ada Quiz"
            subtitle="Tambahkan quiz untuk menguji pemahaman peserta pada modul ini."
        />

        <!-- Daftar Quiz -->
        <div v-else class="flex flex-col gap-2">
            <div
                v-for="quiz in quizzes"
                :key="quiz.id"
                class="flex items-center gap-3 rounded-xl border bg-background px-3 py-2.5 transition-colors hover:bg-muted/30 sm:px-4"
            >
                <!-- Ikon Quiz -->
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground"
                >
                    <IconFileText class="size-5" />
                </div>

                <!-- Informasi Quiz -->
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">
                        {{ quiz.title ?? "-" }}
                    </p>

                    <p class="mt-1 text-xs text-muted-foreground">
                        {{ quiz.questions_count ?? 0 }} soal
                        <span class="mx-1">·</span>
                        {{ quiz.passing_score ?? 0 }}%
                        <span class="mx-1">·</span>
                        {{ quiz.duration_minutes ?? 0 }} menit
                    </p>
                </div>

                <!-- Status Quiz -->
                <span
                    class="hidden shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold sm:inline-flex"
                    :class="
                        quiz.status === 'PUBLISHED'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300'
                            : 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300'
                    "
                >
                    {{
                        quiz.status === "PUBLISHED"
                            ? "Published"
                            : (quiz.status ?? "Draft")
                    }}
                </span>

                <!-- Aksi Quiz -->
                <div class="flex shrink-0 items-center gap-1">
                    <Button
                        type="button"
                        variant="blue"
                        size="icon"
                        class="size-8"
                        title="Lihat quiz"
                        @click="emit('view-quiz', quiz)"
                    >
                        <IconEye class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="blue"
                        size="icon"
                        class="size-8"
                        title="Edit quiz"
                        @click="emit('edit-quiz', module, quiz)"
                    >
                        <IconPencil class="size-4" />
                    </Button>

                    <Button
                        type="button"
                        variant="destructive"
                        size="icon"
                        class="size-8"
                        title="Hapus quiz"
                        @click="emit('delete-quiz', quiz)"
                    >
                        <IconTrash class="size-4" />
                    </Button>
                </div>
            </div>
        </div>

        <CreatePostTest
            v-model:open="showQuizModal"
            :training="training"
            :module="module"
            assessment-type="MODULE_QUIZ"
            :post-test="selectedQuiz"
            :processing="processing"
            @save="handleQuizSave"
        />
    </div>
</template>
