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
});

/*
|--------------------------------------------------------------------------
| Watch Category
|--------------------------------------------------------------------------
*/

watch(
    [() => props.category, () => props.mode, () => props.open],
    ([category, mode, open]) => {
        if (!open) return;

        form.clearErrors();

        if (mode === "edit" && category) {
            form.code = category.code ?? "";
            form.name = category.name ?? "";
            form.description = category.description ?? "";
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
        form.post(route("asset-categories.store"), {
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

    form.put(route("asset-categories.update", props.category.id), {
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
};
</script>

<template>
    <Dialog :open="props.open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-xl">
            <DialogHeader>
                <DialogTitle>
                    {{
                        props.mode === "create"
                            ? "Tambah Kategori Aset"
                            : "Edit Kategori Aset"
                    }}
                </DialogTitle>

                <DialogDescription>
                    {{
                        props.mode === "create"
                            ? "Lengkapi informasi kategori untuk mengelompokkan aset IT perusahaan."
                            : "Perbarui informasi kategori aset sesuai kebutuhan."
                    }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Kode Kategori -->
                <div class="space-y-2">
                    <Label for="code">Kode Kategori</Label>

                    <Input
                        id="code"
                        v-model="form.code"
                        placeholder="Contoh: LAPTOP"
                        maxlength="20"
                        :disabled="form.processing"
                    />

                    <p class="text-xs text-muted-foreground">
                        Gunakan kode unik untuk setiap kategori aset.
                    </p>

                    <InputError :message="form.errors.code" />
                </div>

                <!-- Nama Kategori -->
                <div class="space-y-2">
                    <Label for="name">Nama Kategori</Label>

                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Contoh: Laptop"
                        maxlength="100"
                        :disabled="form.processing"
                    />

                    <InputError :message="form.errors.name" />
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <Label for="description">
                        Deskripsi
                        <span class="text-muted-foreground"> (Opsional) </span>
                    </Label>

                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Masukkan deskripsi kategori aset..."
                        rows="4"
                        :disabled="form.processing"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Action -->
                <div class="flex justify-end gap-2 border-t pt-5">
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="form.processing"
                        @click="emit('update:open', false)"
                    >
                        Batal
                    </Button>

                    <Button
                        type="submit"
                        variant="cyan"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? "Menyimpan..."
                                : props.mode === "create"
                                  ? "Simpan Kategori"
                                  : "Perbarui Kategori"
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
