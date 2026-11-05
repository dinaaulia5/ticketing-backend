<script setup>
import { computed, ref, watch } from "vue";
import axios from "axios";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Checkbox } from "@/Components/ui/checkbox";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import {
    IconSearch,
    IconShield,
    IconUsers,
    IconBook,
    IconCategory,
    IconFileAnalytics,
    IconChevronDown,
    IconChevronRight,
    IconCheck,
    IconDeviceFloppy,
} from "@tabler/icons-vue";

const props = defineProps({
    roles: Object,
});

const roles = computed(() => props.roles?.data ?? []);

const selectedRole = ref(null);
const allPermissions = ref([]);
const rolePermissions = ref([]);

const roleSearch = ref("");
const permissionSearch = ref("");
const selectedModule = ref("all");

const loading = ref(false);
const saving = ref(false);

const expandedModules = ref({});

/*
|--------------------------------------------------------------------------
| Role
|--------------------------------------------------------------------------
*/

const filteredRoles = computed(() => {
    const keyword = roleSearch.value.toLowerCase().trim();

    if (!keyword) {
        return roles.value;
    }

    return roles.value.filter((role) =>
        role.name?.toLowerCase().includes(keyword),
    );
});

/*
|--------------------------------------------------------------------------
| Permission
|--------------------------------------------------------------------------
*/

const permissionModules = computed(() => {
    const grouped = {};

    allPermissions.value.forEach((permission) => {
        const [module, action] = permission.name.split(".");

        if (!module || !action) {
            return;
        }

        if (!grouped[module]) {
            grouped[module] = [];
        }

        grouped[module].push({
            ...permission,
            module,
            action,
        });
    });

    return grouped;
});

const modules = computed(() => Object.keys(permissionModules.value));

const filteredPermissionModules = computed(() => {
    const result = {};

    Object.entries(permissionModules.value).forEach(([module, permissions]) => {
        if (selectedModule.value !== "all" && selectedModule.value !== module) {
            return;
        }

        const filtered = permissions.filter((permission) =>
            permission.name
                ?.toLowerCase()
                .includes(permissionSearch.value.toLowerCase().trim()),
        );

        if (filtered.length) {
            result[module] = filtered;
        }
    });

    return result;
});

/*
|--------------------------------------------------------------------------
| Label
|--------------------------------------------------------------------------
*/

const moduleLabel = (module) => {
    const labels = {
        training: "Training",
        "training-type": "Training Type",
        "training-category": "Training Category",
        users: "User Management",
        user: "User Management",
        reports: "Reports",
    };

    return (
        labels[module] ??
        module
            .replace(/-/g, " ")
            .replace(/\b\w/g, (letter) => letter.toUpperCase())
    );
};

const actionLabel = (action) => {
    const labels = {
        view: "Lihat",
        create: "Tambah",
        update: "Ubah",
        delete: "Hapus",
        export: "Export",
        approve: "Approve",
        import: "Import",
    };

    return labels[action] ?? action.charAt(0).toUpperCase() + action.slice(1);
};

/*
|--------------------------------------------------------------------------
| Module Icon
|--------------------------------------------------------------------------
*/

const moduleIcon = (module) => {
    const icons = {
        training: IconBook,
        "training-type": IconBook,
        "training-category": IconCategory,
        users: IconUsers,
        user: IconUsers,
        reports: IconFileAnalytics,
    };

    return icons[module] ?? IconShield;
};

/*
|--------------------------------------------------------------------------
| Permission Check
|--------------------------------------------------------------------------
*/

const hasPermission = (permissionId) => {
    return rolePermissions.value.includes(permissionId);
};

const togglePermission = (permissionId, checked) => {
    if (checked) {
        if (!rolePermissions.value.includes(permissionId)) {
            rolePermissions.value.push(permissionId);
        }

        return;
    }

    rolePermissions.value = rolePermissions.value.filter(
        (id) => id !== permissionId,
    );
};

/*
|--------------------------------------------------------------------------
| Select All Module
|--------------------------------------------------------------------------
*/

const isModuleSelected = (permissions) => {
    return permissions.every((permission) => hasPermission(permission.id));
};

const toggleModule = (permissions, checked) => {
    const ids = permissions.map((permission) => permission.id);

    if (checked) {
        rolePermissions.value = [
            ...new Set([...rolePermissions.value, ...ids]),
        ];

        return;
    }

    rolePermissions.value = rolePermissions.value.filter(
        (id) => !ids.includes(id),
    );
};

/*
|--------------------------------------------------------------------------
| Select All
|--------------------------------------------------------------------------
*/

const allPermissionIds = computed(() =>
    allPermissions.value.map((permission) => permission.id),
);

const allSelected = computed(() => {
    return (
        allPermissionIds.value.length > 0 &&
        allPermissionIds.value.every((id) => rolePermissions.value.includes(id))
    );
});

