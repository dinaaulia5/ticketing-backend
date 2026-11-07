<script setup>
import { computed, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { toast } from "vue-sonner";

import { flashMessage } from "@/lib/utils";

import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Button } from "@/Components/ui/button";
import { Textarea } from "@/Components/ui/textarea";
import InputError from "@/Components/InputError.vue";

import {
    IconMessage,
    IconSend,
    IconBold,
    IconItalic,
    IconList,
    IconListNumbers,
    IconLink,
    IconInfoCircle,
} from "@tabler/icons-vue";

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },

    comments: {
        type: Array,
        default: () => [],
    },

    currentUser: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    comment: "",
    is_internal: false,
});

const commentCount = computed(() => props.comments.length);

const getInitials = (name) => {
    if (!name) return "?";

    return name
        .split(" ")
        .slice(0, 2)
        .map((word) => word.charAt(0))
        .join("")
        .toUpperCase();
};

const formatDate = (date) => {
    if (!date) return "-";

    return new Intl.DateTimeFormat("id-ID", {
        weekday: "short",
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
        timeZone: "Asia/Jakarta",
    }).format(new Date(date));
};

/*
|--------------------------------------------------------------------------
| Submit Comment
|--------------------------------------------------------------------------
*/

const submit = () => {
    form.post(route("tickets.comments.store", props.ticket.id), {
        preserveScroll: true,

        onSuccess: (page) => {
            const flash = flashMessage(page);

            if (flash) {
                toast[flash.type ?? "success"](flash.message);
            }

            form.reset();
            form.clearErrors();
        },

        onError: () => {
            toast.error("Komentar gagal dikirim.");
        },
    });
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <!-- Header -->
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconMessage class="size-5 text-cyan-500" />
                Komentar ({{ commentCount }})
            </CardTitle>
        </CardHeader>

        <CardContent>
            <div class="space-y-4 border-t pt-3">
                <!-- Comment List -->
                <div v-if="comments.length" class="space-y-4">
                    <div
                        v-for="item in comments"
                        :key="item.id"
                        class="flex gap-3"
                    >
                        <!-- Avatar -->
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-full text-sm font-medium"
                            :class="
                                item.is_internal
                                    ? 'bg-sky-100 text-sky-600'
                                    : 'bg-slate-100 text-slate-700'
                            "
                        >
                            {{ getInitials(item.user?.name) }}
                        </div>

                        <!-- Comment Content -->
                        <div class="min-w-0 flex-1 space-y-1">
                            <div
                                class="flex flex-wrap items-center justify-between gap-2"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold">
                                        {{ item.user?.name ?? "System" }}
                                    </span>

                                    <span
                                        v-if="item.is_internal"
                                        class="rounded-full bg-sky-100 px-3 py-0.5 text-xs font-medium text-blue-600"
                                    >
                                        Tim IT
                                    </span>

                                    <span
                                        v-else
                                        class="rounded-full bg-blue-50 px-3 py-0.5 text-xs font-medium text-blue-600"
                                    >
                                        Pelapor
                                    </span>
                                </div>

                                <span class="text-xs text-muted-foreground">
                                    {{ formatDate(item.created_at) }}
                                </span>
                            </div>

                            <div
                                class="rounded-lg bg-muted/60 px-3 py-2 text-sm leading-relaxed whitespace-pre-line"
                            >
                                {{ item.comment }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="py-5 text-center text-sm text-muted-foreground"
                >
                    Belum ada komentar pada tiket ini.
                </div>

                <!-- Comment Form -->
                <div class="space-y-3">
                    <div class="overflow-hidden rounded-lg border">
                        <!-- Toolbar -->
                        <div
                            class="flex items-center gap-1 border-b bg-muted/30 px-2 py-1"
                        >
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="size-8"
                                disabled
                                title="Bold"
                            >
                                <IconBold class="size-4" />
                            </Button>

                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="size-8"
                                disabled
                                title="Italic"
                            >
                                <IconItalic class="size-4" />
                            </Button>

                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="size-8"
                                disabled
                                title="Bullet List"
                            >
                                <IconList class="size-4" />
                            </Button>

                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="size-8"
                                disabled
                                title="Numbered List"
                            >
                                <IconListNumbers class="size-4" />
                            </Button>

                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="size-8"
                                disabled
                                title="Insert Link"
                            >
                                <IconLink class="size-4" />
                            </Button>
                        </div>

                        <!-- Textarea -->
                        <Textarea
                            v-model="form.comment"
                            placeholder="Tulis komentar..."
                            class="min-h-20 resize-y rounded-none border-0 shadow-none focus-visible:ring-0"
                            :disabled="form.processing"
                        />

                        <InputError :message="form.errors.comment" />
                    </div>

                    <!-- Footer -->
                    <div
                        class="flex flex-wrap items-center justify-between gap-3"
                    >
                        <!-- Internal Comment -->
                        <label
                            class="flex cursor-pointer items-center gap-2 text-xs text-muted-foreground"
                        >
                            <input
                                v-model="form.is_internal"
                                type="checkbox"
                                class="size-4 accent-cyan-500"
                                :disabled="form.processing"
                            />

                            Komentar Internal (hanya untuk tim IT)

                            <IconInfoCircle
                                class="size-4"
                                title="Komentar internal hanya dapat dilihat oleh tim IT."
                            />
                        </label>

                        <!-- Submit -->
                        <Button
                            type="button"
                            class="gap-2 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white hover:from-cyan-600 hover:to-cyan-700"
                            :disabled="!form.comment.trim() || form.processing"
                            @click="submit"
                        >
                            <IconSend class="size-4" />
                            {{
                                form.processing
                                    ? "Mengirim..."
                                    : "Kirim Komentar"
                            }}
                        </Button>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
