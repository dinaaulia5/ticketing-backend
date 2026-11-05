<script setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";

import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { Switch } from "@/Components/ui/switch";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";

const props = defineProps({
    open: Boolean,

    mode: {
        type: String,
        default: "create",
    },

    module: {
        type: Object,
        required: true,
    },

    material: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    title: "",
    description: "",
    type: "PDF",
    file_path: "",
    file_name: "",
    file_size: null,
    mime_type: "",
    video_url: "",
    sequence: 1,
    duration_minutes: null,
    is_preview: false,
});

watch(
    () => [props.open, props.mode, props.material],
    () => {
        form.clearErrors();

        if (props.mode === "edit" && props.material) {
            form.title = props.material.title ?? "";
            form.description = props.material.description ?? "";
            form.type = props.material.type ?? "PDF";
            form.file_path = props.material.file_path ?? "";
            form.file_name = props.material.file_name ?? "";
            form.file_size = props.material.file_size ?? null;
            form.mime_type = props.material.mime_type ?? "";
            form.video_url = props.material.video_url ?? "";
            form.sequence = props.material.sequence ?? 1;
            form.duration_minutes = props.material.duration_minutes ?? null;
            form.is_preview = props.material.is_preview ?? false;
        } else {
            form.reset();
            form.type = "PDF";
            form.sequence = 1;
            form.is_preview = false;
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.mode === "create") {
        form.post(route("training-modules.materials.store", props.module.id), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit("update:open", false);
            },
        });
    } else {
        form.put(
            route("training-modules.materials.update", {
                trainingModule: props.module.id,
                trainingMaterial: props.material.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    emit("update:open", false);
                },
            },
        );
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-xl max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>
                    {{
                        mode === "create"
                            ? "Tambah Training Material"
                            : "Edit Training Material"
                    }}
                </DialogTitle>

                <DialogDescription>
                    Kelola materi untuk modul
                    <span class="font-medium text-foreground">
                        {{ module.title }} </span
                    >.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Judul -->
                <div class="space-y-2">
                    <Label>Judul Materi</Label>
                    <Input
                        v-model="form.title"
                        placeholder="Contoh: Pengenalan Microsoft Excel"
                        required
                    />
                    <InputError :message="form.errors.title" />
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <Label>Deskripsi</Label>
                    <Textarea
                        v-model="form.description"
                        placeholder="Masukkan deskripsi materi..."
                        rows="3"
                    />
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Jenis Materi -->
                <div class="space-y-2">
                    <Label>Jenis Materi</Label>
                    <select
                        v-model="form.type"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                    >
                        <option value="PDF">PDF / Dokumen</option>
                        <option value="VIDEO">Video</option>
                        <option value="TEXT">Teks / Artikel</option>
                        <option value="LINK">Tautan</option>
                        <option value="QUIZ">Kuis</option>
                    </select>
                    <InputError :message="form.errors.type" />
                </div>

                <!-- URL Video -->
                <div
                    v-if="form.type === 'VIDEO' || form.type === 'LINK'"
                    class="space-y-2"
                >
                    <Label>
                        {{ form.type === "VIDEO" ? "URL Video" : "URL Tautan" }}
                    </Label>
                    <Input
                        v-model="form.video_url"
                        type="url"
                        placeholder="https://..."
                    />
                    <InputError :message="form.errors.video_url" />
                </div>

                <!-- Informasi File -->
                <div
                    v-if="form.type === 'PDF'"
                    class="space-y-3 rounded-lg border p-4"
                >
                    <p class="text-sm font-medium">Informasi File PDF</p>

                    <div class="space-y-2">
                        <Label>Path File</Label>
                        <Input
                            v-model="form.file_path"
                            placeholder="Contoh: materials/modul-1.pdf"
                        />
                        <InputError :message="form.errors.file_path" />
                    </div>

                    <div class="space-y-2">
                        <Label>Nama File</Label>
                        <Input
                            v-model="form.file_name"
                            placeholder="materi-excel.pdf"
                        />
                        <InputError :message="form.errors.file_name" />
                    </div>

                    <div class="space-y-2">
                        <Label>MIME Type</Label>
                        <Input
                            v-model="form.mime_type"
                            placeholder="application/pdf"
                        />
                        <InputError :message="form.errors.mime_type" />
                    </div>

                    <div class="space-y-2">
                        <Label>Ukuran File (byte)</Label>
                        <Input
                            v-model.number="form.file_size"
                            type="number"
                            min="0"
                            placeholder="Contoh: 1024000"
                        />
                        <InputError :message="form.errors.file_size" />
                    </div>
                </div>

                <!-- Urutan dan Durasi -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label>Urutan Materi</Label>
                        <Input
                            v-model.number="form.sequence"
                            type="number"
                            min="1"
                            required
                        />
                        <InputError :message="form.errors.sequence" />
                    </div>

                    <div class="space-y-2">
                        <Label>Durasi (Menit)</Label>
                        <Input
                            v-model.number="form.duration_minutes"
                            type="number"
                            min="0"
                            placeholder="Contoh: 30"
                        />
                        <InputError :message="form.errors.duration_minutes" />
                    </div>
                </div>

                <!-- Preview -->
                <div
                    class="flex items-center justify-between rounded-lg border p-4"
                >
                    <div class="space-y-1">
                        <Label>Materi Preview</Label>
                        <p class="text-sm text-muted-foreground">
                            Izinkan materi dilihat sebagai pratinjau.
                        </p>
                    </div>

                    <Switch v-model:checked="form.is_preview" />
                </div>

                <InputError :message="form.errors.is_preview" />

                <!-- Tombol -->
                <div class="flex justify-end gap-2 border-t pt-5">
                    <Button
                        type="button"
                        variant="outline"
                        @click="emit('update:open', false)"
                    >
                        Batal
                    </Button>

                    <Button
                        type="submit"
                        variant="emerald"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? "Menyimpan..."
                                : mode === "create"
                                  ? "Simpan Materi"
                                  : "Perbarui Materi"
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
