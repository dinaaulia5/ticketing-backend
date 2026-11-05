<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import AuthLayout from "@/Layouts/AuthLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import InputError from "@/Components/InputError.vue";

import { Alert, AlertDescription } from "@/Components/ui/alert";
import { Button } from "@/Components/ui/button";
import { Card, CardContent } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

defineOptions({
    layout: AuthLayout,
});

const props = defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },

    status: {
        type: String,
        default: "",
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const login = () => {
    form.post(route("login"), {
        onStart: () => {
            form.clearErrors();
        },

        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Login Berhasil",
                text: "Selamat datang kembali!",
                timer: 1200,
                showConfirmButton: false,
            });
        },

        onError: () => {
            Swal.fire({
                icon: "error",
                title: "Login Gagal",
                text:
                    form.errors.email ||
                    form.errors.password ||
                    "Email atau Password salah.",
            });
        },

        onFinish: () => {
            form.reset("password");
        },
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="flex flex-col gap-6">
        <Card class="overflow-hidden">
            <CardContent class="grid p-0 md:grid-cols-2">
                <!-- FORM -->
                <form class="p-6 md:p-8" @submit.prevent="login">
                    <div class="flex flex-col gap-6">
                        <!-- Logo & Heading -->
                        <div class="flex flex-col items-center text-center">
                            <ApplicationLogo />

                            <h1 class="mt-6 text-2xl font-bold leading-relaxed">
                                Selamat Datang
                            </h1>

                            <p class="text-sm text-muted-foreground">
                                Masuk ke platform My Money untuk mengelola
                                keuangan Anda
                            </p>

                            <!-- Status -->
                            <Alert
                                v-if="props.status"
                                variant="success"
                                class="mt-4 w-full"
                            >
                                <AlertDescription>
                                    {{ props.status }}
                                </AlertDescription>
                            </Alert>
                        </div>

                        <!-- Email -->
                        <div class="grid gap-2">
                            <Label for="email"> Email </Label>

                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="test@example.com"
                                autocomplete="username"
                                autofocus
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Password -->
                        <div class="grid gap-2">
                            <div class="flex items-center">
                                <Label for="password"> Password </Label>

                                <Link
                                    v-if="props.canResetPassword"
                                    :href="route('password.request')"
                                    class="ml-auto text-sm underline-offset-4 hover:underline"
                                >
                                    Lupa Password
                                </Link>
                            </div>

                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="********"
                                autocomplete="current-password"
                                :disabled="form.processing"
                            />

                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center gap-2">
                            <input
                                id="remember"
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded border-gray-300"
                                :disabled="form.processing"
                            />

                            <Label for="remember"> Ingat Saya </Label>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            variant="cyan"
                            class="w-full"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? "Loading..." : "Login" }}
                        </Button>
                    </div>
                </form>

                <!-- IMAGE -->
                <div class="relative hidden bg-muted md:block">
                    <img
                        src="/images/logo.png"
                        alt="Login"
                        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                    />
                </div>
            </CardContent>
        </Card>

        <!-- Footer -->
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
