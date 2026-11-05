<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { Button } from "@/Components/ui/button";

import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";

import { Menu } from "lucide-vue-next";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";

import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <header class="sticky top-0 z-50 border-b bg-background/95 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo -->
                <Link
                    href="/"
                    class="flex items-center gap-2 font-bold text-lg"
                >
                    <span>✈️</span>
                    <span>FlightHub</span>
                </Link>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center gap-8">
                    <Link href="/"> Home </Link>

                    <template v-if="user">
                        <Link href="/my-bookings"> My Bookings </Link>
                    </template>
                </nav>

                <!-- Desktop Auth -->
                <div class="hidden lg:flex items-center gap-2">
                    <template v-if="!user">
                        <Button variant="ghost" as-child>
                            <Link :href="route('login')"> Login </Link>
                        </Button>

                        <Button as-child>
                            <Link :href="route('register')"> Register </Link>
                        </Button>
                    </template>

                    <template v-else>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button
                                    class="flex items-center gap-2 rounded-md px-2 py-1 hover:bg-muted"
                                >
                                    <Avatar class="h-8 w-8">
                                        <AvatarImage :src="user?.avatar" />

                                        <AvatarFallback>
                                            {{ user?.name?.substring(0, 1) }}
                                        </AvatarFallback>
                                    </Avatar>

                                    <span class="text-sm font-medium">
                                        {{ user?.name }}
                                    </span>
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent align="end" class="w-56">
                                <DropdownMenuLabel>
                                    Akun Saya
                                </DropdownMenuLabel>

                                <DropdownMenuSeparator />

                                <DropdownMenuItem as-child>
                                    <Link :href="route('profile.edit')">
                                        Profile
                                    </Link>
                                </DropdownMenuItem>

                                <DropdownMenuItem as-child>
                                    <Link href="/my-bookings">
                                        My Bookings
                                    </Link>
                                </DropdownMenuItem>

                                <DropdownMenuItem as-child>
                                    <Link href="/my-recommendations">
                                        My Recommendations
                                    </Link>
                                </DropdownMenuItem>

                                <DropdownMenuSeparator />

                                <DropdownMenuItem as-child>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Logout
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </template>
                </div>

                <!-- Mobile -->
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="ghost" size="icon" class="lg:hidden">
                            <Menu class="h-5 w-5" />
                        </Button>
                    </SheetTrigger>

                    <SheetContent side="right" class="w-[300px]">
                        <div class="mt-8 flex flex-col gap-6">
                            <div
                                class="flex items-center gap-2 font-bold text-lg"
                            >
                                ✈️ FlightHub
                            </div>

                            <nav class="flex flex-col gap-1">
                                <Link
                                    href="#"
                                    class="rounded-md px-3 py-2 hover:bg-muted"
                                >
                                    Home
                                </Link>
                                <Link
                                    href="#"
                                    class="rounded-md px-3 py-2 hover:bg-muted"
                                >
                                    Dashboard
                                </Link>

                                <Link
                                    href="#"
                                    class="rounded-md px-3 py-2 hover:bg-muted"
                                >
                                    Flights
                                </Link>

                                <Link
                                    href="#"
                                    class="rounded-md px-3 py-2 hover:bg-muted"
                                >
                                    My Bookings
                                </Link>
                            </nav>

                            <div class="flex flex-col gap-2">
                                <Button variant="outline"> Login </Button>

                                <Button> Register </Button>
                            </div>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </header>
</template>
