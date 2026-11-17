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
    IconMapPin,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

const props = defineProps({
    locations: {
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
// LOCATION DATA
// ========================================

const locations = computed(() => {
    return Array.isArray(props.locations)
        ? props.locations
        : (props.locations?.data ?? []);
});

const meta = computed(() => {
    return props.locations?.meta ?? {};
});

const links = computed(() => {
    return props.locations?.links ?? [];
});

// ========================================
// FILTER & SORTING
// ========================================

const params = reactive({
    page: props.state.location_page ?? 1,
    search: props.state.location_search ?? "",
    load: props.state.location_load ?? 10,
    field: props.state.location_field ?? "created_at",
    direction: props.state.location_direction ?? "desc",
});

const onSortTable = (field) => {
    if (params.field === field) {
        params.direction = params.direction === "asc" ? "desc" : "asc";
    } else {
        params.field = field;
        params.direction = "asc";
    }

    params.page = 1;

    router.get(
        route("assets.index"),
        {
            ...props.state,
            location_page: params.page,
            location_search: params.search,
            location_load: params.load,
            location_field: params.field,
            location_direction: params.direction,
            tab: "location",
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ["locations", "state"],
        },
    );
};

// ========================================
// EDIT LOCATION
// ========================================

const openEditLocation = (location) => {
    emit("edit", location);
};
</script>

<template>
    <!-- Filter -->
    <div class="px-4">
        <Filter
            v-model:params="params"
            :state="state"
            route-name="assets.index"
            :only="['locations', 'state']"
        />

        <ShowFilter :params="params" />
    </div>

    <!-- Content -->
    <CardContent class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6">
        <!-- Empty State -->
        <EmptyState
            v-if="locations.length === 0"
            :icon="IconMapPin"
            title="Belum Ada Lokasi"
            subtitle="Belum ada lokasi aset yang tercatat dalam sistem."
        />

        <!-- Location Table -->
        <div v-else class="w-full overflow-x-auto">
            <Table class="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-12">#</TableHead>

                        <!-- Kode Lokasi -->
                        <TableHead>
                            <Button
                                variant="ghost"
                                class="inline-flex group"
                                @click="onSortTable('code')"
                            >
                                Kode Lokasi
                                <IconArrowsDownUp class="ml-2 size-4" />
                            </Button>
                        </TableHead>

                        <!-- Nama Lokasi -->
                        <TableHead>
                            <Button
                                variant="ghost"
                                class="inline-flex group"
                                @click="onSortTable('location_name')"
                            >
                                Nama Lokasi
                                <IconArrowsDownUp class="ml-2 size-4" />
                            </Button>
                        </TableHead>

                        <!-- Gedung -->
                        <TableHead>Gedung</TableHead>

                        <!-- Lantai -->
                        <TableHead>Lantai</TableHead>

                        <!-- Ruangan -->
                        <TableHead>Ruangan</TableHead>

                        <!-- Deskripsi -->
                        <TableHead>Deskripsi</TableHead>

                        <!-- Jumlah Aset -->
                        <TableHead class="text-center"> Jumlah Aset </TableHead>

                        <!-- Aksi -->
                        <TableHead class="text-center"> Aksi </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="(location, index) in locations"
                        :key="location.id"
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

                        <!-- Kode Lokasi -->
                        <TableCell class="font-medium">
                            <span
                                class="inline-flex rounded-md bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                            >
                                {{ location.code }}
                            </span>
                        </TableCell>

                        <!-- Nama Lokasi -->
                        <TableCell>
                            <span class="font-medium">
                                {{ location.location_name }}
                            </span>
                        </TableCell>

                        <!-- Gedung -->
                        <TableCell>
                            {{ location.building || "-" }}
                        </TableCell>

                        <!-- Lantai -->
                        <TableCell>
                            {{ location.floor || "-" }}
                        </TableCell>

                        <!-- Ruangan -->
                        <TableCell>
                            {{ location.room || "-" }}
                        </TableCell>

                        <!-- Deskripsi -->
                        <TableCell>
                            <div class="max-w-72 whitespace-normal">
                                <span class="text-sm text-muted-foreground">
                                    {{ location.description || "-" }}
                                </span>
                            </div>
                        </TableCell>

                        <!-- Jumlah Aset -->
                        <TableCell class="text-center">
                            <span
                                class="inline-flex min-w-10 items-center justify-center rounded-full bg-cyan-500/10 px-3 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                            >
                                {{ location.assets_count ?? 0 }}
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
                                            @select="openEditLocation(location)"
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
                                                            'locations.destroy',
                                                            location.id,
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
        v-if="locations.length > 0 && meta.has_pages"
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
            lokasi
        </p>

        <div class="overflow-x-auto">
            <PaginationTable :meta="meta" :links="links" />
        </div>
    </CardFooter>
</template>
