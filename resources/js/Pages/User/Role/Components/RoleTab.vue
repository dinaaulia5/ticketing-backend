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
    roles: Object,
    state: Object,
});

const emit = defineEmits(["edit"]);

const roles = computed(() => props.roles?.data ?? []);

const roleMeta = computed(() => props.roles?.meta ?? {});

const roleLinks = computed(() => props.roles?.links ?? []);

const roleParams = reactive({
    ...props.state,
});

const onSortRole = (field) => {
    roleParams.field = field;

    roleParams.direction = roleParams.direction === "asc" ? "desc" : "asc";

    router.get(route("roles.index"), roleParams, {
        preserveState: true,
        preserveScroll: true,
        only: ["roles"],
    });
};
</script>

<template>
    <!-- Filter Role -->
    <Filter
        v-model:params="roleParams"
        :state="state"
        route-name="roles.index"
        :only="['roles', 'state']"
    />

    <ShowFilter :params="roleParams" />

    <!-- Table Role -->
    <CardContent class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6">
        <!-- Empty -->
        <EmptyState
            v-if="roles.length === 0"
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
                            @click="onSortRole('name')"
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
                <TableRow v-for="(role, index) in roles" :key="role.id">
                    <!-- No -->
                    <TableCell>
                        {{
                            index +
                            1 +
                            (roleMeta.current_page - 1) * roleMeta.per_page
                        }}
                    </TableCell>

                    <!-- Nama -->
                    <TableCell>
                        {{ role.name }}
                    </TableCell>

                    <!-- Slug -->
                    <TableCell>
                        {{ role.slug }}
                    </TableCell>

                    <!-- Deskripsi -->
                    <TableCell>
                        {{ role.description ?? "-" }}
                    </TableCell>

                    <!-- Status -->
                    <TableCell>
                        <span
                            :class="
                                role.is_active
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                            class="font-medium"
                        >
                            {{ role.is_active ? "Aktif" : "Tidak Aktif" }}
                        </span>
                    </TableCell>

                    <!-- Dibuat -->
                    <TableCell>
                        {{ role.created_at }}
                    </TableCell>

                    <!-- Aksi -->
                    <TableCell>
                        <div class="flex items-center gap-x-1">
                            <!-- Edit -->
                            <Button
                                variant="blue"
                                size="sm"
                                @click="emit('edit', role)"
                            >
                                <IconPencil class="size-4" />
                            </Button>

                            <!-- Delete -->
                            <AlertAction
                                :action="
                                    () =>
                                        deleteAction(
                                            route('roles.destroy', role.id),
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

    <!-- Footer Role -->
    <CardFooter
        class="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row"
    >
        <p class="text-sm text-muted-foreground">
            Menampilkan

            <span class="font-medium text-cyan-600">
                {{ roleMeta.from ?? 0 }}
            </span>

            dari

            {{ roleMeta.total ?? 0 }}

            Peran
        </p>

        <div class="overflow-x-auto">
            <PaginationTable
                v-if="roleMeta.has_pages"
                :meta="roleMeta"
                :links="roleLinks"
            />
        </div>
    </CardFooter>
</template>