const toggleAll = (checked) => {
    if (checked) {
        rolePermissions.value = [...allPermissionIds.value];
        return;
    }

    rolePermissions.value = [];
};

const clearAll = () => {
    rolePermissions.value = [];
};

/*
|--------------------------------------------------------------------------
| Expand Module
|--------------------------------------------------------------------------
*/

const isExpanded = (module) => {
    return expandedModules.value[module] ?? true;
};

const toggleModuleExpand = (module) => {
    expandedModules.value[module] = !isExpanded(module);
};

/*
|--------------------------------------------------------------------------
| Get Role Permissions
|--------------------------------------------------------------------------
*/

const getRolePermissions = async (roleId) => {
    if (!roleId) {
        allPermissions.value = [];
        rolePermissions.value = [];
        return;
    }

    loading.value = true;

    try {
        const response = await axios.get(route("roles.permissions", roleId));

        allPermissions.value = response.data.permissions ?? [];

        rolePermissions.value = [...(response.data.role_permissions ?? [])];
    } catch (error) {
        console.error("Gagal mengambil permission:", error);
    } finally {
        loading.value = false;
    }
};

watch(selectedRole, (roleId) => {
    getRolePermissions(roleId);
});

/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
|
| Endpoint penyimpanan kita buat setelah UI selesai.
|
*/

const savePermissions = async () => {
    if (!selectedRole.value) {
        return;
    }

    saving.value = true;

    try {
        await axios.put(route("roles.permissions.update", selectedRole.value), {
            permissions: rolePermissions.value,
        });

        await getRolePermissions(selectedRole.value);
    } catch (error) {
        console.error("Gagal menyimpan hak akses:", error);
    } finally {
        saving.value = false;
    }
};
</script>

