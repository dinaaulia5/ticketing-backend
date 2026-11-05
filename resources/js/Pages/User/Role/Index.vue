<script setup>
import { reactive, ref, computed } from "vue";

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
    IconPencil,
    IconPlus,
    IconShield,
    IconTrash,
    IconKey,
    IconLock,
} from "@tabler/icons-vue";

import { deleteAction } from "@/lib/utils";

import Tabs from "@/Components/ui/tabs/Tabs.vue";

import TabsList from "@/Components/ui/tabs/TabsList.vue";

import TabsTrigger from "@/Components/ui/tabs/TabsTrigger.vue";

import Create from "./Create.vue";
import RoleTab from "./Components/RoleTab.vue";
import PermissionTab from "./Components/PermissionTab.vue";
import AccessTab from "./Components/AccessTab.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    roles: Object,
    permissions: Object,
    pageSettings: Object,
    items: Array,
    state: Object,
});

/*
|--------------------------------------------------------------------------
| Active Tab
|--------------------------------------------------------------------------
*/

const activeTab = ref("role");

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/

const roleParams = reactive({
    ...props.state,
});

/*
|--------------------------------------------------------------------------
| Permission
|--------------------------------------------------------------------------
*/

const permissionParams = reactive({
    ...props.state,
});

/*
|--------------------------------------------------------------------------
| Sorting Role
|--------------------------------------------------------------------------
*/

const onSortRole = (field) => {
    roleParams.field = field;

    roleParams.direction = roleParams.direction === "asc" ? "desc" : "asc";

    router.get(route("roles.index"), roleParams, {
        preserveState: true,
        preserveScroll: true,
        only: ["roles"],
    });
};

/*
|--------------------------------------------------------------------------
| Sorting Permission
|--------------------------------------------------------------------------
*/

const onSortPermission = (field) => {
    permissionParams.field = field;

    permissionParams.direction =
        permissionParams.direction === "asc" ? "desc" : "asc";

    router.get(route("roles.index"), permissionParams, {
        preserveState: true,
        preserveScroll: true,
        only: ["permissions"],
    });
};

/*
|--------------------------------------------------------------------------
| Role Modal
|--------------------------------------------------------------------------
*/

const openModal = ref(false);

const modalMode = ref("create");

const selectedRole = ref(null);

/*
|--------------------------------------------------------------------------
| Create Role
|--------------------------------------------------------------------------
*/

const openCreateRoleModal = () => {
    modalMode.value = "create";

    selectedRole.value = null;

    openModal.value = true;
};

/*
|--------------------------------------------------------------------------
| Edit Role
|--------------------------------------------------------------------------
*/

const openEditRoleModal = (role) => {
    modalMode.value = "edit";

    selectedRole.value = role;

    openModal.value = true;
};
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head title="Manajemen Akses" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- Banner -->
        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- Card -->
        <Card>
            <CardHeader class="p-0">
                <div
                    class="flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconShield"
                    />

                    <Button
                        v-if="activeTab === 'role'"
                        variant="red"
                        size="xl"
                        @click="openCreateRoleModal"
                    >
                        <IconPlus class="size-4" />
                        Tambah Peran
                    </Button>

                    <Button
                        v-else-if="activeTab === 'permission'"
                        variant="red"
                        size="xl"
                        as-child
                    >
                        <Link :href="route('permissions.create')">
                            <IconPlus class="size-4" />
                            Tambah Izin
                        </Link>
                    </Button>
                </div>

                <div class="px-4 pb-3">
                    <Tabs v-model="activeTab" class="w-full">
                        <TabsList
                            class="inline-flex h-11 items-center gap-1 rounded-lg border bg-muted/40 p-1"
                        >
                            <!-- PERAN -->
                            <TabsTrigger
                                value="role"
                                class="h-9 min-w-[130px] gap-2 rounded-md px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 data-[state=active]:bg-blue-600 data-[state=active]:text-white data-[state=active]:shadow-sm"
                            >
                                <IconShield class="size-4" />
                                <span>Peran</span>
                            </TabsTrigger>

                            <!-- IZIN -->
                            <TabsTrigger
                                value="permission"
                                class="h-9 min-w-[130px] gap-2 rounded-md px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 data-[state=active]:bg-blue-600 data-[state=active]:text-white data-[state=active]:shadow-sm"
                            >
                                <IconKey class="size-4" />
                                <span>Izin</span>
                            </TabsTrigger>

                            <!-- HAK AKSES -->
                            <TabsTrigger
                                value="access"
                                class="h-9 min-w-[130px] gap-2 rounded-md px-4 text-sm font-medium text-muted-foreground transition-all duration-200 hover:bg-blue-50 hover:text-blue-600 data-[state=active]:bg-blue-600 data-[state=active]:text-white data-[state=active]:shadow-sm"
                            >
                                <IconLock class="size-4" />
                                <span>Hak Akses</span>
                            </TabsTrigger>
                        </TabsList>
                    </Tabs>
                </div>
            </CardHeader>

            <!-- ========================================= -->
            <!-- TAB PERAN -->
            <!-- ========================================= -->

            <RoleTab
                v-if="activeTab === 'role'"
                :roles="props.roles"
                :state="props.state"
                @edit="openEditRoleModal"
            />

            <!-- ========================================= -->
            <!-- TAB IZIN -->
            <!-- ========================================= -->

            <PermissionTab
                v-if="activeTab === 'permission'"
                :permissions="props.permissions"
                :state="props.state"
                @edit="openEditRoleModal"
            />

            <!-- ========================================= -->
            <!-- TAB HAK AKSES -->
            <!-- ========================================= -->

            <AccessTab v-if="activeTab === 'access'" :roles="props.roles" />
        </Card>

        <Create
            v-model:open="openModal"
            :mode="modalMode"
            :role="selectedRole"
        />
    </div>
</template>
