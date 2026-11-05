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
    IconPlane,
    IconPlus,
    IconTrash,
} from "@tabler/icons-vue";
import EmptyState from "@/Components/EmptyState.vue";
import { Tab } from "@headlessui/vue";

import { deleteAction } from "@/lib/utils";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    trainings: Object,
    pageSettings: Object,
    items: Array,
    state: Object,
});

const trainings = computed(() => props.trainings.data);
const meta = computed(() => props.trainings.meta);
const links = computed(() => props.trainings.links);

const params = reactive({
    ...props.state,
});

const onSortTable = (field) => {
    params.field = field;
    params.direction = params.direction === "asc" ? "desc" : "asc";

    router.get(route("trainings.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["trainings"],
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
    selectedCategory.value = trainings;
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
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <EmptyState
                    v-if="trainings.length === 0"
                    :icon="IconPlane"
                    title="Tidak ada Pelatihan"
                    subtitle="Mulailah dengan menambahkan data Pelatihan"
                />

                <!-- table Pelatihan -->
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
                            v-for="(training, index) in trainings"
                            :key="training.id"
                        >
                            <TableCell>
                                {{
                                    index +
                                    1 +
                                    (meta.current_page - 1) * meta.per_page
                                }}
                            </TableCell>

                            <TableCell class="font-medium">
                                {{ training.code }}
                            </TableCell>

                            <TableCell>
                                {{ training.name }}
                            </TableCell>

                            <TableCell>
                                {{ training.description }}
                            </TableCell>

                            <TableCell>
                                {{ training.created_at }}
                            </TableCell>

                            <TableCell>
                                <div class="flex items-center gap-x-1">
                                    <Button variant="blue" size="sm">
                                        <IconPencil class="size-4" />
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
                    dari {{ meta.total }} Pelatihan
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
            :training="selectedCategory"
        />
    </div>
</template>
