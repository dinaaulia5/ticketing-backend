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

    location: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["update:open"]);

const form = useForm({
    code: "",
    location_name: "",
    building: "",
    floor: "",
    room: "",
    description: "",
});

/*
|--------------------------------------------------------------------------
| Watch Location
|--------------------------------------------------------------------------
*/

watch(
    [() => props.location, () => props.mode, () => props.open],
    ([location, mode, open]) => {
        if (!open) return;

        form.clearErrors();

        if (mode === "edit" && location) {
            form.code = location.code ?? "";
            form.location_name = location.location_name ?? "";
            form.building = location.building ?? "";
            form.floor = location.floor ?? "";
            form.room = location.room ?? "";
            form.description = location.description ?? "";
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
        form.post(route("locations.store"), {
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

    form.put(route("locations.update", props.location.id), {
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
                            ? "Tambah Lokasi"
                            : "Edit Lokasi"
                    }}
                </DialogTitle>

                <DialogDescription>
                    {{
                        props.mode === "create"
                            ? "Lengkapi informasi lokasi untuk mencatat penempatan aset IT perusahaan."
                            : "Perbarui informasi lokasi sesuai kebutuhan."
                    }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Kode Lokasi -->
                <div class="space-y-2">
                    <Label for="code">Kode Lokasi</Label>

                    <Input
                        id="code"
                        v-model="form.code"
                        placeholder="Contoh: LOC-001"
                        maxlength="20"
                        :disabled="form.processing"
                    />

                    <p class="text-xs text-muted-foreground">
                        Gunakan kode unik untuk setiap lokasi.
                    </p>

                    <InputError :message="form.errors.code" />
                </div>

                <!-- Nama Lokasi -->
                <div class="space-y-2">
                    <Label for="location_name">Nama Lokasi</Label>

                    <Input
                        id="location_name"
                        v-model="form.location_name"
                        placeholder="Contoh: Head Office"
                        maxlength="100"
                        :disabled="form.processing"
                    />

                    <InputError :message="form.errors.location_name" />
                </div>

                <!-- Gedung -->
                <div class="space-y-2">
                    <Label for="building">
                        Gedung
                        <span class="text-muted-foreground">(Opsional)</span>
                    </Label>

                    <Input
                        id="building"
                        v-model="form.building"
                        placeholder="Contoh: Gedung A"
                        maxlength="100"
                        :disabled="form.processing"
                    />

                    <InputError :message="form.errors.building" />
                </div>

                <!-- Lantai dan Ruangan -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="floor">
                            Lantai
                            <span class="text-muted-foreground"
                                >(Opsional)</span
                            >
                        </Label>

                        <Input
                            id="floor"
                            v-model="form.floor"
                            placeholder="Contoh: Lantai 2"
                            maxlength="50"
                            :disabled="form.processing"
                        />

                        <InputError :message="form.errors.floor" />
                    </div>

                    <div class="space-y-2">
                        <Label for="room">
                            Ruangan
                            <span class="text-muted-foreground"
                                >(Opsional)</span
                            >
                        </Label>

                        <Input
                            id="room"
                            v-model="form.room"
                            placeholder="Contoh: Ruang IT"
                            maxlength="50"
                            :disabled="form.processing"
                        />

                        <InputError :message="form.errors.room" />
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <Label for="description">
                        Deskripsi
                        <span class="text-muted-foreground">(Opsional)</span>
                    </Label>

                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Masukkan deskripsi lokasi..."
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
                                  ? "Simpan Lokasi"
                                  : "Perbarui Lokasi"
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
