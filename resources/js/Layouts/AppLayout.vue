<script setup>
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";

import Sidebar from "@/Layouts/Partials/Sidebar.vue";

import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

import { IconLayoutSidebar, IconX } from "@tabler/icons-vue";

import Avatar from "@/Components/ui/avatar/Avatar.vue";
import AvatarFallback from "@/Components/ui/avatar/AvatarFallback.vue";

import ThemeSwitcher from "@/Components/Dark/ThemeSwitcher.vue";
import { Toaster } from "@/Components/ui/sonner";

defineProps({
    title: {
        type: String,
        default: "",
    },
});

// ========================================
// MOBILE SIDEBAR
// ========================================
const sidebarOpen = ref(false);

// ========================================
// DESKTOP SIDEBAR
// ========================================
const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

// ========================================
// PAGE DATA
// ========================================
const page = usePage();

const auth = computed(() => page.props.auth?.user ?? null);
const url = computed(() => page.url);
</script>

<template>
    <div class="min-h-screen bg-white dark:bg-background">
        <Head :title="title" />

        <Toaster rich-colors position="top-center" />

        <!-- ================================== -->
        <!-- MOBILE & TABLET SIDEBAR -->
        <!-- ================================== -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                as="div"
                class="relative z-50 lg:hidden"
                @close="sidebarOpen = false"
            >
                <!-- Overlay -->
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-900/70"></div>
                </TransitionChild>

                <!-- Drawer -->
                <div class="fixed inset-0 flex">
                    <TransitionChild
                        as="template"
                        enter="transition ease-in-out duration-300 transform"
                        enter-from="-translate-x-full"
                        enter-to="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leave-from="translate-x-0"
                        leave-to="-translate-x-full"
                    >
                        <DialogPanel class="relative flex w-full max-w-sm">
                            <!-- Close button -->
                            <TransitionChild
                                as="template"
                                enter="ease-in-out duration-300"
                                enter-from="opacity-0"
                                enter-to="opacity-100"
                                leave="ease-in-out duration-300"
                                leave-from="opacity-100"
                                leave-to="opacity-0"
                            >
                                <div
                                    class="absolute left-full top-0 flex w-16 justify-center pt-5"
                                >
                                    <button
                                        type="button"
                                        class="-m-2.5 p-2.5 text-white"
                                        @click="sidebarOpen = false"
                                    >
                                        <IconX class="size-6" />
                                    </button>
                                </div>
                            </TransitionChild>

                            <!-- Mobile Sidebar Content -->
                            <div
                                class="flex min-h-0 grow flex-col overflow-y-auto bg-slate-50 px-4 py-4 dark:bg-background"
                            >
                                <Sidebar
                                    :auth="auth"
                                    :url="url"
                                    :collapsed="false"
                                    :collapsible="false"
                                />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- ================================== -->
        <!-- DESKTOP SIDEBAR -->
        <!-- ================================== -->
        <aside
            class="fixed inset-y-0 left-0 z-50 hidden flex-col overflow-hidden border-r border-slate-200 bg-slate-50 transition-[width] duration-300 ease-in-out dark:border-border dark:bg-background lg:flex"
            :class="sidebarCollapsed ? 'w-[72px]' : 'w-72'"
        >
            <div
                class="flex min-h-0 grow flex-col overflow-x-hidden overflow-y-auto py-4"
                :class="sidebarCollapsed ? 'px-2' : 'px-4'"
            >
                <Sidebar
                    :auth="auth"
                    :url="url"
                    :collapsed="sidebarCollapsed"
                    :collapsible="true"
                    @toggle-collapse="toggleSidebar"
                />
            </div>
        </aside>

        <!-- ================================== -->
        <!-- MOBILE & TABLET HEADER -->
        <!-- ================================== -->
        <header
            class="sticky top-0 z-40 flex items-center gap-x-4 border-b border-border bg-white p-4 shadow-sm dark:bg-background sm:px-6 lg:hidden"
        >
            <!-- Open mobile sidebar -->
            <button
                type="button"
                class="flex size-9 shrink-0 items-center justify-center rounded-lg text-foreground transition-colors hover:bg-muted"
                @click="sidebarOpen = true"
                aria-label="Buka sidebar"
            >
                <IconLayoutSidebar class="size-6" />
            </button>

            <!-- Page title -->
            <div
                class="min-w-0 flex-1 truncate text-sm font-semibold text-foreground"
            >
                {{ title }}
            </div>

            <!-- User avatar -->
            <Link href="#">
                <Avatar>
                    <AvatarFallback>
                        {{ auth?.name?.substring(0, 1) ?? "U" }}
                    </AvatarFallback>
                </Avatar>
            </Link>
        </header>

        <!-- ================================== -->
        <!-- MAIN CONTENT -->
        <!-- ================================== -->
        <main
            class="min-h-screen min-w-0 py-4 transition-[padding] duration-300 ease-in-out dark:bg-background"
            :class="sidebarCollapsed ? 'lg:pl-[72px]' : 'lg:pl-72'"
        >
            <div
                class="w-full min-w-0 max-w-full overflow-x-hidden px-4 sm:px-6 lg:px-8"
            >
                <slot />
            </div>

            <!-- Theme Switcher -->
            <div class="fixed bottom-5 right-5 z-30 flex justify-end">
                <ThemeSwitcher />
            </div>
        </main>
    </div>
</template>
