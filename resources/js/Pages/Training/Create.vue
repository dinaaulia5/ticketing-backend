<script setup>
import { Link, useForm } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

import { IconArrowBack, IconBook, IconCheck } from "@tabler/icons-vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: {
        type: Object,
        required: true,
    },

    items: {
        type: Array,
        default: () => [],
    },

    categories: {
        type: Array,
        default: () => [],
    },

    trainingTypes: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    category_id: "",
    type_id: "",

    code: "",
    title: "",
    description: "",

    level: "",
    duration_minutes: null,
    capacity: null,

    is_mandatory: false,

    status: "DRAFT",

    thumbnail: "",

    start_date: "",
    end_date: "",

    registration_start: "",
    registration_end: "",
});

const onHandleSubmit = () => {
    form.post(props.pageSettings.action, {
        preserveScroll: true,
        preserveState: true,
    });
};

const onReset = () => {
    form.reset();

    form.status = "DRAFT";
    form.is_mandatory = false;
};
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <Card>
            <!-- Header -->
            <CardHeader>
                <div
                    class="flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconBook"
                    />

                    <Button variant="emerald" size="xl" as-child>
                        <Link :href="route('trainings.index')">
                            <IconArrowBack class="size-4" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </CardHeader>

            <!-- Form -->
            <CardContent>
                <form
                    class="grid grid-cols-1 gap-5 lg:grid-cols-2"
                    @submit.prevent="onHandleSubmit"
                >
                    <!-- Training Category -->
                    <div class="flex flex-col gap-2">
                        <Label for="category_id"> Training Category </Label>

                        <select
                            id="category_id"
                            v-model="form.category_id"
                            class="h-10 rounded-md border bg-background px-3 text-sm"
                        >
                            <option value="">Pilih Training Category</option>

                            <option
                                v-for="category in props.categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>

                        <InputError :message="form.errors.category_id" />
                    </div>

                    <!-- Training Type -->
                    <div class="flex flex-col gap-2">
                        <Label for="type_id"> Training Type </Label>

                        <select
                            id="type_id"
                            v-model="form.type_id"
                            class="h-10 rounded-md border bg-background px-3 text-sm"
                        >
                            <option value="">Pilih Training Type</option>

                            <option
                                v-for="type in props.trainingTypes"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.name }}
                            </option>
                        </select>

                        <InputError :message="form.errors.type_id" />
                    </div>

                    <!-- Training Code -->
                    <div class="flex flex-col gap-2">
                        <Label for="code"> Kode Training </Label>

                        <Input
                            id="code"
                            v-model="form.code"
                            placeholder="Contoh: TRN-001"
                        />

                        <InputError :message="form.errors.code" />
                    </div>

                    <!-- Training Title -->
                    <div class="flex flex-col gap-2">
                        <Label for="title"> Nama Training </Label>

                        <Input
                            id="title"
                            v-model="form.title"
                            placeholder="Masukkan nama training"
                        />

                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Description -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <Label for="description"> Deskripsi Training </Label>

                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring"
                            placeholder="Masukkan deskripsi training"
                        />

                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Level -->
                    <div class="flex flex-col gap-2">
                        <Label for="level"> Level </Label>

                        <select
                            id="level"
                            v-model="form.level"
                            class="h-10 rounded-md border bg-background px-3 text-sm"
                        >
                            <option value="">Pilih Level</option>

                            <option value="BEGINNER">Beginner</option>

                            <option value="INTERMEDIATE">Intermediate</option>

                            <option value="ADVANCED">Advanced</option>
                        </select>

                        <InputError :message="form.errors.level" />
                    </div>

                    <!-- Duration -->
                    <div class="flex flex-col gap-2">
                        <Label for="duration_minutes"> Durasi (Menit) </Label>

                        <Input
                            id="duration_minutes"
                            v-model="form.duration_minutes"
                            type="number"
                            min="1"
                            placeholder="Contoh: 120"
                        />

                        <InputError :message="form.errors.duration_minutes" />
                    </div>

                    <!-- Capacity -->
                    <div class="flex flex-col gap-2">
                        <Label for="capacity"> Kapasitas Peserta </Label>

                        <Input
                            id="capacity"
                            v-model="form.capacity"
                            type="number"
                            min="1"
                            placeholder="Contoh: 30"
                        />

                        <InputError :message="form.errors.capacity" />
                    </div>

                    <!-- Status -->
                    <div class="flex flex-col gap-2">
                        <Label for="status"> Status </Label>

                        <select
                            id="status"
                            v-model="form.status"
                            class="h-10 rounded-md border bg-background px-3 text-sm"
                        >
                            <option value="DRAFT">Draft</option>

                            <option value="PUBLISHED">Published</option>

                            <option value="ONGOING">Ongoing</option>

                            <option value="COMPLETED">Completed</option>

                            <option value="CANCELLED">Cancelled</option>
                        </select>

                        <InputError :message="form.errors.status" />
                    </div>

                    <!-- Start Date -->
                    <div class="flex flex-col gap-2">
                        <Label for="start_date"> Tanggal Mulai </Label>

                        <Input
                            id="start_date"
                            v-model="form.start_date"
                            type="date"
                        />

                        <InputError :message="form.errors.start_date" />
                    </div>

                    <!-- End Date -->
                    <div class="flex flex-col gap-2">
                        <Label for="end_date"> Tanggal Selesai </Label>

                        <Input
                            id="end_date"
                            v-model="form.end_date"
                            type="date"
                        />

                        <InputError :message="form.errors.end_date" />
                    </div>

                    <!-- Registration Start -->
                    <div class="flex flex-col gap-2">
                        <Label for="registration_start">
                            Pendaftaran Dibuka
                        </Label>

                        <Input
                            id="registration_start"
                            v-model="form.registration_start"
                            type="date"
                        />

                        <InputError :message="form.errors.registration_start" />
                    </div>

                    <!-- Registration End -->
                    <div class="flex flex-col gap-2">
                        <Label for="registration_end">
                            Pendaftaran Ditutup
                        </Label>

                        <Input
                            id="registration_end"
                            v-model="form.registration_end"
                            type="date"
                        />

                        <InputError :message="form.errors.registration_end" />
                    </div>

                    <!-- Thumbnail -->
                    <div class="flex flex-col gap-2 lg:col-span-2">
                        <Label for="thumbnail"> Thumbnail </Label>

                        <Input
                            id="thumbnail"
                            v-model="form.thumbnail"
                            placeholder="Path / URL thumbnail"
                        />

                        <InputError :message="form.errors.thumbnail" />
                    </div>

                    <!-- Mandatory -->
                    <div class="flex flex-col gap-2">
                        <Label> Sifat Training </Label>

                        <div class="flex h-10 items-center gap-3">
                            <input
                                id="is_mandatory"
                                v-model="form.is_mandatory"
                                type="checkbox"
                                class="size-4 rounded border"
                            />

                            <Label
                                for="is_mandatory"
                                class="cursor-pointer font-normal"
                            >
                                Training wajib diikuti
                            </Label>
                        </div>

                        <InputError :message="form.errors.is_mandatory" />
                    </div>

                    <!-- Action -->
                    <div
                        class="flex justify-end gap-2 border-t pt-5 lg:col-span-2"
                    >
                        <Button
                            type="button"
                            variant="ghost"
                            :disabled="form.processing"
                            @click="onReset"
                        >
                            Reset
                        </Button>

                        <Button
                            type="submit"
                            variant="emerald"
                            size="xl"
                            :disabled="form.processing"
                        >
                            <IconCheck class="size-4" />

                            {{
                                form.processing
                                    ? "Menyimpan..."
                                    : "Simpan Training"
                            }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
