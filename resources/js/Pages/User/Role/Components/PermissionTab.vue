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
    IconArrowsDownUp,
    IconPencil,
    IconShield,
    IconTrash,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

const props = defineProps({
    permissions: Object,
    state: Object,
});

const emit = defineEmits(["edit"]);

const permissions = computed(() => props.permissions?.data ?? []);

const permissionMeta = computed(() => props.permissions?.meta ?? {});

const permissionLinks = computed(() => props.permissions?.links ?? []);

const permissionParams = reactive({
    ...props.state,
});

const onSortPermission = (field) => {
    permissionParams.field = field;

    permissionParams.direction =
        permissionParams.direction === "asc" ? "desc" : "asc";

    router.get(route("permissions.index"), permissionParams, {
        preserveState: true,
        preserveScroll: true,
        only: ["permissions"],
    });
};
</script>

<template>
    <!-- Filter permission -->
    <Filter
        v-model:params="permissionParams"
        :state="state"
        route-name="permissions.index"
        :only="['permissions', 'state']"
    />

    <ShowFilter :params="permissionParams" />

    <!-- Table permission -->
    <CardContent class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6">
        <!-- Empty -->
        <EmptyState
            v-if="permissions.length === 0"
            :icon="IconShield"
            title="Tidak ada Peran"
            subtitle="Mulailah dengan menambahkan data peran."
        />

        <!-- Table -->
        <Table v-else class="w-full">
            <TableHeader>
                <TableRow>
                    <TableHead>#</TableHead>

                    <TableHead>
                        <Button
                            variant="ghost"
                            class="inline-flex group"
                            @click="onSortPermission('name')"
                        >
                            Nama Peran

                            <IconArrowsDownUp class="ml-2 size-4" />
                        </Button>
                    </TableHead>

                    <TableHead>Slug</TableHead>

                    <TableHead>Deskripsi</TableHead>

                    <TableHead>Status</TableHead>

                    <TableHead>Dibuat</TableHead>

                    <TableHead>Aksi</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <TableRow
                    v-for="(permission, index) in permissions"
                    :key="permission.id"
                >
                    <!-- No -->
                    <TableCell>
                        {{
                            index +
                            1 +
                            (permissionMeta.current_page - 1) *
                                permissionMeta.per_page
                        }}
                    </TableCell>

                    <!-- Nama -->
                    <TableCell>
                        {{ permission.name }}
                    </TableCell>

                    <!-- Slug -->
                    <TableCell>
                        {{ permission.slug }}
                    </TableCell>

                    <!-- Deskripsi -->
                    <TableCell>
                        {{ permission.description ?? "-" }}
                    </TableCell>

                    <!-- Status -->
                    <TableCell>
                        <span
                            :class="
                                permission.is_active
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                            class="font-medium"
                        >
                            {{ permission.is_active ? "Aktif" : "Tidak Aktif" }}
                        </span>
                    </TableCell>

                    <!-- Dibuat -->
                    <TableCell>
                        {{ permission.created_at }}
                    </TableCell>

                    <!-- Aksi -->
                    <TableCell>
                        <div class="flex items-center gap-x-1">
                            <!-- Edit -->
                            <Button
                                variant="blue"
                                size="sm"
                                @click="emit('edit', permission)"
                            >
                                <IconPencil class="size-4" />
                            </Button>

                            <!-- Delete -->
                            <AlertAction
                                :action="
                                    () =>
                                        deleteAction(
                                            route(
                                                'permissions.destroy',
                                                permission.id,
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

    <!-- Footer Permission -->
    <CardFooter
        class="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row"
    >
        <p class="text-sm text-muted-foreground">
            Menampilkan

            <span class="font-medium text-cyan-600">
                {{ permissionMeta.from ?? 0 }}
            </span>

            dari

            {{ permissionMeta.total ?? 0 }}

            Peran
        </p>

        <div class="overflow-x-auto">
            <PaginationTable
                v-if="permissionMeta.has_pages"
                :meta="permissionMeta"
                :links="permissionLinks"
            />
        </div>
    </CardFooter>
</template>
