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
    IconEye,
    IconPencil,
    IconTrash,
    IconCategory,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

const props = defineProps({
    assets: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },

    categories: {
        type: Array,
        default: () => [],
    },

    state: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["edit"]);

const assets = computed(() => props.assets?.data ?? []);
const meta = computed(() => props.assets?.meta ?? {});
const links = computed(() => props.assets?.links ?? []);

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

    router.get(route("assets.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ["assets", "state"],
    });
};

const openShowAsset = (asset) => {
    router.get(route("assets.show", asset.id));
};

const openEditAsset = (asset) => {
    emit("edit", asset);
};
</script>

<template>
    <!-- Filter -->
    <div class="px-4">
        <Filter
            v-model:params="params"
            :state="state"
            route-name="assets.index"
            :only="['assets', 'state']"
        />

        <ShowFilter :params="params" />
    </div>

    <!-- Content -->
    <CardContent class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6">
        <!-- Empty State -->
        <EmptyState
            v-if="assets.length === 0"
            :icon="IconCategory"
            title="Belum Ada Aset"
            subtitle="Belum ada data aset IT yang tercatat dalam sistem."
        />

        <!-- Asset Table -->
        <div v-else class="w-full overflow-x-auto">
            <Table class="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-12">#</TableHead>

                        <TableHead>
                            <Button
                                variant="ghost"
                                class="inline-flex group"
                                @click="onSortTable('asset_code')"
                            >
                                Kode Aset
                                <IconArrowsDownUp class="ml-2 size-4" />
                            </Button>
                        </TableHead>

                        <TableHead>
                            <Button
                                variant="ghost"
                                class="inline-flex group"
                                @click="onSortTable('name')"
                            >
                                Nama Aset
                                <IconArrowsDownUp class="ml-2 size-4" />
                            </Button>
                        </TableHead>

                        <TableHead>Kategori</TableHead>

                        <TableHead>Brand / Model</TableHead>

                        <TableHead>Lokasi</TableHead>

                        <TableHead>Kondisi</TableHead>

                        <TableHead>Status</TableHead>

                        <TableHead class="text-center"> Aksi </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="(asset, index) in assets" :key="asset.id">
                        <!-- Nomor -->
                        <TableCell>
                            {{
                                index +
                                1 +
                                ((meta.current_page ?? 1) - 1) *
                                    (meta.per_page ?? 10)
                            }}
                        </TableCell>

                        <!-- Kode Aset -->
                        <TableCell class="font-medium">
                            <span
                                class="inline-flex rounded-md bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                            >
                                {{ asset.asset_code }}
                            </span>
                        </TableCell>

                        <!-- Nama Aset -->
                        <TableCell>
                            <div class="min-w-40 max-w-64 whitespace-normal">
                                <span class="font-medium">
                                    {{ asset.name }}
                                </span>
                            </div>
                        </TableCell>

                        <!-- Kategori -->
                        <TableCell>
                            {{ asset.category?.name || "-" }}
                        </TableCell>

                        <!-- Brand / Model -->
                        <TableCell>
                            <div class="flex flex-col">
                                <span>{{ asset.brand || "-" }}</span>
                                <span class="text-xs text-muted-foreground">
                                    {{ asset.model || "" }}
                                </span>
                            </div>
                        </TableCell>

                        <!-- Lokasi -->
                        <TableCell>
                            {{ asset.location?.location_name || "-" }}
                        </TableCell>

                        <!-- Kondisi -->
                        <TableCell>
                            <span
                                class="inline-flex min-w-20 items-center justify-center rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400':
                                        asset.condition === 'good',

                                    'bg-amber-500/10 text-amber-600 dark:text-amber-400':
                                        asset.condition === 'fair',

                                    'bg-red-500/10 text-red-600 dark:text-red-400':
                                        asset.condition === 'damaged',
                                }"
                            >
                                {{
                                    {
                                        good: "Baik",
                                        fair: "Cukup",
                                        damaged: "Rusak",
                                    }[asset.condition] || asset.condition
                                }}
                            </span>
                        </TableCell>

                        <!-- Status -->
                        <TableCell>
                            <span
                                class="inline-flex min-w-24 items-center justify-center rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400':
                                        asset.status === 'available',

                                    'bg-blue-500/10 text-blue-600 dark:text-blue-400':
                                        asset.status === 'assigned',

                                    'bg-amber-500/10 text-amber-600 dark:text-amber-400':
                                        asset.status === 'maintenance',

                                    'bg-gray-500/10 text-gray-600 dark:text-gray-400':
                                        asset.status === 'retired',
                                }"
                            >
                                {{
                                    {
                                        available: "Available",
                                        assigned: "Assigned",
                                        maintenance: "Maintenance",
                                        retired: "Retired",
                                    }[asset.status] || asset.status
                                }}
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
                                        <!-- Detail -->
                                        <DropdownMenuItem
                                            @select="openShowAsset(asset)"
                                            class="cursor-pointer"
                                        >
                                            <IconEye
                                                class="mr-2 size-4 text-cyan-500"
                                            />
                                            Detail
                                        </DropdownMenuItem>

                                        <!-- Edit -->
                                        <DropdownMenuItem
                                            @select="openEditAsset(asset)"
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
                                                            'assets.destroy',
                                                            asset.id,
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
        v-if="assets.length > 0"
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
            aset
        </p>

        <div class="overflow-x-auto">
            <PaginationTable
                v-if="meta.has_pages"
                :meta="meta"
                :links="links"
            />
        </div>
    </CardFooter>
</template>
