<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

import { computed, nextTick, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

import {
    IconLayoutDashboard,
    IconShield,
    IconChevronDown,
    IconKey,
    IconUsers,
    IconBriefcase,
    IconTicket,
    IconCategory,
    IconLayoutSidebar,
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

    collapsed: {
        type: Boolean,
        default: false,
    },

    collapsible: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["toggle-collapse"]);

const page = usePage();

// ========================================
// ACCESS MANAGEMENT POPUP
// ========================================

const accessPopupOpen = ref(false);
const accessTriggerRef = ref(null);

const accessPopupStyle = ref({
    top: "0px",
    left: "0px",
});

const toggleAccessPopup = async () => {
    if (accessPopupOpen.value) {
        accessPopupOpen.value = false;
        return;
    }

    await nextTick();

    const rect = accessTriggerRef.value?.getBoundingClientRect();

    if (!rect) return;

    // Perkiraan tinggi popup
    const popupHeight = 150;

    // Posisi vertikal mengikuti tombol Shield
    const top = Math.min(
        Math.max(rect.top, 12),
        window.innerHeight - popupHeight - 12,
    );

    accessPopupStyle.value = {
        top: `${top}px`,
        left: `${rect.right + 12}px`,
    };

    accessPopupOpen.value = true;
};

// Tutup popup ketika sidebar kembali dibuka
watch(
    () => props.collapsed,
    (collapsed) => {
        if (!collapsed) {
            accessPopupOpen.value = false;
        }
    },
);

// ========================================
// ROLE & PERMISSION
// ========================================

const isSuperAdmin = computed(() => {
    const roles = page.props.auth?.roles ?? [];

    return roles.includes("Super Admin") || roles.includes("super-admin");
});

const hasPermission = (permission) => {
    return isSuperAdmin.value || can(permission);
};

// ========================================
// USER MANAGEMENT
// ========================================

const hasUserManagement = computed(() => {
    return hasPermission("users.view") || hasPermission("employees.view");
});

// ========================================
// ACCESS MANAGEMENT
// ========================================

const hasAccessManagement = computed(() => {
    return hasPermission("roles.view") || hasPermission("permissions.view");
});

const accessManagementOpen = ref(
    props.url.startsWith("/roles") || props.url.startsWith("/permissions"),
);

// ========================================
// MASTER
// ========================================

const hasMaster = computed(() => {
    return hasPermission("categories.view") || hasPermission("tickets.view");
});

// Buka dropdown otomatis ketika masuk halaman terkait
watch(
    () => props.url,
    (newUrl) => {
        if (newUrl.startsWith("/roles") || newUrl.startsWith("/permissions")) {
            accessManagementOpen.value = true;
        }
    },
);
</script>

<template>
    <nav
        class="flex flex-1 flex-col gap-y-6 overflow-y-auto scrollbar-none"
        :class="collapsed ? 'items-center' : ''"
    >
        <!-- LOGO & COLLAPSE BUTTON -->
        <div
            class="flex w-full shrink-0 items-center"
            :class="collapsed ? 'justify-center' : 'justify-between'"
        >
            <!-- Logo saat sidebar terbuka -->
            <div v-if="!collapsed" class="min-w-0 flex-1">
                <ApplicationLogo url="#" />
            </div>

            <!-- Logo saat sidebar tertutup -->
            <button
                v-else
                type="button"
                class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500 text-white transition-colors hover:bg-cyan-600"
                title="Buka sidebar"
                @click="emit('toggle-collapse')"
            >
                <IconTicket class="size-6" />
            </button>

            <!-- Tombol tutup sidebar -->
            <button
                v-if="collapsible && !collapsed"
                type="button"
                class="flex size-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                title="Tutup sidebar"
                @click="emit('toggle-collapse')"
            >
                <IconLayoutSidebar class="size-5" />
            </button>
        </div>

        <!-- USER PROFILE -->
        <Card v-if="!collapsed" class="w-full border-border/70 shadow-none">
            <CardContent class="flex items-center gap-3 p-3">
                <Avatar class="shrink-0">
                    <AvatarImage :src="auth?.avatar" />

                    <AvatarFallback>
                        {{ auth?.name?.substring(0, 1) ?? "U" }}
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

        <!-- USER AVATAR COLLAPSED -->
        <div v-else class="flex w-full justify-center">
            <Avatar class="size-10">
                <AvatarImage :src="auth?.avatar" />

                <AvatarFallback>
                    {{ auth?.name?.substring(0, 1) ?? "U" }}
                </AvatarFallback>
            </Avatar>
        </div>

        <!-- MENU -->
        <ul role="list" class="flex w-full flex-1 flex-col gap-y-2">
            <!-- GENERAL -->
            <li
                v-if="!collapsed"
                class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground"
            >
                General
            </li>

            <NavLink
                title="Dashboard"
                :url="route('dashboard')"
                :active="url.startsWith('/dashboard')"
                :icon="IconLayoutDashboard"
                :collapsed="collapsed"
            />

            <!-- USER MANAGEMENT -->
            <template v-if="hasUserManagement">
                <li
                    v-if="!collapsed"
                    class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground"
                >
                    User Management
                </li>

                <NavLink
                    v-if="hasPermission('users.view')"
                    title="Users"
                    :url="route('users.index')"
                    :active="url.startsWith('/users')"
                    :icon="IconUsers"
                    :collapsed="collapsed"
                />

                <NavLink
                    v-if="hasPermission('employees.view')"
                    title="Employees"
                    :url="route('employees.index')"
                    :active="url.startsWith('/employees')"
                    :icon="IconBriefcase"
                    :collapsed="collapsed"
                />
            </template>

            <!-- ACCESS MANAGEMENT -->
            <li v-if="hasAccessManagement" class="relative w-full">
                <button
                    ref="accessTriggerRef"
                    type="button"
                    class="flex w-full items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm text-foreground transition-colors hover:bg-muted"
                    :class="collapsed ? 'justify-center px-0' : ''"
                    :title="collapsed ? 'Access Management' : undefined"
                    @click.stop="
                        collapsed
                            ? toggleAccessPopup()
                            : (accessManagementOpen = !accessManagementOpen)
                    "
                >
                    <IconShield class="size-5 shrink-0" />

                    <span v-if="!collapsed" class="flex-1 text-left">
                        Access Management
                    </span>

                    <IconChevronDown
                        v-if="!collapsed"
                        class="size-4 shrink-0 transition-transform duration-200"
                        :class="{
                            'rotate-180': accessManagementOpen,
                        }"
                    />
                </button>

                <!-- DROPDOWN SAAT SIDEBAR TERBUKA -->
                <Transition
                    enter-active-class="transition-all duration-200 ease-out"
                    enter-from-class="opacity-0 -translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-150 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-1"
                >
                    <div
                        v-if="!collapsed && accessManagementOpen"
                        class="ml-8 mt-1 space-y-1"
                    >
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
                </Transition>
            </li>

            <!-- MASTER -->
            <template v-if="hasMaster">
                <li
                    v-if="!collapsed"
                    class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground"
                >
                    Master
                </li>

                <NavLink
                    v-if="hasPermission('categories.view')"
                    title="Category & Subsystem"
                    :url="route('categories.index')"
                    :active="url.startsWith('/categories')"
                    :icon="IconCategory"
                    :collapsed="collapsed"
                />

                <NavLink
                    v-if="hasPermission('tickets.view')"
                    title="Ticket"
                    :url="route('tickets.index')"
                    :active="url.startsWith('/tickets')"
                    :icon="IconTicket"
                    :collapsed="collapsed"
                />
            </template>
        </ul>
    </nav>

    <!-- POPUP ACCESS MANAGEMENT SAAT SIDEBAR COLLAPSED -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-x-[-6px] scale-95"
            enter-to-class="opacity-100 translate-x-0 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-x-0 scale-100"
            leave-to-class="opacity-0 translate-x-[-6px] scale-95"
        >
            <div
                v-if="collapsed && accessPopupOpen"
                :style="accessPopupStyle"
                class="fixed z-[9999] w-64 rounded-2xl border border-border bg-background p-3 shadow-xl"
                @click.stop
            >
                <div
                    class="mb-2 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground"
                >
                    Access Management
                </div>

                <div class="space-y-1">
                    <NavLink
                        v-if="hasPermission('roles.view')"
                        title="Roles"
                        :url="route('roles.index')"
                        :active="url.startsWith('/roles')"
                        :icon="IconShield"
                        @click="accessPopupOpen = false"
                    />

                    <NavLink
                        v-if="hasPermission('permissions.view')"
                        title="Permissions"
                        :url="route('permissions.index')"
                        :active="url.startsWith('/permissions')"
                        :icon="IconKey"
                        @click="accessPopupOpen = false"
                    />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
