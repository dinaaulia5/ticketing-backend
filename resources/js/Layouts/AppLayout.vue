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

const sidebarOpen = ref(false);

const page = usePage();

const auth = computed(() => page.props.auth.user);
const url = computed(() => page.url);
</script>

<template>
    <div>
        <Head :title="title" />
        <Toaster rich-colors position="top-center" />

        <!-- Mobile Sidebar -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                as="div"
                class="relative z-50 lg:hidden"
                @close="sidebarOpen = false"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-gray-900/80"></div>
                </TransitionChild>

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
                        <DialogPanel
                            class="relative flex flex-1 w-full max-w-sm mr-16"
                        >
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
                                    class="absolute top-0 flex justify-center w-16 pt-5 left-full"
                                >
                                    <button
                                        type="button"
                                        class="-m-2.5 p-2.5"
                                        @click="sidebarOpen = false"
                                    >
                                        <IconX class="text-white size-6" />
                                    </button>
                                </div>
                            </TransitionChild>

                            <div
                                class="flex flex-col px-6 pb-2 overflow-y-auto bg-white grow gap-y-5 dark:bg-background"
                            >
                                <Sidebar :auth="auth" :url="url" />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Sidebar Desktop -->
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col"
        >
            <div
                class="flex flex-col px-4 overflow-y-auto grow gap-y-5 bg-slate-50 dark:border-r dark:border-r-card dark:bg-background"
            >
                <Sidebar :auth="auth" :url="url" />
            </div>
        </div>

        <!-- Header Mobile -->
        <div
            class="sticky top-0 z-40 flex items-center p-4 bg-white shadow-sm gap-x-6 dark:bg-background sm:px-6 lg:hidden"
        >
            <button
                type="button"
                class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                @click="sidebarOpen = true"
            >
                <IconLayoutSidebar class="size-6" />
            </button>

            <div class="flex-1 text-sm font-semibold leading-6 text-foreground">
                {{ title }}
            </div>

            <Link href="#">
                <Avatar>
                    <AvatarFallback>
                        {{ auth?.name?.substring(0, 1) ?? "X" }}
                    </AvatarFallback>
                </Avatar>
            </Link>
        </div>

        <!-- Main -->
        <main class="min-h-screen py-4 dark:bg-background lg:pl-72">
            <div
                class="w-full max-w-full overflow-x-hidden px-4 sm:px-6 lg:px-8"
            >
                <slot />
            </div>

            <div
                class="fixed z-30 flex w-full justify-center bottom-5 end-5 lg:w-auto lg:justify-end lg:px-8"
            >
                <ThemeSwitcher />
            </div>
        </main>
    </div>
</template>
