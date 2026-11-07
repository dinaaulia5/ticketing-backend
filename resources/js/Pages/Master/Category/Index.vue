<script setup>
import { reactive, ref, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import AlertAction from "@/Components/AlertAction.vue";
import EmptyState from "@/Components/EmptyState.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";
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
    IconEye,
} from "@tabler/icons-vue";

import { deleteAction, formatDateIndo } from "@/lib/utils";
import CreateModalCategory from "./Create.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    categories: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },

    pageSettings: {
        type: Object,
        required: true,
    },

    items: {
        type: Array,
        default: () => [],
    },

    state: {
        type: Object,
        default: () => ({}),
    },
});

/*
|--------------------------------------------------------------------------
| Category Data
|--------------------------------------------------------------------------
*/

const categories = computed(() => props.categories?.data ?? []);
const meta = computed(() => props.categories?.meta ?? {});
const links = computed(() => props.categories?.links ?? []);

/*
|--------------------------------------------------------------------------
| Filter & Sorting
|--------------------------------------------------------------------------
*/

const params = reactive({
    page: props.state.page ?? 1,
    search: props.state.search ?? "",
    load: props.state.load ?? 10,
    field: props.state.field ?? "created_at",
    direction: props.state.direction ?? "desc",
});

const onSortTable = (field) => {
    if (params.field === field) {
        params.direction = params.direction === "asc" ? "desc" : "asc";
    } else {
        params.field = field;
        params.direction = "asc";
    }

    params.page = 1;

    router.get(route("categories.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ["categories", "state"],
    });
};

/*
|--------------------------------------------------------------------------
| Category Modal
|--------------------------------------------------------------------------
*/

const openModal = ref(false);
const modalMode = ref("create");
const selectedCategory = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedCategory.value = null;
    openModal.value = true;
};

const openEditModal = (category) => {
    modalMode.value = "edit";
    selectedCategory.value = category;
    openModal.value = true;
};

const openShowCategory = (category) => {
    router.get(route("categories.show", category.id));
};

/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head title="Master Categories" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- Banner -->
        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- Table -->
        <Card class="overflow-hidden">
            <!-- Header -->
            <CardHeader class="p-0">
                <div
                    class="flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconCategory"
                    />

                    <Button variant="cyan" size="xl" @click="openCreateModal">
                        <IconPlus class="size-4" />
                        Tambah Kategori
                    </Button>
                </div>

                <!-- Filter -->
                <Filter
                    v-model:params="params"
                    :state="state"
                    route-name="categories.index"
                    :only="['categories', 'state']"
                />

                <ShowFilter :params="params" />
            </CardHeader>

            <!-- Content -->
            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <!-- Empty State -->
                <EmptyState
                    v-if="categories.length === 0"
                    :icon="IconCategory"
                    title="Belum Ada Kategori"
                    subtitle="Mulailah dengan menambahkan kategori layanan IT."
                />

                <!-- Category Table -->
                <div v-else class="w-full overflow-x-auto">
                    <Table class="w-full">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12"> # </TableHead>

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

                                <TableHead> Deskripsi </TableHead>

                                <TableHead> Jumlah Subsystem </TableHead>

                                <TableHead> Dibuat </TableHead>

                                <TableHead class="text-center">
                                    Aksi
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(category, index) in categories"
                                :key="category.id"
                            >
                                <!-- Number -->
                                <TableCell>
                                    {{
                                        index +
                                        1 +
                                        ((meta.current_page ?? 1) - 1) *
                                            (meta.per_page ?? 10)
                                    }}
                                </TableCell>

                                <!-- Code -->
                                <TableCell class="font-medium">
                                    <span
                                        class="inline-flex rounded-md bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                                    >
                                        {{ category.code }}
                                    </span>
                                </TableCell>

                                <!-- Name -->
                                <TableCell>
                                    <span class="font-medium">
                                        {{ category.name }}
                                    </span>
                                </TableCell>

                                <!-- Description -->
                                <TableCell>
                                    <div
                                        class="min-w-48 max-w-96 whitespace-normal"
                                    >
                                        <p
                                            class="line-clamp-2 text-sm text-muted-foreground"
                                        >
                                            {{ category.description || "-" }}
                                        </p>
                                    </div>
                                </TableCell>

                                <TableCell class="text-center">
                                    <span
                                        class="inline-flex min-w-10 items-center justify-center rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400"
                                    >
                                        {{ category.subsystems_count ?? 0 }}
                                    </span>
                                </TableCell>

                                <!-- Created At -->
                                <TableCell>
                                    {{ formatDateIndo(category.created_at) }}
                                </TableCell>

                                <!-- Actions -->
                                <TableCell>
                                    <div
                                        class="flex items-center justify-center gap-x-1.5"
                                    >
                                        <!-- Detail / Show -->
                                        <Button
                                            variant="cyan"
                                            size="sm"
                                            title="Detail kategori"
                                            @click="openShowCategory(category)"
                                        >
                                            <IconEye class="size-4" />
                                        </Button>

                                        <!-- Edit -->
                                        <Button
                                            variant="blue"
                                            size="sm"
                                            title="Edit kategori"
                                            @click="openEditModal(category)"
                                        >
                                            <IconPencil class="size-4" />
                                        </Button>

                                        <!-- Delete -->
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
                                            <Button
                                                variant="red"
                                                size="sm"
                                                title="Hapus kategori"
                                            >
                                                <IconTrash class="size-4" />
                                            </Button>
                                        </AlertAction>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </CardContent>

            <!-- Pagination -->
            <CardFooter
                v-if="categories.length > 0"
                class="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row"
            >
                <p class="text-sm text-muted-foreground">
                    Menampilkan
                    <span class="font-medium text-cyan-600">
                        {{ meta.from ?? 0 }}
                    </span>
                    dari
                    <span class="font-medium">
                        {{ meta.total ?? 0 }}
                    </span>
                    Kategori
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

        <!-- Category Modal -->
        <CreateModalCategory
            v-model:open="openModal"
            :mode="modalMode"
            :category="selectedCategory"
        />
    </div>
</template>
