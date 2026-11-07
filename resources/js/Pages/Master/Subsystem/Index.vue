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
import { deleteAction, formatDateIndo } from "@/lib/utils";
import CreateModalSubsystem from "./Create.vue";

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

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    subsystems: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },

    categoryId: {
        type: String,
        required: true,
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

const subsystems = computed(() => props.subsystems?.data ?? []);
const meta = computed(() => props.subsystems?.meta ?? {});
const links = computed(() => props.subsystems?.links ?? []);

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

    router.get(route("categories.show", props.categoryId), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ["subsystems", "state"],
    });
};
/*
|--------------------------------------------------------------------------
| Category Modal
|--------------------------------------------------------------------------
*/

const openModal = ref(false);
const modalMode = ref("create");
const selectedSubsystem = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedSubsystem.value = null;
    openModal.value = true;
};

const openEditModal = (subsystem) => {
    modalMode.value = "edit";
    selectedSubsystem.value = subsystem;
    openModal.value = true;
};

/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
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

                    <Button variant="cyan" size="lg" @click="openCreateModal">
                        <IconPlus class="size-4" />
                        Tambah Subsystem
                    </Button>
                </div>

                <!-- Filter -->
                <Filter
                    v-model:params="params"
                    :state="state"
                    route-name="categories.show"
                    :route-params="categoryId"
                    :only="['subsystems', 'state']"
                />

                <ShowFilter :params="params" />
            </CardHeader>

            <!-- Content -->
            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <!-- Empty State -->
                <EmptyState
                    v-if="subsystems.length === 0"
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

                                <TableHead> Dibuat </TableHead>

                                <TableHead class="text-center">
                                    Aksi
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(subsystem, index) in subsystems"
                                :key="subsystem.id"
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
                                        {{ subsystem.code }}
                                    </span>
                                </TableCell>

                                <!-- Name -->
                                <TableCell>
                                    <span class="font-medium">
                                        {{ subsystem.name }}
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
                                            {{ subsystem.description || "-" }}
                                        </p>
                                    </div>
                                </TableCell>

                                <!-- Created At -->
                                <TableCell>
                                    {{ formatDateIndo(subsystem.created_at) }}
                                </TableCell>

                                <!-- Actions -->
                                <TableCell>
                                    <div
                                        class="flex items-center justify-center gap-x-1.5"
                                    >
                                        <Button
                                            variant="blue"
                                            size="sm"
                                            title="Edit subsystem"
                                            @click="openEditModal(subsystem)"
                                        >
                                            <IconPencil class="size-4" />
                                        </Button>

                                        <!-- Delete -->
                                        <AlertAction
                                            :action="
                                                () =>
                                                    deleteAction(
                                                        route(
                                                            'subsystems.destroy',
                                                            subsystem.id,
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
                v-if="subsystems.length > 0"
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

        <!-- Edit subsystem Modal -->
        <CreateModalSubsystem
            v-model:open="openModal"
            :mode="modalMode"
            :subsystem="selectedSubsystem"
            :category-id="categoryId"
        />
    </div>
</template>
