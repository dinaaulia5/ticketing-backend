<script setup>
import { reactive, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

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
    IconBook,
    IconEye,
    IconFile,
    IconPencil,
    IconPlane,
    IconPlus,
    IconStack2,
    IconTrash,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    trainings: {
        type: Object,
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

const trainings = computed(() => props.trainings.data ?? []);
const meta = computed(() => props.trainings.meta ?? {});
const links = computed(() => props.trainings.links ?? []);

const params = reactive({
    ...props.state,
});

const onSortTable = (field) => {
    params.field = field;
    params.direction =
        params.field === field && params.direction === "asc" ? "desc" : "asc";

    router.get(route("trainings.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["trainings"],
    });
};

const formatDate = (date) => {
    if (!date) return "-";

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) return date;

    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    }).format(parsedDate);
};

const statusClass = (status) => {
    const styles = {
        DRAFT: "bg-amber-500/15 text-amber-400 ring-1 ring-amber-500/30",
        PUBLISHED:
            "bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/30",
        ONGOING: "bg-cyan-500/15 text-cyan-400 ring-1 ring-cyan-500/30",
        COMPLETED: "bg-blue-500/15 text-blue-400 ring-1 ring-blue-500/30",
        CANCELLED: "bg-red-500/15 text-red-400 ring-1 ring-red-500/30",
    };

    return (
        styles[String(status ?? "").toUpperCase()] ??
        "bg-muted text-muted-foreground"
    );
};
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <Head title="Training Master" />

        <BreadcrumbHeader :items="items" />

        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <Card class="overflow-hidden">
            <CardHeader class="space-y-4 p-0">
                <div
                    class="flex flex-col items-start justify-between gap-4 p-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconPlane"
                    />

                    <Button variant="red" size="xl" as-child>
                        <Link :href="route('trainings.create')">
                            <IconPlus class="size-4" />
                            Tambah Pelatihan
                        </Link>
                    </Button>
                </div>

                <Filter v-model:params="params" :state="state" />
                <ShowFilter :params="params" />
            </CardHeader>

            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-4 [&_th]:px-4"
            >
                <EmptyState
                    v-if="trainings.length === 0"
                    :icon="IconBook"
                    title="Belum Ada Pelatihan"
                    subtitle="Tambahkan pelatihan untuk mulai menyusun kurikulum dan materi pembelajaran."
                />

                <div v-else class="w-full overflow-x-auto">
                    <Table class="w-full">
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12">#</TableHead>

                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex"
                                        @click="onSortTable('code')"
                                    >
                                        Kode
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex"
                                        @click="onSortTable('title')"
                                    >
                                        Nama Pelatihan
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <TableHead>Jumlah Modul</TableHead>
                                <TableHead>Jumlah Materi</TableHead>
                                <TableHead>Status</TableHead>

                                <TableHead>
                                    <Button
                                        variant="ghost"
                                        class="inline-flex"
                                        @click="onSortTable('created_at')"
                                    >
                                        Dibuat
                                        <IconArrowsDownUp class="ml-2 size-4" />
                                    </Button>
                                </TableHead>

                                <TableHead class="text-center">
                                    Aksi
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="(training, index) in trainings"
                                :key="training.id"
                            >
                                <TableCell>
                                    {{
                                        index +
                                        1 +
                                        ((meta.current_page ?? 1) - 1) *
                                            (meta.per_page ?? 10)
                                    }}
                                </TableCell>

                                <TableCell class="font-medium">
                                    {{ training.code }}
                                </TableCell>

                                <TableCell>
                                    <div
                                        class="min-w-48 max-w-72 whitespace-normal"
                                    >
                                        <p class="font-medium">
                                            {{ training.title }}
                                        </p>
                                        <p
                                            v-if="training.description"
                                            class="mt-1 line-clamp-2 text-xs text-muted-foreground"
                                        >
                                            {{ training.description }}
                                        </p>
                                    </div>
                                </TableCell>

                                <TableCell>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-cyan-500/10 px-2.5 py-1 text-xs font-medium text-cyan-600 dark:text-cyan-400"
                                    >
                                        <IconFiles class="size-3.5" />
                                        {{ training.modules_count ?? 0 }} Modul
                                    </span>
                                </TableCell>

                                <TableCell>
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-medium text-blue-600 dark:text-blue-400"
                                    >
                                        <IconFiles class="size-3.5" />
                                        {{ training.materials_count ?? 0 }}
                                        Materi
                                    </span>
                                </TableCell>

                                <TableCell>
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="statusClass(training.status)"
                                    >
                                        {{ training.status ?? "-" }}
                                    </span>
                                </TableCell>

                                <TableCell>
                                    {{ formatDate(training.created_at) }}
                                </TableCell>

                                <TableCell>
                                    <div
                                        class="flex items-center justify-center gap-1.5"
                                    >
                                        <Button
                                            variant="cyan"
                                            size="sm"
                                            as-child
                                            title="Detail pelatihan"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'trainings.show',
                                                        training.id,
                                                    )
                                                "
                                            >
                                                <IconEye class="size-4" />
                                            </Link>
                                        </Button>

                                        <Button
                                            variant="blue"
                                            size="sm"
                                            as-child
                                            title="Edit pelatihan"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'trainings.edit',
                                                        training.id,
                                                    )
                                                "
                                            >
                                                <IconPencil class="size-4" />
                                            </Link>
                                        </Button>

                                        <AlertAction
                                            :action="
                                                () =>
                                                    deleteAction(
                                                        route(
                                                            'trainings.destroy',
                                                            training.id,
                                                        ),
                                                    )
                                            "
                                        >
                                            <Button
                                                variant="red"
                                                size="sm"
                                                title="Hapus pelatihan"
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

            <CardFooter
                class="flex w-full flex-col items-center justify-between gap-y-2 border-t py-3 lg:flex-row"
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
                    Pelatihan
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
    </div>
</template>
