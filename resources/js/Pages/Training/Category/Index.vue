<script setup>
import { reactive, ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import AlertAction from "@/Components/AlertAction.vue";

import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import {
    IconArrowsDownUp,
    IconPencil,
    IconCategory,
    IconPlus,
    IconTrash,
} from "@tabler/icons-vue";
import EmptyState from "@/Components/EmptyState.vue";
import { Tab } from "@headlessui/vue";

import { deleteAction } from "@/lib/utils";
import Create from "./CreateModal.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    categories: Object,
    pageSettings: Object,
    items: Array,
    state: Object,
});

const categories = computed(() => props.categories.data);
const meta = computed(() => props.categories.meta);
const links = computed(() => props.categories.links);

const params = reactive({
    ...props.state,
});

const onSortTable = (field) => {
    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";

    router.get(route("training.categories.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["categories"],
    });
};

const openModal = ref(false);
const modalMode = ref("create");
const selectedCategory = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedCategory.value = null;
    openModal.value = true;
};

const openEditModal = () => {
    modalMode.value = "edit";
    selectedCategory.value = categories;
    openModal.value = true;
};
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head title="Kategori Pelatihan" />
        <!-- breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- banner -->
        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- Table -->
        <Card>
            <!-- header -->
            <CardHeader class="p-0">
                <div
                    class="flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconCategory"
                    />

                    <Button variant="red" size="xl" @click="openCreateModal">
                        <IconPlus class="size-4" />
                        Tambah
                    </Button>
                </div>

                <Filter v-model:params="params" :state="state" />
                <ShowFilter :params="params" />
            </CardHeader>

            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <EmptyState
                    v-if="categories.length === 0"
                    :icon="IconCategory"
                    title="Tidak ada Bandara"
                    subtitle="Mulailah dengan menambahkan data bandara"
                />

                <!-- table Bandara -->
                <Table v-else class="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    class="inline-flex group"
                                    @click="onSortTable('code')"
                                >
                                    Kode
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    class="inline-flex group"
                                    @click="onSortTable('name')"
                                >
                                    Nama Kategori
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>
                            <TableHead>Deskripsi</TableHead>
                            <TableHead>Dibuat</TableHead>
                            <TableHead>Aksi</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="(category, index) in categories"
                            :key="category.id"
                        >
                            <TableCell>
                                {{
                                    index +
                                    1 +
                                    (meta.current_page - 1) * meta.per_page
                                }}
                            </TableCell>

                            <TableCell class="font-medium">
                                {{ category.code }}
                            </TableCell>

                            <TableCell>
                                {{ category.name }}
                            </TableCell>

                            <TableCell>
                                {{ category.description }}
                            </TableCell>

                            <TableCell>
                                {{ category.created_at }}
                            </TableCell>

                            <TableCell>
                                <div class="flex items-center gap-x-1">
                                    <Button
                                        variant="blue"
                                        size="sm"
                                        @click="openEditModal(category)"
                                    >
                                        <IconPencil class="size-4" />
                                    </Button>

                                    <AlertAction
                                        :action="
                                            () =>
                                                deleteAction(
                                                    route(
                                                        'categories.destroy',
                                                        category.id,
                                                    ),
                                                )
                                        "
                                    >
                                        <Button variant="red" size="sm">
                                            <IconTrash class="size-4" />
                                        </Button>
                                    </AlertAction>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>

            <CardFooter
                class="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row"
            >
                <p class="text-sm text-muted-foreground">
                    Menampilkan
                    <span class="font-medium text-cyan-600">
                        {{ meta.from ?? 0 }}
                    </span>
                    dari {{ meta.total }} Bandara
                </p>

                <div class="overflow-x-auto">
                    <PaginationTable
                        v-if="meta.has_pages"
                        :meta="meta"
                        :links="links"
                    />
                </div>
            </CardFooter>
        </Card>

        <Create
            v-model:open="openModal"
            :mode="modalMode"
            :category="selectedCategory"
        />
    </div>
</template>
