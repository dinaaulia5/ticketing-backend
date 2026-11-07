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
    IconTicket,
    IconCircleDot,
    IconPlayerPause,
    IconCheck,
    IconFolder,
    IconRefresh,
    IconCircleCheck,
    IconLock,
    IconClock,
    IconDotsVertical,
} from "@tabler/icons-vue";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

import { deleteAction, formatDateIndo } from "@/lib/utils";
import CreateModalTicket from "./Create.vue";
import CardStat from "@/Components/CardStat.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    tickets: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },
    statistics: {
        type: Object,
        default: () => ({
            total: 0,
            open: 0,
            in_progress: 0,
            pending: 0,
            resolved: 0,
            closed: 0,
        }),
    },
    categories: {
        type: Array,
        default: () => [],
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

const tickets = computed(() => props.tickets?.data ?? []);
const meta = computed(() => props.tickets?.meta ?? {});
const links = computed(() => props.tickets?.links ?? []);

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

    router.get(route("tickets.index"), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        only: ["tickets", "state"],
    });
};

/*
|--------------------------------------------------------------------------
| Ticket Modal
|--------------------------------------------------------------------------
*/

const openModal = ref(false);
const modalMode = ref("create");
const selectedTicket = ref(null);

const openCreateModal = () => {
    modalMode.value = "create";
    selectedTicket.value = null;
    openModal.value = true;
};

const openEditModal = (ticket) => {
    modalMode.value = "edit";
    selectedTicket.value = ticket;
    openModal.value = true;
};

const openShowTicket = (ticket) => {
    router.get(route("tickets.show", ticket.id));
};

const getPercentage = (value) => {
    const total = props.statistics.total;

    if (!total) return "0.0";

    return ((value / total) * 100).toFixed(1);
};

