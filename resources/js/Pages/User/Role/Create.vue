<script setup>
import { watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

import { flashMessage } from "@/lib/utils";
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

const page = usePage();

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },

    mode: {
        type: String,
        default: "create",
    },

    role: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    name: "",
    slug: "",
    description: "",
    is_active: true,
});

/*
|--------------------------------------------------------------------------
| Watch Role
|--------------------------------------------------------------------------
*/

watch(
    () => props.role,
    (role) => {
        form.clearErrors();

        if (props.mode === "edit" && role) {
            form.name = role.name ?? "";
            form.slug = role.slug ?? "";
            form.description = role.description ?? "";
            form.is_active = role.is_active ?? true;
        } else {
            form.reset();
            form.is_active = true;
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
        form.post(route("roles.store"), {
            preserveScroll: true,

            onSuccess: (page) => {
                const flash = page.props.flash_message;

                if (flash?.message) {
                    toast[flash.type ?? "success"](flash.message);
                }

                form.reset();
                form.is_active = true;
                emit("update:open", false);
            },
        });

        return;
    }

    form.put(route("roles.update", props.role.id), {
        preserveScroll: true,

        onSuccess: (page) => {
            const flash = flashMessage(page);

            if (flash) {
                toast[flash.type](flash.message);
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
                    {{ mode === "create" ? "Tambah Peran" : "Edit Peran" }}
                </DialogTitle>

                <DialogDescription>
                    {{
                        mode === "create"
                            ? "Lengkapi informasi peran yang akan digunakan dalam sistem."
                            : "Perbarui informasi peran sesuai kebutuhan."
                    }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Name -->
                <div class="space-y-2">
                    <Label for="name"> Nama Peran </Label>

                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Contoh: LMS Admin"
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <!-- Slug -->
                <div class="space-y-2">
                    <Label for="slug"> Slug </Label>

                    <Input
                        id="slug"
                        v-model="form.slug"
                        placeholder="Contoh: lms_admin"
                    />

                    <p class="text-xs text-muted-foreground">
                        Gunakan huruf kecil, angka, tanda hubung (-), atau
                        underscore (_).
                    </p>

                    <InputError :message="form.errors.slug" />
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <Label for="description"> Deskripsi </Label>

                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Masukkan deskripsi peran..."
                        rows="4"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Status -->
                <div
                    class="flex items-center justify-between rounded-lg border p-4"
                >
                    <div class="space-y-1">
                        <Label> Status Peran </Label>

                        <p class="text-sm text-muted-foreground">
                            Aktifkan peran agar dapat digunakan untuk pengguna.
                        </p>
                    </div>

                    <Switch v-model:checked="form.is_active" />
                </div>

                <InputError :message="form.errors.is_active" />

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
