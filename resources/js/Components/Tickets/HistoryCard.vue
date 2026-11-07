<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import {
    IconHistory,
    IconPlus,
    IconPaperclip,
    IconMessage,
    IconRefresh,
    IconUserCheck,
    IconCircleCheck,
} from "@tabler/icons-vue";

defineProps({
    histories: {
        type: Array,
        default: () => [],
    },
});

const activityLabels = {
    created: "Tiket dibuat",
    attachment_uploaded: "Lampiran diunggah",
    comment_added: "Komentar ditambahkan",
    status_changed: "Status tiket diubah",
    assignment_created: "PIC ditugaskan",
    priority_changed: "Prioritas diubah",
};

const activityIcons = {
    created: IconPlus,
    attachment_uploaded: IconPaperclip,
    comment_added: IconMessage,
    status_changed: IconRefresh,
    assignment_created: IconUserCheck,
    priority_changed: IconCircleCheck,
};

const iconColors = {
    created: "bg-cyan-50 text-cyan-500",
    attachment_uploaded: "bg-slate-100 text-slate-600",
    comment_added: "bg-blue-50 text-blue-500",
    status_changed: "bg-amber-50 text-amber-500",
    assignment_created: "bg-emerald-50 text-emerald-500",
    priority_changed: "bg-purple-50 text-purple-500",
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
    }).format(new Date(date));
};

const getActivityLabel = (activity) => {
    return activityLabels[activity] ?? activity;
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconHistory class="size-5 text-cyan-500" />
                Riwayat Aktivitas
            </CardTitle>
        </CardHeader>

        <CardContent>
            <div class="space-y-0 border-t pt-4">
                <!-- Empty State -->
                <div
                    v-if="histories.length === 0"
                    class="py-6 text-center text-sm text-muted-foreground"
                >
                    Belum ada riwayat aktivitas.
                </div>

                <!-- Timeline -->
                <div
                    v-for="(history, index) in histories"
                    :key="history.id"
                    class="relative flex gap-3 pb-5 last:pb-0"
                >
                    <!-- Timeline Line -->
                    <div class="relative flex w-7 shrink-0 justify-center">
                        <div
                            v-if="index !== histories.length - 1"
                            class="absolute top-5 bottom-[-20px] w-px bg-border"
                        />

                        <span
                            class="relative z-10 mt-1.5 size-2.5 rounded-full bg-cyan-500 ring-4 ring-background"
                        />
                    </div>

                    <!-- Activity Icon -->
                    <div
                        :class="[
                            'flex size-10 shrink-0 items-center justify-center rounded-xl',
                            iconColors[history.activity] ??
                                'bg-slate-100 text-slate-500',
                        ]"
                    >
                        <component
                            :is="activityIcons[history.activity] ?? IconHistory"
                            class="size-5"
                        />
                    </div>

                    <!-- Activity Information -->
                    <div class="min-w-0 flex-1 space-y-1">
                        <div
                            class="flex flex-wrap items-start justify-between gap-2"
                        >
                            <p class="text-sm font-semibold">
                                {{
                                    history.description ||
                                    getActivityLabel(history.activity)
                                }}
                            </p>

                            <span class="text-xs text-muted-foreground">
                                {{ formatDate(history.created_at) }}
                            </span>
                        </div>

                        <p class="text-xs text-muted-foreground">
                            Oleh {{ history.user?.name ?? "System" }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