/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head title="Master tickets" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- Banner -->
        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- STATISTIK -->
        <div
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6"
        >
            <!-- TOTAL TIKET -->
            <CardStat
                :data="{
                    title: 'Total Tiket',
                    icon: IconTicket,
                    background:
                        'bg-gradient-to-r from-blue-400 via-blue-500 to-blue-500',
                    iconBackground: 'bg-blue-600/40 text-white',
                    bars: [20, 35, 25, 50, 65, 85, 100],
                }"
            >
                {{ props.statistics.total }}
            </CardStat>

            <!-- OPEN -->
            <CardStat
                :data="{
                    title: 'Open',
                    icon: IconFolder,
                    background:
                        'bg-gradient-to-r from-sky-400 via-sky-500 to-sky-500',
                    iconBackground: 'bg-sky-600/40 text-white',
                    percentage: getPercentage(props.statistics.open),
                }"
            >
                {{ props.statistics.open }}
            </CardStat>

            <!-- IN PROGRESS -->
            <CardStat
                :data="{
                    title: 'In Progress',
                    icon: IconRefresh,
                    background:
                        'bg-gradient-to-r from-amber-400 via-amber-500 to-amber-500',
                    iconBackground: 'bg-amber-600/40 text-white',
                    percentage: getPercentage(props.statistics.in_progress),
                }"
            >
                {{ props.statistics.in_progress }}
            </CardStat>

            <!-- PENDING -->
            <CardStat
                :data="{
                    title: 'Pending',
                    icon: IconClock,
                    background:
                        'bg-gradient-to-r from-orange-400 via-orange-500 to-orange-500',
                    iconBackground: 'bg-orange-600/40 text-white',
                    percentage: getPercentage(props.statistics.pending),
                }"
            >
                {{ props.statistics.pending }}
            </CardStat>

            <!-- RESOLVED -->
            <CardStat
                :data="{
                    title: 'Resolved',
                    icon: IconCircleCheck,
                    background:
                        'bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-500',
                    iconBackground: 'bg-emerald-600/40 text-white',
                    percentage: getPercentage(props.statistics.resolved),
                }"
            >
                {{ props.statistics.resolved }}
            </CardStat>

            <!-- CLOSED -->
            <CardStat
                :data="{
                    title: 'Closed',
                    icon: IconLock,
                    background:
                        'bg-gradient-to-r from-gray-400 via-gray-500 to-gray-500',
                    iconBackground: 'bg-gray-600/40 text-white',
                    percentage: getPercentage(props.statistics.closed),
                }"
            >
                {{ props.statistics.closed }}
            </CardStat>
        </div>

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
                        {{ pageSettings.create }}
                    </Button>
                </div>

                <!-- Filter -->
                <Filter
                    v-model:params="params"
                    :state="state"
                    route-name="tickets.index"
                    :only="['tickets', 'state']"
                />

                <ShowFilter :params="params" />
            </CardHeader>

            <!-- Content -->
            <!-- Content -->
            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <!-- Empty State -->
                <EmptyState
                    v-if="tickets.length === 0"
                    :icon="IconTicket"
                    title="Belum Ada Tiket"
                    subtitle="Belum ada tiket layanan IT yang tercatat."
                />

                <!-- Ticket Table -->
                <div v-else class="w-full overflow-x-auto">
                    <Table class="w-full">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12"> # </TableHead>

                                <!-- Ticket Number -->
                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex group"
                                        @click="onSortTable('ticket_number')"
                                    >
                                        No. Tiket
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <!-- Title -->
                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex group"
                                        @click="onSortTable('title')"
                                    >
                                        Judul Tiket
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <!-- Type -->
                                <TableHead> Jenis </TableHead>

                                <!-- Category -->
                                <TableHead> Kategori </TableHead>

                                <!-- Priority -->
                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex group"
                                        @click="onSortTable('priority')"
                                    >
                                        Prioritas
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <!-- Status -->
                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex group"
                                        @click="onSortTable('status')"
                                    >
                                        Status
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <!-- Reporter -->
                                <TableHead> Pelapor </TableHead>

                                <!-- Created At -->
                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex group"
                                        @click="onSortTable('created_at')"
                                    >
                                        Dibuat Pada
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <!-- Actions -->
                                <TableHead class="text-center">
                                    Aksi
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(ticket, index) in tickets"
                                :key="ticket.id"
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

                                <!-- Ticket Number -->
                                <TableCell class="font-medium">
                                    <span
                                        class="inline-flex rounded-md bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                                    >
                                        {{ ticket.ticket_number }}
                                    </span>
                                </TableCell>

                                <!-- Title -->
                                <TableCell>
                                    <div
                                        class="min-w-48 max-w-72 whitespace-normal"
                                    >
                                        <span class="font-medium">
                                            {{ ticket.title }}
                                        </span>
                                    </div>
                                </TableCell>

                                <!-- Type -->
                                <TableCell>
                                    <span
                                        class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold"
                                        :class="
                                            ticket.ticket_type === 'incident'
                                                ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                                : 'bg-violet-500/10 text-violet-600 dark:text-violet-400'
                                        "
                                    >
                                        {{
                                            ticket.ticket_type === "incident"
                                                ? "Incident"
                                                : "Service Request"
                                        }}
                                    </span>
                                </TableCell>

                                <!-- Category -->
                                <TableCell>
                                    {{ ticket.category?.name || "-" }}
                                </TableCell>

                                <!-- Priority -->
                                <TableCell>
                                    <span
                                        class="inline-flex min-w-20 items-center justify-center rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-red-500/10 text-red-600 dark:text-red-400':
                                                ticket.priority ===
                                                    'critical' ||
                                                ticket.priority === 'high',

                                            'bg-amber-500/10 text-amber-600 dark:text-amber-400':
                                                ticket.priority === 'medium',

                                            'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400':
                                                ticket.priority === 'low',

                                            'bg-muted text-muted-foreground':
                                                !ticket.priority,
                                        }"
                                    >
                                        {{
                                            ticket.priority
                                                ? ticket.priority
                                                      .charAt(0)
                                                      .toUpperCase() +
                                                  ticket.priority.slice(1)
                                                : "Belum ditentukan"
                                        }}
                                    </span>
                                </TableCell>

                                <!-- Status -->
                                <TableCell>
                                    <span
                                        class="inline-flex min-w-24 items-center justify-center rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="{
                                            'bg-blue-500/10 text-blue-600 dark:text-blue-400':
                                                ticket.status === 'open',

                                            'bg-amber-500/10 text-amber-600 dark:text-amber-400':
                                                ticket.status ===
                                                    'in_progress' ||
                                                ticket.status === 'pending',

                                            'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400':
                                                ticket.status === 'resolved',

                                            'bg-gray-500/10 text-gray-600 dark:text-gray-400':
                                                ticket.status === 'closed',
                                        }"
                                    >
                                        {{
                                            {
                                                open: "Open",
                                                in_progress: "In Progress",
                                                pending: "Pending",
                                                resolved: "Resolved",
                                                closed: "Closed",
                                            }[ticket.status] || ticket.status
                                        }}
                                    </span>
                                </TableCell>

                                <!-- Reporter -->
                                <TableCell>
                                    {{ ticket.user?.name || "-" }}
                                </TableCell>

                                <!-- Created At -->
                                <TableCell>
                                    {{ formatDateIndo(ticket.created_at) }}
                                </TableCell>

                                <!-- Actions -->
                                <TableCell>
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <DropdownMenu>
                                            <!-- Tombol titik tiga -->
                                            <DropdownMenuTrigger as-child>
                                                <Button
                                                    type="button"
                                                    variant="ghost"
                                                    size="icon"
                                                    class="size-9 rounded-lg"
                                                    title="Menu aksi"
                                                >
                                                    <IconDotsVertical
                                                        class="size-5"
                                                    />
                                                </Button>
                                            </DropdownMenuTrigger>

                                            <!-- Dropdown Menu -->
                                            <DropdownMenuContent
                                                align="end"
                                                class="w-40"
                                            >
                                                <!-- Detail -->
                                                <DropdownMenuItem
                                                    @select="
                                                        openShowTicket(ticket)
                                                    "
                                                    class="cursor-pointer"
                                                >
                                                    <IconEye
                                                        class="mr-2 size-4 text-cyan-500"
                                                    />
                                                    Detail
                                                </DropdownMenuItem>

                                                <!-- Edit -->
                                                <DropdownMenuItem
                                                    @select="
                                                        openEditModal(ticket)
                                                    "
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
                                                                    'tickets.destroy',
                                                                    ticket.id,
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
                v-if="tickets.length > 0"
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
        <CreateModalTicket
            v-model:open="openModal"
            :mode="modalMode"
            :ticket="selectedTicket"
            :categories="props.categories"
        />
    </div>
</template>
