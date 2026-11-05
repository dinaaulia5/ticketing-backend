<script setup>
import { computed, reactive, ref, watch } from "vue";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Label } from "@/Components/ui/label";

import {
    IconArrowLeft,
    IconArrowRight,
    IconCheck,
    IconClipboardCheck,
    IconClock,
    IconEye,
    IconFileText,
    IconPlus,
    IconSettings,
    IconTrash,
    IconTrophy,
} from "@tabler/icons-vue";

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    training: {
        type: Object,
        default: () => ({}),
    },
    module: {
        type: Object,
        default: null,
    },
    assessmentType: {
        type: String,
        default: "POST_TEST",
    },
    postTest: {
        type: Object,
        default: null,
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:open", "save"]);

const currentStep = ref(1);

const steps = [
    {
        title: "Informasi Quiz",
        description: "Pengaturan dasar",
        icon: IconFileText,
    },
    {
        title: "Daftar Soal",
        description: "Kelola pertanyaan",
        icon: IconClipboardCheck,
    },
    { title: "Preview", description: "Pratinjau quiz", icon: IconEye },
    {
        title: "Publikasi",
        description: "Review dan selesaikan",
        icon: IconCheck,
    },
];

const isEdit = computed(() => Boolean(props.postTest?.id));

const defaultForm = () => ({
    title: "",
    description: "",
    training_id: props.training?.id ?? "",
    assessment_type: "POST_TEST",
    passing_score: 70,
    duration_minutes: 30,
    max_attempts: 1,
    shuffle_questions: false,
    shuffle_options: false,
    show_explanation_policy: "AFTER_SUBMIT",
    is_published: false,
});

const form = reactive(defaultForm());
const questions = ref([]);

const totalPoints = computed(() =>
    questions.value.reduce(
        (total, question) => total + Number(question.points || 0),
        0,
    ),
);

const resetForm = () => {
    Object.assign(form, defaultForm());

    questions.value = [];
    currentStep.value = 1;

    if (props.postTest) {
        Object.assign(form, {
            ...props.postTest,
            training_id: props.training?.id ?? props.postTest.training_id,
            assessment_type: props.assessmentType,
        });

        questions.value = props.postTest.questions
            ? structuredClone(props.postTest.questions)
            : [];
    }
};

// Letakkan setelah defineProps yang sudah ada.
watch(
    () => props.training,
    (value) => {
        console.log("[POST TEST MODAL] Props training:", value);
        console.log("[POST TEST MODAL] Training ID:", value?.id);
        console.log("[POST TEST MODAL] Training title:", value?.title);
    },
    { immediate: true, deep: true },
);

watch(
    () => props.open,
    (isOpen) => {
        console.log("[POST TEST MODAL] Props open:", isOpen);
    },
);

const closeModal = () => {
    if (props.processing) return;
    emit("update:open", false);
};

const addQuestion = () => {
    questions.value.push({
        localId: `${Date.now()}-${questions.value.length}`,
        question: "",
        type: "SINGLE_CHOICE",
        difficulty: "EASY",
        explanation: "",
        points: 10,
        options: [
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
            { text: "", is_correct: false },
        ],
    });
};

const removeQuestion = (index) => {
    questions.value.splice(index, 1);
};

const addOption = (question) => {
    question.options.push({ text: "", is_correct: false });
};

const removeOption = (question, index) => {
    if (question.options.length > 2) {
        question.options.splice(index, 1);
    }
};

const setCorrectOption = (question, selectedIndex) => {
    question.options.forEach((option, index) => {
        option.is_correct = index === selectedIndex;
    });
};

const validateQuestions = () =>
    questions.value.every((question) => {
        const validQuestion = question.question.trim().length > 0;
        const validOptions =
            question.options.length >= 2 &&
            question.options.every((option) => option.text.trim().length > 0);
        const hasCorrectAnswer = question.options.some(
            (option) => option.is_correct,
        );

        return validQuestion && validOptions && hasCorrectAnswer;
    });

const nextStep = () => {
    if (currentStep.value === 1) {
        if (
            !form.title.trim() ||
            !form.training_id ||
            form.passing_score < 0 ||
            form.passing_score > 100 ||
            form.duration_minutes < 1 ||
            form.max_attempts < 1
        ) {
            window.alert("Lengkapi informasi Post Test dengan benar.");
            return;
        }
    }

    if (currentStep.value === 2) {
        if (!questions.value.length || !validateQuestions()) {
            window.alert(
                "Tambahkan soal dan lengkapi semua pilihan jawaban serta jawaban benarnya.",
            );
            return;
        }
    }

    if (currentStep.value < steps.length) {
        currentStep.value++;
    }
};

const previousStep = () => {
    if (currentStep.value > 1) currentStep.value--;
};

const saveQuiz = (publish = false) => {
    if (!form.title.trim() || !props.training?.id) {
        window.alert("Judul dan training wajib diisi.");
        currentStep.value = 1;
        return;
    }

    if (props.assessmentType === "MODULE_QUIZ" && !props.module?.id) {
        window.alert("Modul training belum dipilih.");
        return;
    }

    if (!questions.value.length || !validateQuestions()) {
        currentStep.value = 2;
        window.alert(
            "Lengkapi minimal satu soal beserta pilihan jawaban dan jawaban benarnya.",
        );
        return;
    }

    const payload = {
        ...form,

        // Training selalu menjadi induk Quiz maupun Post Test.
        training_id: props.training.id,

        // Quiz modul menggunakan ID modul, Post Test menggunakan null.
        module_id:
            props.assessmentType === "MODULE_QUIZ" ? props.module.id : null,

        assessment_type: props.assessmentType,
        is_published: publish,

        questions: questions.value.map((question, index) => ({
            question: question.question.trim(),
            type: question.type,
            difficulty: question.difficulty,
            explanation: question.explanation || null,
            points: Number(question.points),
            sequence: index + 1,

            options: question.options.map((option, optionIndex) => ({
                option_text: option.text.trim(),
                is_correct: option.is_correct,
                sequence: optionIndex + 1,
            })),
        })),
    };

    console.log("[QUIZ] Payload yang dikirim:", payload);

    emit("save", payload);
};
</script>

<template>
    <Dialog
        :open="open"
        @update:open="(value) => !processing && emit('update:open', value)"
    >
        <DialogContent
            class="flex max-h-[92vh] flex-col gap-0 overflow-hidden p-0 sm:max-w-5xl"
        >
            <!-- Header -->
            <DialogHeader class="shrink-0 border-b p-5 sm:p-6">
                <p class="text-sm font-semibold">
                    {{ props.training?.title ?? "Training belum dipilih" }}
                </p>
                <div class="flex items-start gap-3">
                    <div
                        class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-600"
                    >
                        <IconClipboardCheck class="size-6" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <DialogTitle>
                            {{ isEdit ? "Edit Post Test" : "Buat Post Test" }}
                        </DialogTitle>

                        <DialogDescription class="mt-1">
                            {{ training?.title ?? "Training" }}
                            <span class="mx-1">·</span>
                            Langkah {{ currentStep }} dari {{ steps.length }}
                        </DialogDescription>
                    </div>
                </div>

                <!-- Stepper -->
                <div class="mt-5 grid grid-cols-4 gap-2">
                    <button
                        v-for="(step, index) in steps"
                        :key="step.title"
                        type="button"
                        class="flex min-w-0 flex-col items-center gap-2 rounded-lg p-2 text-center transition-colors sm:flex-row sm:text-left"
                        :class="
                            currentStep === index + 1
                                ? 'bg-cyan-500/10 text-cyan-700 dark:text-cyan-400'
                                : 'text-muted-foreground hover:bg-muted/60'
                        "
                        :disabled="processing || index + 1 > currentStep"
                        @click="currentStep = index + 1"
                    >
                        <span
                            class="flex size-8 shrink-0 items-center justify-center rounded-full border"
                            :class="
                                currentStep === index + 1
                                    ? 'border-cyan-500 bg-cyan-500 text-white'
                                    : currentStep > index + 1
                                      ? 'border-emerald-500 bg-emerald-500 text-white'
                                      : 'bg-muted'
                            "
                        >
                            <IconCheck
                                v-if="currentStep > index + 1"
                                class="size-4"
                            />
                            <component v-else :is="step.icon" class="size-4" />
                        </span>

                        <span class="min-w-0">
                            <span
                                class="block text-xs font-semibold sm:text-sm"
                            >
                                {{ step.title }}
                            </span>
                            <span class="hidden text-xs opacity-75 lg:block">
                                {{ step.description }}
                            </span>
                        </span>
                    </button>
                </div>
            </DialogHeader>

            <!-- Body -->
            <div class="min-h-0 flex-1 overflow-y-auto p-5 sm:p-6">
                <!-- STEP 1 -->
                <section v-if="currentStep === 1" class="space-y-6">
                    <div>
                        <h3 class="font-semibold">Informasi Post Test</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Atur konfigurasi evaluasi akhir training.
                        </p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="space-y-2 sm:col-span-2">
                            <Label for="post-title">Judul Post Test *</Label>
                            <Input
                                id="post-title"
                                v-model="form.title"
                                maxlength="255"
                                placeholder="Contoh: Post Test Leadership"
                                :disabled="processing"
                            />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <Label for="post-description">Deskripsi</Label>
                            <Textarea
                                id="post-description"
                                v-model="form.description"
                                rows="3"
                                placeholder="Jelaskan tujuan evaluasi akhir..."
                                :disabled="processing"
                            />
                        </div>

                        <div class="space-y-2">
                            <Label>Training</Label>
                            <div
                                class="flex min-h-10 items-center rounded-md border bg-muted/40 px-3 py-2 text-sm"
                            >
                                {{
                                    training?.title ?? "Training belum dipilih"
                                }}
                            </div>
                            <p class="text-xs text-muted-foreground">
                                Post Test terhubung ke training, bukan modul.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label>Tipe Assessment</Label>
                            <div
                                class="flex h-10 items-center rounded-md border bg-muted/40 px-3 text-sm"
                            >
                                Final Assessment · Post Test
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="post-passing"
                                >Passing Score (%) *</Label
                            >
                            <div class="relative">
                                <IconTrophy
                                    class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input
                                    id="post-passing"
                                    v-model.number="form.passing_score"
                                    class="pl-9"
                                    type="number"
                                    min="0"
                                    max="100"
                                    :disabled="processing"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="post-duration">Durasi (menit) *</Label>
                            <div class="relative">
                                <IconClock
                                    class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input
                                    id="post-duration"
                                    v-model.number="form.duration_minutes"
                                    class="pl-9"
                                    type="number"
                                    min="1"
                                    :disabled="processing"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="post-attempts">Maks. Percobaan *</Label>
                            <Input
                                id="post-attempts"
                                v-model.number="form.max_attempts"
                                type="number"
                                min="1"
                                :disabled="processing"
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="post-explanation"
                                >Pembahasan Jawaban</Label
                            >
                            <select
                                id="post-explanation"
                                v-model="form.show_explanation_policy"
                                class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                :disabled="processing"
                            >
                                <option value="IMMEDIATE">
                                    Setelah setiap soal dijawab
                                </option>
                                <option value="AFTER_SUBMIT">
                                    Setelah quiz dikumpulkan
                                </option>
                                <option value="NEVER">Tidak ditampilkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3 border-t pt-5">
                        <h3 class="text-sm font-semibold">Pengaturan Quiz</h3>

                        <label
                            class="flex cursor-pointer items-center justify-between gap-4 rounded-lg border p-3"
                        >
                            <span class="flex items-start gap-3">
                                <IconSettings
                                    class="mt-0.5 size-4 text-muted-foreground"
                                />
                                <span>
                                    <span class="block text-sm font-medium"
                                        >Acak urutan soal</span
                                    >
                                    <span class="text-xs text-muted-foreground"
                                        >Urutan pertanyaan diacak.</span
                                    >
                                </span>
                            </span>
                            <input
                                v-model="form.shuffle_questions"
                                type="checkbox"
                                class="size-4 accent-cyan-600"
                                :disabled="processing"
                            />
                        </label>

                        <label
                            class="flex cursor-pointer items-center justify-between gap-4 rounded-lg border p-3"
                        >
                            <span class="flex items-start gap-3">
                                <IconSettings
                                    class="mt-0.5 size-4 text-muted-foreground"
                                />
                                <span>
                                    <span class="block text-sm font-medium"
                                        >Acak pilihan jawaban</span
                                    >
                                    <span class="text-xs text-muted-foreground"
                                        >Posisi pilihan jawaban diacak.</span
                                    >
                                </span>
                            </span>
                            <input
                                v-model="form.shuffle_options"
                                type="checkbox"
                                class="size-4 accent-cyan-600"
                                :disabled="processing"
                            />
                        </label>
                    </div>
                </section>

                <!-- STEP 2 -->
                <section v-else-if="currentStep === 2" class="space-y-5">
                    <div
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <h3 class="font-semibold">Daftar Soal</h3>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Tambahkan pertanyaan dan pilihan jawaban.
                            </p>
                        </div>
                        <Button
                            type="button"
                            variant="blue"
                            @click="addQuestion"
                        >
                            <IconPlus class="mr-2 size-4" />
                            Tambah Soal
                        </Button>
                    </div>

                    <div
                        v-if="!questions.length"
                        class="rounded-xl border border-dashed px-4 py-10 text-center"
                    >
                        <IconClipboardCheck
                            class="mx-auto mb-3 size-10 text-muted-foreground"
                        />
                        <p class="font-medium">Belum ada soal</p>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Tambahkan soal pertama untuk Post Test ini.
                        </p>
                        <Button
                            class="mt-4"
                            type="button"
                            variant="blue"
                            @click="addQuestion"
                        >
                            <IconPlus class="mr-2 size-4" />
                            Buat Soal Pertama
                        </Button>
                    </div>

                    <div
                        v-for="(question, index) in questions"
                        :key="question.localId ?? index"
                        class="space-y-4 rounded-xl border p-4"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold">
                                Soal {{ index + 1 }}
                            </h4>
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                @click="removeQuestion(index)"
                            >
                                <IconTrash class="size-4 text-destructive" />
                            </Button>
                        </div>

                        <div class="space-y-2">
                            <Label>Pertanyaan *</Label>
                            <Textarea
                                v-model="question.question"
                                rows="3"
                                placeholder="Tulis pertanyaan..."
                            />
                        </div>

                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="space-y-2">
                                <Label>Tipe Soal</Label>
                                <select
                                    v-model="question.type"
                                    class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    <option value="SINGLE_CHOICE">
                                        Pilihan Ganda
                                    </option>
                                    <option value="MULTIPLE_CHOICE">
                                        Pilihan Ganda Kompleks
                                    </option>
                                    <option value="TRUE_FALSE">
                                        Benar / Salah
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <Label>Poin</Label>
                                <Input
                                    v-model.number="question.points"
                                    type="number"
                                    min="1"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label>Kesulitan</Label>
                                <select
                                    v-model="question.difficulty"
                                    class="h-10 w-full rounded-md border bg-background px-3 text-sm"
                                >
                                    <option value="EASY">Mudah</option>
                                    <option value="MEDIUM">Sedang</option>
                                    <option value="HARD">Sulit</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <Label>Pilihan Jawaban *</Label>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    @click="addOption(question)"
                                >
                                    <IconPlus class="mr-1 size-4" />
                                    Tambah Pilihan
                                </Button>
                            </div>

                            <div
                                v-for="(
                                    option, optionIndex
                                ) in question.options"
                                :key="optionIndex"
                                class="flex items-center gap-2"
                            >
                                <input
                                    :name="`correct-${question.localId ?? index}`"
                                    type="radio"
                                    :checked="option.is_correct"
                                    class="size-4 accent-cyan-600"
                                    @change="
                                        setCorrectOption(question, optionIndex)
                                    "
                                />
                                <span class="w-5 text-xs text-muted-foreground">
                                    {{ String.fromCharCode(65 + optionIndex) }}
                                </span>
                                <Input
                                    v-model="option.text"
                                    class="flex-1"
                                    :placeholder="`Pilihan ${optionIndex + 1}`"
                                />
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    :disabled="question.options.length <= 2"
                                    @click="removeOption(question, optionIndex)"
                                >
                                    <IconTrash
                                        class="size-4 text-destructive"
                                    />
                                </Button>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                Pilih radio untuk menentukan jawaban benar.
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label>Pembahasan Jawaban</Label>
                            <Textarea
                                v-model="question.explanation"
                                rows="2"
                                placeholder="Jelaskan jawaban yang benar..."
                            />
                        </div>
                    </div>

                    <div
                        v-if="questions.length"
                        class="rounded-lg bg-muted/50 p-3 text-sm"
                    >
                        Jumlah soal: <strong>{{ questions.length }}</strong>
                        <span class="mx-2">·</span>
                        Total bobot: <strong>{{ totalPoints }} poin</strong>
                    </div>
                </section>

                <!-- STEP 3 -->
                <section v-else-if="currentStep === 3" class="space-y-5">
                    <div>
                        <h3 class="font-semibold">Preview Quiz</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Periksa informasi dan soal sebelum dipublikasikan.
                        </p>
                    </div>

                    <div class="rounded-xl border p-4">
                        <h3 class="text-lg font-semibold">{{ form.title }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ form.description || "Tidak ada deskripsi." }}
                        </p>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            <div class="rounded-lg bg-muted/50 p-3">
                                <p class="text-xs text-muted-foreground">
                                    Jumlah Soal
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ questions.length }}
                                </p>
                            </div>
                            <div class="rounded-lg bg-muted/50 p-3">
                                <p class="text-xs text-muted-foreground">
                                    Passing Score
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ form.passing_score }}%
                                </p>
                            </div>
                            <div class="rounded-lg bg-muted/50 p-3">
                                <p class="text-xs text-muted-foreground">
                                    Durasi
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ form.duration_minutes }} menit
                                </p>
                            </div>
                            <div class="rounded-lg bg-muted/50 p-3">
                                <p class="text-xs text-muted-foreground">
                                    Maks. Percobaan
                                </p>
                                <p class="mt-1 font-semibold">
                                    {{ form.max_attempts }} kali
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-for="(question, index) in questions"
                        :key="question.localId ?? index"
                        class="space-y-3 rounded-xl border p-4"
                    >
                        <p class="text-xs font-semibold text-cyan-600">
                            PERTANYAAN {{ index + 1 }}
                        </p>
                        <p class="font-medium">{{ question.question }}</p>

                        <div
                            v-for="(option, optionIndex) in question.options"
                            :key="optionIndex"
                            class="flex items-center gap-2 rounded-lg border p-3 text-sm"
                            :class="
                                option.is_correct
                                    ? 'border-emerald-500/50 bg-emerald-500/5'
                                    : ''
                            "
                        >
                            <span class="text-muted-foreground"
                                >{{
                                    String.fromCharCode(65 + optionIndex)
                                }}.</span
                            >
                            <span class="flex-1">{{ option.text }}</span>
                            <IconCheck
                                v-if="option.is_correct"
                                class="size-4 text-emerald-600"
                            />
                        </div>
                    </div>
                </section>

                <!-- STEP 4 -->
                <section v-else class="space-y-5">
                    <div>
                        <h3 class="font-semibold">Review & Publikasi</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Periksa kembali konfigurasi sebelum menyelesaikan
                            Post Test.
                        </p>
                    </div>

                    <div class="space-y-3 rounded-xl border p-4">
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-muted-foreground">Judul</span>
                            <span class="text-right font-medium">{{
                                form.title
                            }}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-muted-foreground">Training</span>
                            <span class="text-right font-medium">{{
                                training?.title ?? "-"
                            }}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-muted-foreground">Tipe</span>
                            <span class="font-medium">Post Test</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-muted-foreground"
                                >Jumlah Soal</span
                            >
                            <span class="font-medium">{{
                                questions.length
                            }}</span>
                        </div>
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-muted-foreground"
                                >Total Bobot</span
                            >
                            <span class="font-medium"
                                >{{ totalPoints }} poin</span
                            >
                        </div>
                    </div>

                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-lg border p-4"
                    >
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            class="mt-1 size-4 accent-cyan-600"
                        />
                        <span>
                            <span class="block text-sm font-medium"
                                >Publikasikan setelah disimpan</span
                            >
                            <span
                                class="mt-1 block text-xs text-muted-foreground"
                            >
                                Jika tidak dipilih, Post Test disimpan sebagai
                                Draft.
                            </span>
                        </span>
                    </label>
                </section>
            </div>

            <!-- Footer -->
            <DialogFooter
                class="shrink-0 flex-col gap-2 border-t p-4 sm:flex-row sm:justify-between sm:p-5"
            >
                <Button
                    type="button"
                    variant="outline"
                    :disabled="currentStep === 1 || processing"
                    @click="previousStep"
                >
                    <IconArrowLeft class="mr-2 size-4" />
                    Sebelumnya
                </Button>

                <div class="flex flex-col-reverse gap-2 sm:flex-row">
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="processing"
                        @click="closeModal"
                    >
                        Batal
                    </Button>

                    <Button
                        v-if="currentStep < 4"
                        type="button"
                        variant="blue"
                        :disabled="processing"
                        @click="nextStep"
                    >
                        {{ currentStep === 1 ? "Simpan & Lanjut" : "Lanjut" }}
                        <IconArrowRight class="ml-2 size-4" />
                    </Button>

                    <template v-else>
                        <Button
                            type="button"
                            variant="outline"
                            :disabled="processing"
                            @click="saveQuiz(false)"
                        >
                            Simpan Draft
                        </Button>

                        <Button
                            type="button"
                            variant="blue"
                            :disabled="processing"
                            @click="saveQuiz(true)"
                        >
                            <IconCheck class="mr-2 size-4" />
                            {{ processing ? "Menyimpan..." : "Publikasikan" }}
                        </Button>
                    </template>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
