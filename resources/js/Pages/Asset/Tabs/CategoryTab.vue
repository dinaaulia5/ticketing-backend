<script setup>
import { reactive, computed } from "vue";
import { router } from "@inertiajs/vue3";

import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import EmptyState from "@/Components/EmptyState.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";
import AlertAction from "@/Components/AlertAction.vue";

import { Button } from "@/Components/ui/button";
import { CardContent, CardFooter } from "@/Components/ui/card";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

import {
    IconArrowsDownUp,
    IconDotsVertical,
    IconPencil,
    IconTrash,
    IconCategory,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

const props = defineProps({
    categories: {
        type: [Object, Array],
        default: () => [],
    },

    state: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["edit"]);

// ========================================
// CATEGORY DATA
// ========================================

const categories = computed(() => {
    return Array.isArray(props.categories)
        ? props.categories
        : (props.categories?.data ?? []);
});

const meta = computed(() => {
    return props.categories?.meta ?? {};
});

const links = computed(() => {
    return props.categories?.links ?? [];
});

// ========================================
// FILTER & SORTING
// ========================================

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

    router.get(route("asset-categories.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ["categories", "state"],
    });
};

// ========================================
// EDIT CATEGORY
// ========================================

const openEditCategory = (category) => {
    emit("edit", category);
};
</script>

<template>
    <!-- Filter -->
    <div class="px-4">
        <Filter
            v-model:params="params"
            :state="state"
            route-name="asset-categories.index"
            :only="['categories', 'state']"
        />

        <ShowFilter :params="params" />
    </div>

    <!-- Content -->
    <CardContent class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6">
        <!-- Empty State -->
        <EmptyState
            v-if="categories.length === 0"
            :icon="IconCategory"
            title="Belum Ada Kategori Aset"
            subtitle="Belum ada kategori aset IT yang tercatat dalam sistem."
        />

        <!-- Category Table -->
        <div v-else class="w-full overflow-x-auto">
            <Table class="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-12">#</TableHead>

                        <!-- Kode Kategori -->
                        <TableHead>
                            <Button
                                variant="ghost"
                                class="inline-flex group"
                                @click="onSortTable('code')"
                            >
                                Kode Kategori
                                <IconArrowsDownUp class="ml-2 size-4" />
                            </Button>
                        </TableHead>

                        <!-- Nama Kategori -->
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

                        <TableHead class="text-center"> Jumlah Aset </TableHead>

                        <TableHead class="text-center"> Aksi </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="(category, index) in categories"
                        :key="category.id"
                    >
                        <!-- Nomor -->
                        <TableCell>
                            {{
                                index +
                                1 +
                                ((meta.current_page ?? 1) - 1) *
                                    (meta.per_page ?? 10)
                            }}
                        </TableCell>

                        <!-- Kode Kategori -->
                        <TableCell class="font-medium">
                            <span
                                class="inline-flex rounded-md bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                            >
                                {{ category.code }}
                            </span>
                        </TableCell>

                        <!-- Nama Kategori -->
                        <TableCell>
                            <span class="font-medium">
                                {{ category.name }}
                            </span>
                        </TableCell>

                        <!-- Deskripsi -->
                        <TableCell>
                            <div class="max-w-72 whitespace-normal">
                                <span class="text-sm text-muted-foreground">
                                    {{ category.description || "-" }}
                                </span>
                            </div>
                        </TableCell>

                        <!-- Jumlah Aset -->
                        <TableCell class="text-center">
                            <span
                                class="inline-flex min-w-10 items-center justify-center rounded-full bg-cyan-500/10 px-3 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                            >
                                {{ category.assets_count ?? 0 }}
                            </span>
                        </TableCell>

                        <!-- Aksi -->
                        <TableCell>
                            <div class="flex items-center justify-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="size-9 rounded-lg"
                                            title="Menu aksi"
                                        >
                                            <IconDotsVertical class="size-5" />
                                        </Button>
                                    </DropdownMenuTrigger>

                                    <DropdownMenuContent
                                        align="end"
                                        class="w-40"
                                    >
                                        <!-- Edit -->
                                        <DropdownMenuItem
                                            @select="openEditCategory(category)"
                                            class="cursor-pointer"
                                        >
                                            <IconPencil
                                                class="mr-2 size-4 text-blue-500"
                                            />
                                            Edit
                                        </DropdownMenuItem>

                                        <DropdownMenuSeparator />

                                        <!-- Hapus -->
                                        <AlertAction
                                            :action="
                                                () =>
                                                    deleteAction(
                                                        route(
                                                            'asset-categories.destroy',
                                                            category.id,
                                                        ),
                                                    )
                                            "
                                        >
                                            <button
                                                type="button"
                                                class="flex w-full items-center rounded-sm px-2 py-1.5 text-sm text-red-500 outline-none transition hover:bg-red-500/10"
                                            >
                                                <IconTrash
                                                    class="mr-2 size-4"
                                                />
                                                Hapus
                                            </button>
                                        </AlertAction>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </CardContent>

    <!-- Pagination -->
    <CardFooter
        v-if="categories.length > 0 && meta.has_pages"
        class="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row"
    >
        <p class="text-sm text-muted-foreground">
            Menampilkan
            <span class="font-medium text-cyan-600">
                {{ meta.from ?? 0 }}
            </span>
            sampai
            <span class="font-medium text-cyan-600">
                {{ meta.to ?? 0 }}
            </span>
            dari
            <span class="font-medium">
                {{ meta.total ?? 0 }}
            </span>
            kategori
        </p>

        <div class="overflow-x-auto">
            <PaginationTable :meta="meta" :links="links" />
        </div>
    </CardFooter>
</template>
