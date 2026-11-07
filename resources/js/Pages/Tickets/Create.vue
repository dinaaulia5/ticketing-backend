<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

import { flashMessage } from "@/lib/utils";
import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";

import {
    handleFileChange as validateAttachments,
    removeAttachment as deleteAttachment,
} from "@/lib/attachment";

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

    ticket: {
        type: Object,
        default: null,
    },

    categories: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:open"]);

const fileInput = ref(null);

const form = useForm({
    ticket_type: "incident",
    category_id: "",
    subsystem_id: "",
    title: "",
    description: "",
    attachments: [],
});

/*
|--------------------------------------------------------------------------
| Subsystem berdasarkan kategori
|--------------------------------------------------------------------------
*/

const selectedCategory = computed(() => {
    return props.categories.find(
        (category) => category.id === form.category_id,
    );
});

const subsystems = computed(() => {
    return selectedCategory.value?.subsystems ?? [];
});

/*
|--------------------------------------------------------------------------
| Attachment
|--------------------------------------------------------------------------
*/

const handleFileChange = (event) => {
    const result = validateAttachments(event, form.attachments);

    form.attachments = result.files;

    if (result.error) {
        toast.error(result.error);
    }

    event.target.value = "";
};

const removeAttachment = (index) => {
    form.attachments = deleteAttachment(form.attachments, index);
};

/*
|--------------------------------------------------------------------------
| Watch Ticket
|--------------------------------------------------------------------------
*/

watch(
    [() => props.ticket, () => props.mode, () => props.open],
    ([ticket, mode, open]) => {
        if (!open) return;

        form.clearErrors();

        if (mode === "edit" && ticket) {
            form.ticket_type = ticket.ticket_type ?? "incident";
            form.category_id = ticket.category_id ?? "";
            form.subsystem_id = ticket.subsystem_id ?? "";
            form.title = ticket.title ?? "";
            form.description = ticket.description ?? "";
            form.attachments = [];
        } else {
            form.reset();
            form.clearErrors();
            form.ticket_type = "incident";
            form.attachments = [];
        }

        if (fileInput.value) {
            fileInput.value.value = "";
        }
    },
    {
        immediate: true,
    },
);

/*
|--------------------------------------------------------------------------
| Watch Category
|--------------------------------------------------------------------------
*/

watch(
    () => form.category_id,
    (newCategory, oldCategory) => {
        if (newCategory !== oldCategory) {
            form.subsystem_id = "";
        }
    },
);

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    if (props.mode === "create") {
        form.post(route("tickets.store"), {
            preserveScroll: true,

            onSuccess: (page) => {
                const flash = flashMessage(page);

                if (flash) {
                    toast[flash.type ?? "success"](flash.message);
                }

                form.reset();

                if (fileInput.value) {
                    fileInput.value.value = "";
                }

                emit("update:open", false);
            },
        });

        return;
    }

    form.put(route("tickets.update", props.ticket.id), {
        preserveScroll: true,

        onSuccess: (page) => {
            const flash = flashMessage(page);

            if (flash) {
                toast[flash.type ?? "success"](flash.message);
            }

            form.reset();

            if (fileInput.value) {
                fileInput.value.value = "";
            }

            emit("update:open", false);
        },
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="sm:max-w-2xl">
            <DialogHeader>
                <DialogTitle> Buat Tiket Baru </DialogTitle>

                <DialogDescription>
                    Lengkapi informasi berikut untuk membuat tiket layanan IT.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Ticket Type -->
                <div class="space-y-2">
                    <Label for="ticket_type">Jenis Tiket</Label>

                    <select
                        id="ticket_type"
                        v-model="form.ticket_type"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    >
                        <option value="incident">Incident</option>

                        <option value="service_request">Service Request</option>
                    </select>

                    <p class="text-xs text-muted-foreground">
                        Incident untuk gangguan layanan, sedangkan Service
                        Request untuk permintaan layanan IT.
                    </p>

                    <InputError :message="form.errors.ticket_type" />
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <Label for="category_id">Kategori</Label>

                    <select
                        id="category_id"
                        v-model="form.category_id"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    >
                        <option value="" disabled>Pilih kategori</option>

                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>

                    <InputError :message="form.errors.category_id" />
                </div>

                <!-- Subsystem -->
                <div class="space-y-2">
                    <Label for="subsystem_id"> Subsystem </Label>

                    <select
                        id="subsystem_id"
                        v-model="form.subsystem_id"
                        :disabled="!form.category_id || subsystems.length === 0"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <option value="">
                            {{
                                !form.category_id
                                    ? "Pilih kategori terlebih dahulu"
                                    : subsystems.length === 0
                                      ? "Tidak ada subsystem"
                                      : "Pilih subsystem (opsional)"
                            }}
                        </option>

                        <option
                            v-for="subsystem in subsystems"
                            :key="subsystem.id"
                            :value="subsystem.id"
                        >
                            {{ subsystem.name }}
                        </option>
                    </select>

                    <InputError :message="form.errors.subsystem_id" />
                </div>

                <!-- Title -->
                <div class="space-y-2">
                    <Label for="title">Judul Tiket</Label>

                    <Input
                        id="title"
                        v-model="form.title"
                        placeholder="Contoh: Laptop tidak bisa menyala"
                        maxlength="150"
                    />

                    <InputError :message="form.errors.title" />
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <Label for="description"> Deskripsi </Label>

                    <Textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Jelaskan permasalahan atau kebutuhan layanan IT secara detail..."
                        rows="5"
                    />

                    <InputError :message="form.errors.description" />
                </div>

                <!-- Attachment -->
                <div class="space-y-2">
                    <Label for="attachments">Lampiran (Opsional)</Label>

                    <input
                        id="attachments"
                        ref="fileInput"
                        type="file"
                        multiple
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        :disabled="form.processing"
                        @change="handleFileChange"
                        class="flex w-full cursor-pointer rounded-md border border-input bg-background px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-emerald-500 file:px-3 file:py-1 file:text-white file:font-medium"
                    />

                    <p class="text-xs text-muted-foreground">
                        Format JPG, JPEG, PNG, PDF, DOC, DOCX. Maksimal 5 file,
                        masing-masing 5 MB, total 25 MB.
                    </p>

                    <InputError :message="form.errors.attachments" />

                    <!-- Daftar file -->
                    <div v-if="form.attachments.length > 0" class="space-y-2">
                        <p class="text-sm font-medium">
                            File terpilih ({{ form.attachments.length }}/5)
                        </p>

                        <div
                            v-for="(file, index) in form.attachments"
                            :key="index"
                            class="flex items-center justify-between gap-3 rounded-md border p-3"
                        >
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">
                                    {{ file.name }}
                                </p>

                                <p class="text-xs text-muted-foreground">
                                    {{ (file.size / 1024 / 1024).toFixed(2) }}
                                    MB
                                </p>
                            </div>

                            <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                :disabled="form.processing"
                                @click="removeAttachment(index)"
                            >
                                Hapus
                            </Button>
                        </div>
                    </div>
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
                        variant="emerald"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? "Menyimpan..." : "Buat Tiket" }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