<template>
    <div class="space-y-6 p-6">
        <!-- Header -->
        <div>
            <h2 class="text-lg font-semibold">Hak Akses</h2>

            <p class="text-sm text-muted-foreground">
                Atur permission yang dimiliki oleh setiap role.
            </p>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
            <!-- ================================================= -->
            <!-- ROLE -->
            <!-- ================================================= -->

            <div class="rounded-lg border bg-card">
                <div class="border-b p-4">
                    <div class="mb-1 flex items-center gap-2">
                        <IconShield class="size-5" />

                        <h3 class="font-semibold">Role</h3>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Pilih role yang ingin diatur.
                    </p>
                </div>

                <!-- Search Role -->
                <div class="border-b p-3">
                    <div class="relative">
                        <IconSearch
                            class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                        />

                        <Input
                            v-model="roleSearch"
                            placeholder="Cari role..."
                            class="pl-9"
                        />
                    </div>
                </div>

                <!-- Role List -->
                <div class="max-h-[520px] overflow-y-auto p-2">
                    <button
                        v-for="role in filteredRoles"
                        :key="role.id"
                        type="button"
                        class="mb-1 w-full rounded-md border border-transparent p-3 text-left transition hover:bg-muted"
                        :class="
                            selectedRole === role.id
                                ? 'border-primary bg-primary/5'
                                : ''
                        "
                        @click="selectedRole = role.id"
                    >
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-9 shrink-0 items-center justify-center rounded-md bg-muted"
                            >
                                <IconShield class="size-4" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-medium">
                                    {{ role.name }}
                                </div>

                                <p
                                    class="mt-1 line-clamp-2 text-xs text-muted-foreground"
                                >
                                    {{
                                        role.description ??
                                        "Tidak ada deskripsi."
                                    }}
                                </p>

                                <p class="mt-2 text-xs text-muted-foreground">
                                    {{ role.permissions_count ?? 0 }}
                                    permissions
                                </p>
                            </div>
                        </div>
                    </button>

                    <div
                        v-if="filteredRoles.length === 0"
                        class="py-8 text-center text-sm text-muted-foreground"
                    >
                        Tidak ada role.
                    </div>
                </div>
            </div>

            <!-- ================================================= -->
            <!-- PERMISSION -->
            <!-- ================================================= -->

            <div class="rounded-lg border bg-card">
                <!-- Permission Header -->
                <div class="border-b p-4">
                    <div
                        v-if="selectedRole"
                        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <h3 class="font-semibold">
                                {{
                                    roles.find(
                                        (role) => role.id === selectedRole,
                                    )?.name
                                }}
                            </h3>

                            <p class="text-xs text-muted-foreground">
                                Atur permission untuk role ini.
                            </p>
                        </div>

                        <div
                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                        >
                            Aktif
                        </div>
                    </div>

                    <div v-else class="flex items-center gap-3">
                        <div
                            class="flex size-10 items-center justify-center rounded-md bg-muted"
                        >
                            <IconShield class="size-5" />
                        </div>

                        <div>
                            <h3 class="font-semibold">Permission</h3>

                            <p class="text-sm text-muted-foreground">
                                Pilih role terlebih dahulu.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Belum pilih role -->
                <div
                    v-if="!selectedRole"
                    class="flex min-h-[400px] items-center justify-center p-6"
                >
                    <div class="text-center">
                        <IconShield
                            class="mx-auto mb-3 size-10 text-muted-foreground"
                        />

                        <h3 class="font-medium">Pilih Role</h3>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Pilih role di sebelah kiri untuk melihat permission.
                        </p>
                    </div>
                </div>

                <!-- Loading -->
                <div
                    v-else-if="loading"
                    class="flex min-h-[400px] items-center justify-center"
                >
                    <p class="text-sm text-muted-foreground">
                        Memuat permission...
                    </p>
                </div>

                <!-- Permission Content -->
                <template v-else>
                    <!-- Toolbar -->
                    <div
                        class="flex flex-col gap-3 border-b p-4 md:flex-row md:items-center"
                    >
                        <div class="relative flex-1">
                            <IconSearch
                                class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground"
                            />

                            <Input
                                v-model="permissionSearch"
                                placeholder="Cari permission..."
                                class="pl-9"
                            />
                        </div>

                        <Select v-model="selectedModule">
                            <SelectTrigger class="w-full md:w-[180px]">
                                <SelectValue placeholder="Semua Modul" />
                            </SelectTrigger>

                            <SelectContent>
                                <SelectItem value="all">
                                    Semua Modul
                                </SelectItem>

                                <SelectItem
                                    v-for="module in modules"
                                    :key="module"
                                    :value="module"
                                >
                                    {{ moduleLabel(module) }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Select All -->
                    <div
                        class="flex items-center justify-between border-b px-4 py-3"
                    >
                        <div class="flex items-center gap-2">
                            <Checkbox
                                :model-value="allSelected"
                                @update:model-value="toggleAll"
                            />

                            <span class="text-sm font-medium">
                                Pilih Semua Permission
                            </span>
                        </div>

                        <Button variant="ghost" size="sm" @click="clearAll">
                            Bersihkan
                        </Button>
                    </div>

                    <!-- Permission Groups -->
                    <div class="max-h-[520px] space-y-3 overflow-y-auto p-4">
                        <div
                            v-for="(
                                permissions, module
                            ) in filteredPermissionModules"
                            :key="module"
                            class="rounded-lg border"
                        >
                            <!-- Module Header -->
                            <div
                                class="flex items-center justify-between bg-muted/40 px-4 py-3"
                            >
                                <button
                                    type="button"
                                    class="flex items-center gap-2"
                                    @click="toggleModuleExpand(module)"
                                >
                                    <component
                                        :is="
                                            isExpanded(module)
                                                ? IconChevronDown
                                                : IconChevronRight
                                        "
                                        class="size-4"
                                    />

                                    <component
                                        :is="moduleIcon(module)"
                                        class="size-4"
                                    />

                                    <span class="text-sm font-semibold">
                                        {{ moduleLabel(module) }}
                                    </span>
                                </button>

                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-muted-foreground">
                                        {{
                                            permissions.filter((permission) =>
                                                hasPermission(permission.id),
                                            ).length
                                        }}/{{ permissions.length }}
                                    </span>

                                    <Checkbox
                                        :model-value="
                                            isModuleSelected(permissions)
                                        "
                                        @update:model-value="
                                            (value) =>
                                                toggleModule(permissions, value)
                                        "
                                    />
                                </div>
                            </div>

                            <!-- Permission List -->
                            <div
                                v-if="isExpanded(module)"
                                class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-2"
                            >
                                <label
                                    v-for="permission in permissions"
                                    :key="permission.id"
                                    class="flex cursor-pointer items-center gap-3 rounded-md px-3 py-2.5 transition hover:bg-muted"
                                >
                                    <Checkbox
                                        :model-value="
                                            hasPermission(permission.id)
                                        "
                                        @update:model-value="
                                            (value) =>
                                                togglePermission(
                                                    permission.id,
                                                    value,
                                                )
                                        "
                                    />

                                    <span class="text-sm">
                                        {{ actionLabel(permission.action) }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div
                            v-if="
                                Object.keys(filteredPermissionModules)
                                    .length === 0
                            "
                            class="py-12 text-center"
                        >
                            <IconShield
                                class="mx-auto mb-3 size-8 text-muted-foreground"
                            />

                            <p class="text-sm font-medium">
                                Tidak ada permission
                            </p>

                            <p class="mt-1 text-xs text-muted-foreground">
                                Coba ubah pencarian atau filter modul.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex flex-col gap-3 border-t bg-muted/20 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <p class="text-sm text-muted-foreground">
                            <span class="font-semibold text-foreground">
                                {{ rolePermissions.length }}
                            </span>
                            permission dipilih
                        </p>

                        <Button :disabled="saving" @click="savePermissions">
                            <IconDeviceFloppy class="mr-2 size-4" />

                            {{ saving ? "Menyimpan..." : "Simpan Perubahan" }}
                        </Button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
