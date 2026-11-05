<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputError from "@/Components/InputError.vue";

import { Alert, AlertDescription } from "@/Components/ui/alert";
import { Button } from "@/Components/ui/button";
import { Card, CardContent } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const props = defineProps({
    status: String,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const onHandleSubmit = () => {
    form.post(route("register"), {
        onFinish: () => {
            form.reset("password", "password_confirmation");
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="flex flex-col gap-6">
        <Card class="overflow-hidden">
            <CardContent class="grid p-0 md:grid-cols-2">
                <!-- FORM -->
                <form class="p-6 md:p-8" @submit.prevent="onHandleSubmit">
                    <div class="flex flex-col gap-6">
                        <!-- Logo -->
                        <div class="flex flex-col items-center text-center">
                            <ApplicationLogo />

                            <h1 class="mt-6 text-2xl font-bold leading-relaxed">
                                Selamat Datang
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Mulai daftar sekarang untuk menggunakan Travel
                                Agentic.
                            </p>

                            <Alert
                                v-if="status"
                                variant="success"
                                class="mt-4 w-full"
                            >
                                <AlertDescription>
                                    {{ status }}
                                </AlertDescription>
                            </Alert>
                        </div>

                        <!-- Name -->
                        <div class="grid gap-2">
                            <Label for="name"> Nama </Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Dina Nur Auliana"
                                autocomplete="name"
                                autofocus
                            />

                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div class="grid gap-2">
                            <Label for="email"> Email </Label>

                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="dina@email.com"
                                autocomplete="username"
                            />

                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Password -->
                        <div class="grid gap-2">
                            <Label for="password"> Password </Label>

                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="********"
                                autocomplete="new-password"
                            />

                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="grid gap-2">
                            <Label for="password_confirmation">
                                Konfirmasi Password
                            </Label>

                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="********"
                                autocomplete="new-password"
                            />

                            <InputError
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            variant="cyan"
                            class="w-full"
                            :disabled="form.processing"
                        >
                            Daftar
                        </Button>

                        <!-- Login -->
                        <div class="text-center text-sm">
                            Sudah memiliki akun?

                            <Link
                                :href="route('login')"
                                class="underline underline-offset-4"
                            >
                                Login
                            </Link>
                        </div>
                    </div>
                </form>

                <!-- Image -->
                <div class="relative hidden bg-muted md:block">
                    <img
                        src="/images/logo.png"
                        alt="Register"
                        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                    />
                </div>
            </CardContent>
        </Card>

        <div
            class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary"
        >
            Dengan mengklik lanjutkan, Anda menyetujui

            <Link href="#"> Persyaratan Layanan </Link>

            dan

            <Link href="#"> Kebijakan Privasi Kami </Link>
        </div>
    </div>
</template>
