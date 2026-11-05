<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

import {
    IconLayoutDashboard,
    IconShield,
    IconChevronDown,
    IconKey,
    IconUsers,
    IconBriefcase,
    IconCategory,
    IconBook,
    IconBooks,
} from "@tabler/icons-vue";

import { can } from "@/lib/permissions";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },

    url: {
        type: String,
        default: "",
    },
});

const page = usePage();

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
| Mengasumsikan Laravel mengirim auth.roles sebagai array nama role.
*/

const isSuperAdmin = computed(() => {
    const roles = page.props.auth?.roles ?? [];

    return roles.includes("Super Admin") || roles.includes("super-admin");
});

/*
|--------------------------------------------------------------------------
| Permission helper
|--------------------------------------------------------------------------
*/

const hasPermission = (permission) => {
    return isSuperAdmin.value || can(permission);
};

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

const hasUserManagement = computed(() => {
    return hasPermission("users.view") || hasPermission("employees.view");
});

/*
|--------------------------------------------------------------------------
| Access Management
|--------------------------------------------------------------------------
*/

const hasAccessManagement = computed(() => {
    return hasPermission("roles.view") || hasPermission("permissions.view");
});

const accessManagementOpen = ref(
    props.url.startsWith("/roles") || props.url.startsWith("/permissions"),
);

/*
|--------------------------------------------------------------------------
| Master
|--------------------------------------------------------------------------
*/

const hasMaster = computed(() => {
    return (
        hasPermission("training-category.view") ||
        hasPermission("training-type.view") ||
        hasPermission("training.view")
    );
});
</script>

<template>
    <nav class="flex flex-1 flex-col gap-y-6 overflow-y-auto scrollbar-none">
        <!-- LOGO -->
        <ApplicationLogo url="#" />

        <!-- USER PROFILE -->
        <Card>
            <CardContent class="flex items-center gap-3 p-3">
                <Avatar>
                    <AvatarImage :src="auth?.avatar" />

                    <AvatarFallback>
                        {{ auth?.name?.substring(0, 1) }}
                    </AvatarFallback>
                </Avatar>

                <div class="flex min-w-0 flex-col">
                    <span
                        class="line-clamp-1 text-sm font-medium tracking-tight"
                    >
                        {{ auth?.name }}
                    </span>

                    <span class="line-clamp-1 text-xs text-muted-foreground">
                        {{ auth?.id }}
                    </span>
                </div>
            </CardContent>
        </Card>

        <!-- MENU -->
        <ul role="list" class="flex flex-1 flex-col gap-y-2">
            <!-- GENERAL -->
            <div class="px-3 py-2 text-sm font-medium text-muted-foreground">
                General
            </div>

            <NavLink
                title="Dashboard"
                :url="route('dashboard')"
                :active="url.startsWith('/dashboard')"
                :icon="IconLayoutDashboard"
            />

            <!-- USER MANAGEMENT -->
            <template v-if="hasUserManagement">
                <div
                    class="px-3 py-2 text-sm font-medium text-muted-foreground"
                >
                    User Management
                </div>

                <NavLink
                    v-if="hasPermission('users.view')"
                    title="Users"
                    :url="route('users.index')"
                    :active="url.startsWith('/users')"
                    :icon="IconUsers"
                />

                <NavLink
                    v-if="hasPermission('employees.view')"
                    title="Employees"
                    :url="route('employees.index')"
                    :active="url.startsWith('/employees')"
                    :icon="IconBriefcase"
                />
            </template>

            <!-- ACCESS MANAGEMENT -->
            <li v-if="hasAccessManagement">
                <button
                    type="button"
                    class="flex w-full items-center gap-x-3 rounded-md px-3 py-2 text-left text-foreground transition-colors hover:bg-muted"
                    @click="accessManagementOpen = !accessManagementOpen"
                >
                    <IconShield class="size-5 shrink-0" />

                    <div class="flex-1">Access Management</div>

                    <IconChevronDown
                        class="size-4 shrink-0 transition-transform duration-200"
                        :class="{
                            'rotate-180': accessManagementOpen,
                        }"
                    />
                </button>

                <div v-show="accessManagementOpen" class="mt-1 ml-8 space-y-1">
                    <NavLink
                        v-if="hasPermission('roles.view')"
                        title="Roles"
                        :url="route('roles.index')"
                        :active="url.startsWith('/roles')"
                        :icon="IconShield"
                    />

                    <NavLink
                        v-if="hasPermission('permissions.view')"
                        title="Permissions"
                        :url="route('permissions.index')"
                        :active="url.startsWith('/permissions')"
                        :icon="IconKey"
                    />
                </div>
            </li>

            <!-- MASTER -->
            <template v-if="hasMaster">
                <div
                    class="px-3 py-2 text-sm font-medium text-muted-foreground"
                >
                    Master
                </div>
            </template>
        </ul>
    </nav>
</template>
