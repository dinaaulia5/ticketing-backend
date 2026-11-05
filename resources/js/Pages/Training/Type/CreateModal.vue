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

    category: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    code: "",
    name: "",
    description: "",
    is_active: true,
});

watch(
    () => props.category,
    (category) => {
        form.clearErrors();

        if (props.mode === "edit" && category) {
            form.code = category.code;
            form.name = category.name;
            form.description = category.description;
            form.is_active = category.is_active;
        } else {
            form.reset();
            form.is_active = true;
        }
    },
    { immediate: true },
);

const submit = () => {
    if (props.mode === "create") {
        form.post(route("training.types.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit("update:open", false);
            },
        });
    } else {
        form.put(route("training.types.update", props.category.id), {
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
                            ? "Tambah Tipe Pelatihan"
                            : "Edit Tipe Pelatihan"
                    }}
                </DialogTitle>

                <DialogDescription>
                    Lengkapi informasi Tipe pelatihan.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Code -->
                <div class="space-y-2">
                    <Label>Kode</Label>

                    <Input v-model="form.code" placeholder="Contoh : CAT001" />

                    <InputError :message="form.errors.code" />
                </div>

                <!-- Name -->
                <div class="space-y-2">
                    <Label>Nama Tipe</Label>

                    <Input
                        v-model="form.name"
                        placeholder="Contoh : Leadership"
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <Label>Deskripsi</Label>

                    <Textarea
                        v-model="form.description"
                        placeholder="Masukkan deskripsi Tipe..."
                        rows="4"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Status -->
                <div
                    class="flex items-center justify-between rounded-lg border p-4"
                >
                    <div>
                        <Label>Status</Label>
                        <p class="text-sm text-muted-foreground">
                            Aktifkan Tipe agar dapat digunakan.
                        </p>
                    </div>

                    <Switch v-model:checked="form.is_active" />
                </div>

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
                        {{ mode === "create" ? "Simpan" : "Perbarui" }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
