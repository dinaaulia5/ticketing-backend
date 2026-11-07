<script setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

import { flashMessage } from "@/lib/utils";
import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";

import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    mode: {
        type: String,
        default: "create",
    },
    subsystem: {
        type: Object,
        default: null,
    },
    categoryId: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    category_id: props.categoryId,
    code: "",
    name: "",
    description: "",
});

/*
|--------------------------------------------------------------------------
| Watch Category
|--------------------------------------------------------------------------
*/

watch(
    [() => props.subsystem, () => props.mode, () => props.open],
    ([subsystem, mode, open]) => {
        if (!open) return;

        form.clearErrors();

        if (mode === "edit" && subsystem) {
            form.code = subsystem.code ?? "";
            form.name = subsystem.name ?? "";
            form.description = subsystem.description ?? "";
        } else {
            form.reset();
            form.clearErrors();
        }
    },
    {
        immediate: true,
    },
);

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    if (props.mode === "create") {
        form.post(route("subsystems.store"), {
            preserveScroll: true,

            onSuccess: (page) => {
                const flash = flashMessage(page);

                if (flash) {
                    toast[flash.type ?? "success"](flash.message);
                }

                form.reset();
                emit("update:open", false);
            },
        });

        return;
    }

    form.put(route("subsystems.update", props.subsystem.id), {
        preserveScroll: true,

        onSuccess: (page) => {
            const flash = flashMessage(page);

            if (flash) {
                toast[flash.type ?? "success"](flash.message);
            }

            emit("update:open", false);
        },
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{
                        mode === "create"
                            ? "Tambah Subsystem"
                            : "Edit Subsystem"
                    }}
                </DialogTitle>

                <DialogDescription>
                    {{
                        mode === "create"
                            ? "Lengkapi informasi Subsystem layanan IT yang akan ditambahkan."
                            : "Perbarui informasi Subsystem sesuai kebutuhan."
                    }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Code -->
                <div class="space-y-2">
                    <Label for="code">Kode Subsystem</Label>

                    <Input
                        id="code"
                        v-model="form.code"
                        placeholder="Contoh: HW"
                        maxlength="20"
                    />

                    <p class="text-xs text-muted-foreground">
                        Gunakan kode unik untuk setiap Subsystem.
                    </p>

                    <InputError :message="form.errors.code" />
                </div>

                <!-- Name -->
                <div class="space-y-2">
                    <Label for="name">Nama Subsystem</Label>

                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Contoh: Hardware"
                        maxlength="100"
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <Label for="description">Deskripsi</Label>

                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Masukkan deskripsi Subsystem..."
                        rows="4"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Action -->
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
