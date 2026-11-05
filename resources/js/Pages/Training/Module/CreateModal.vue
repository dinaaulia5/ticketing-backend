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
        default: null,
    },

    training: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    training_id: props.training.id,
    title: "",
    description: "",
    sequence: 1,
    duration_minutes: null,
    is_required: true,
    is_active: true,
});

watch(
    () => [props.open, props.mode, props.module, props.training.id],
    () => {
        form.clearErrors();

        if (props.mode === "edit" && props.module) {
            form.training_id = props.training.id;
            form.title = props.module.title ?? "";
            form.description = props.module.description ?? "";
            form.sequence = props.module.sequence ?? 1;
            form.duration_minutes = props.module.duration_minutes ?? null;
            form.is_required = props.module.is_required ?? true;
            form.is_active = props.module.is_active ?? true;
        } else {
            form.reset();
            form.training_id = props.training.id;
            form.sequence = 1;
            form.is_required = true;
            form.is_active = true;
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.mode === "create") {
        form.post(route("training-modules.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit("update:open", false);
            },
        });
    } else {
        form.put(route("training-modules.update", props.module.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit("update:open", false);
            },
        });
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{
                        mode === "create"
                            ? "Tambah Modul Pelatihan"
                            : "Edit Modul Pelatihan"
                    }}
                </DialogTitle>

                <DialogDescription>
                    Lengkapi informasi modul untuk pelatihan
                    <span class="font-medium text-foreground">
                        {{ training.title }} </span
                    >.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Nama Modul -->
                <div class="space-y-2">
                    <Label>Nama Modul</Label>

                    <Input
                        v-model="form.title"
                        placeholder="Contoh: Pengenalan Excel"
                        required
                    />

                    <InputError :message="form.errors.title" />
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <Label>Deskripsi</Label>

                    <Textarea
                        v-model="form.description"
                        placeholder="Masukkan deskripsi modul..."
                        rows="3"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Urutan dan Durasi -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label>Urutan Modul</Label>

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
                            min="1"
                            placeholder="Contoh: 60"
                        />

                        <InputError :message="form.errors.duration_minutes" />
                    </div>
                </div>

                <!-- Wajib Diikuti -->
                <div
                    class="flex items-center justify-between rounded-lg border p-4"
                >
                    <div class="space-y-1">
                        <Label>Modul Wajib</Label>

                        <p class="text-sm text-muted-foreground">
                            Peserta diwajibkan menyelesaikan modul ini.
                        </p>
                    </div>

                    <Switch v-model:checked="form.is_required" />
                </div>

                <InputError :message="form.errors.is_required" />

                <!-- Status -->
                <div
                    class="flex items-center justify-between rounded-lg border p-4"
                >
                    <div class="space-y-1">
                        <Label>Status Modul</Label>

                        <p class="text-sm text-muted-foreground">
                            Aktifkan modul agar dapat digunakan.
                        </p>
                    </div>

                    <Switch v-model:checked="form.is_active" />
                </div>

                <InputError :message="form.errors.is_active" />

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
                                  ? "Simpan"
                                  : "Perbarui"
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
