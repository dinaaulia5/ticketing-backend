<script setup>
import { watch, ref } from "vue";
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

    asset: {
        type: Object,
        default: null,
    },

    categories: {
        type: Array,
        default: () => [],
    },

    locations: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:open"]);

const fileInput = ref(null);

const form = useForm({
    asset_code: "",
    name: "",
    asset_category_id: "",
    brand: "",
    model: "",
    serial_number: "",
    processor: "",
    ram: "",
    storage: "",
    os: "",
    ip_address: "",
    mac_address: "",
    location_id: "",
    purchase_date: "",
    purchase_price: "",
    warranty_expiry: "",
    end_of_life: "",
    condition: "good",
    status: "available",
    notes: "",
    attachments: [],
});

/*
|--------------------------------------------------------------------------
| Watch Asset
|--------------------------------------------------------------------------
*/

watch(
    [() => props.asset, () => props.mode, () => props.open],
    ([asset, mode, open]) => {
        if (!open) return;

        form.clearErrors();

        if (fileInput.value) {
            fileInput.value.value = "";
        }

        if (mode === "edit" && asset) {
            form.asset_code = asset.asset_code ?? "";
            form.name = asset.name ?? "";

            form.asset_category_id = String(asset.asset_category_id ?? "");

            form.brand = asset.brand ?? "";
            form.model = asset.model ?? "";
            form.serial_number = asset.serial_number ?? "";
            form.processor = asset.processor ?? "";
            form.ram = asset.ram ?? "";
            form.storage = asset.storage ?? "";
            form.os = asset.os ?? "";
            form.ip_address = asset.ip_address ?? "";
            form.mac_address = asset.mac_address ?? "";
            form.location_id = asset.location_id ?? "";
            form.purchase_date = asset.purchase_date ?? "";
            form.purchase_price = asset.purchase_price ?? "";
            form.warranty_expiry = asset.warranty_expiry ?? "";
            form.end_of_life = asset.end_of_life ?? "";
            form.condition = asset.condition ?? "good";
            form.status = asset.status ?? "available";
            form.notes = asset.notes ?? "";
            form.attachments = [];
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
| Attachment
|--------------------------------------------------------------------------
*/

const handleFileChange = (event) => {
    const files = Array.from(event.target.files ?? []);

    form.attachments = files;
};

const removeAttachment = (index) => {
    form.attachments.splice(index, 1);

    if (fileInput.value) {
        fileInput.value.value = "";
    }
};

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const submit = () => {
    if (props.mode === "create") {
        form.post(route("assets.store"), {
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

    form.put(route("assets.update", props.asset.id), {
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
        <DialogContent class="max-h-[90vh] overflow-y-auto sm:max-w-3xl">
            <DialogHeader>
                <DialogTitle>
                    {{ props.mode === "create" ? "Tambah Aset" : "Edit Aset" }}
                </DialogTitle>

                <DialogDescription>
                    {{
                        props.mode === "create"
                            ? "Lengkapi informasi aset IT perusahaan."
                            : "Perbarui informasi aset IT perusahaan."
                    }}
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Identitas Aset -->
                <div class="space-y-4">
                    <h3 class="border-b pb-2 font-semibold">Identitas Aset</h3>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <!-- Kode Aset -->
                        <div class="space-y-2">
                            <Label for="asset_code">Kode Aset</Label>

                            <Input
                                id="asset_code"
                                v-model="form.asset_code"
                                placeholder="Contoh: AST-001"
                                maxlength="50"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.asset_code" />
                        </div>

                        <!-- Nama Aset -->
                        <div class="space-y-2">
                            <Label for="name">Nama Aset</Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Contoh: Laptop Lenovo"
                                maxlength="150"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Kategori Aset -->
                        <div class="space-y-2">
                            <Label for="asset_category_id">
                                Kategori Aset
                            </Label>

                            <select
                                id="asset_category_id"
                                v-model="form.asset_category_id"
                                class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                :disabled="form.processing"
                            >
                                <option value="" disabled>
                                    Pilih Kategori Aset
                                </option>

                                <option
                                    v-for="category in props.categories"
                                    :key="category.id"
                                    :value="String(category.id)"
                                >
                                    {{ category.name }}
                                </option>
                            </select>

                            <InputError
                                :message="form.errors.asset_category_id"
                            />
                        </div>

                        <!-- Brand -->
                        <div class="space-y-2">
                            <Label for="brand">Brand</Label>

                            <Input
                                id="brand"
                                v-model="form.brand"
                                placeholder="Contoh: Lenovo"
                                maxlength="100"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.brand" />
                        </div>

                        <!-- Model -->
                        <div class="space-y-2">
                            <Label for="model">Model</Label>

                            <Input
                                id="model"
                                v-model="form.model"
                                placeholder="Contoh: ThinkPad T14"
                                maxlength="100"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.model" />
                        </div>

                        <!-- Serial Number -->
                        <div class="space-y-2">
                            <Label for="serial_number"> Serial Number </Label>

                            <Input
                                id="serial_number"
                                v-model="form.serial_number"
                                placeholder="Masukkan serial number"
                                maxlength="150"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.serial_number" />
                        </div>
                    </div>
                </div>

                <!-- Spesifikasi Teknis -->
                <div class="space-y-4">
                    <h3 class="border-b pb-2 font-semibold">
                        Spesifikasi Teknis
                    </h3>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="processor">Processor</Label>
                            <Input
                                id="processor"
                                v-model="form.processor"
                                placeholder="Contoh: Intel Core i5"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.processor" />
                        </div>

                        <div class="space-y-2">
                            <Label for="ram">RAM</Label>
                            <Input
                                id="ram"
                                v-model="form.ram"
                                placeholder="Contoh: 16 GB"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.ram" />
                        </div>

                        <div class="space-y-2">
                            <Label for="storage">Storage</Label>
                            <Input
                                id="storage"
                                v-model="form.storage"
                                placeholder="Contoh: 512 GB SSD"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.storage" />
                        </div>

                        <div class="space-y-2">
                            <Label for="os">Operating System</Label>
                            <Input
                                id="os"
                                v-model="form.os"
                                placeholder="Contoh: Windows 11"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.os" />
                        </div>

                        <div class="space-y-2">
                            <Label for="ip_address">IP Address</Label>
                            <Input
                                id="ip_address"
                                v-model="form.ip_address"
                                placeholder="Contoh: 192.168.1.10"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.ip_address" />
                        </div>

                        <div class="space-y-2">
                            <Label for="mac_address">MAC Address</Label>
                            <Input
                                id="mac_address"
                                v-model="form.mac_address"
                                placeholder="Contoh: AA:BB:CC:DD:EE:FF"
                                :disabled="form.processing"
                            />
                            <InputError :message="form.errors.mac_address" />
                        </div>
                    </div>
                </div>

                <!-- Informasi Pembelian -->
                <div class="space-y-4">
                    <h3 class="border-b pb-2 font-semibold">
                        Informasi Pembelian
                    </h3>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="purchase_date">
                                Tanggal Pembelian
                            </Label>

                            <Input
                                id="purchase_date"
                                type="date"
                                v-model="form.purchase_date"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.purchase_date" />
                        </div>

                        <div class="space-y-2">
                            <Label for="purchase_price">
                                Harga Pembelian
                            </Label>

                            <Input
                                id="purchase_price"
                                type="number"
                                min="0"
                                v-model="form.purchase_price"
                                placeholder="Contoh: 12000000"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.purchase_price" />
                        </div>

                        <div class="space-y-2">
                            <Label for="warranty_expiry">
                                Tanggal Akhir Garansi
                            </Label>

                            <Input
                                id="warranty_expiry"
                                type="date"
                                v-model="form.warranty_expiry"
                                :disabled="form.processing"
                            />

                            <InputError
                                :message="form.errors.warranty_expiry"
                            />
                        </div>

                        <div class="space-y-2">
                            <Label for="end_of_life">
                                Tanggal Akhir Masa Pakai
                            </Label>

                            <Input
                                id="end_of_life"
                                type="date"
                                v-model="form.end_of_life"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.end_of_life" />
                        </div>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="space-y-4">
                    <h3 class="border-b pb-2 font-semibold">Lokasi Aset</h3>

                    <div class="space-y-2">
                        <Label for="location_id">Lokasi</Label>

                        <select
                            id="location_id"
                            v-model="form.location_id"
                            class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                            :disabled="form.processing"
                        >
                            <option value="">Pilih Lokasi</option>

                            <option
                                v-for="location in props.locations"
                                :key="location.id"
                                :value="location.id"
                            >
                                {{ location.location_name }}
                            </option>
                        </select>

                        <InputError :message="form.errors.location_id" />
                    </div>
                </div>

                <!-- Kondisi dan Status -->
                <div class="space-y-4">
                    <h3 class="border-b pb-2 font-semibold">
                        Kondisi dan Status
                    </h3>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="condition">Kondisi Aset</Label>

                            <select
                                id="condition"
                                v-model="form.condition"
                                class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                :disabled="form.processing"
                            >
                                <option value="good">Baik</option>
                                <option value="fair">Cukup</option>
                                <option value="damaged">Rusak</option>
                            </select>

                            <InputError :message="form.errors.condition" />
                        </div>

                        <div class="space-y-2">
                            <Label for="status">Status Aset</Label>

                            <select
                                id="status"
                                v-model="form.status"
                                class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                                :disabled="form.processing"
                            >
                                <option value="available">Tersedia</option>
                                <option value="assigned">Digunakan</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="retired">Tidak Digunakan</option>
                            </select>

                            <InputError :message="form.errors.status" />
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="space-y-2">
                    <Label for="notes">Catatan</Label>

                    <Textarea
                        id="notes"
                        v-model="form.notes"
                        placeholder="Masukkan catatan tambahan aset..."
                        rows="3"
                        :disabled="form.processing"
                    />

                    <InputError :message="form.errors.notes" />
                </div>

                <!-- Attachment -->
                <div class="space-y-2">
                    <Label for="attachments">Lampiran</Label>

                    <input
                        ref="fileInput"
                        id="attachments"
                        type="file"
                        multiple
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
                        class="block w-full rounded-md border border-input p-2 text-sm"
                        :disabled="form.processing"
                        @change="handleFileChange"
                    />

                    <p class="text-xs text-muted-foreground">
                        Maksimal 5 file, ukuran masing-masing maksimal 5 MB.
                    </p>

                    <div v-if="form.attachments.length" class="space-y-2">
                        <div
                            v-for="(file, index) in form.attachments"
                            :key="index"
                            class="flex items-center justify-between rounded-md border p-2 text-sm"
                        >
                            <span class="truncate">
                                {{ file.name }}
                            </span>

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

                    <InputError :message="form.errors.attachments" />

                    <InputError
                        v-for="(file, index) in form.attachments"
                        :key="index"
                        :message="form.errors[`attachments.${index}`]"
                    />
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
                                  ? "Simpan Aset"
                                  : "Perbarui Aset"
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
