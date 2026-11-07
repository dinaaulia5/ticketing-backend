<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Badge } from "@/Components/ui/badge";

import { IconChartBarPopular } from "@tabler/icons-vue";

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const statusLabels = {
    open: "Open",
    in_progress: "In Progress",
    pending: "Pending",
    resolved: "Resolved",
    closed: "Closed",
};

const priorityLabels = {
    low: "Low",
    medium: "Medium",
    high: "High",
    critical: "Critical",
};

const statusClass = {
    open: "bg-blue-100 text-blue-600",
    in_progress: "bg-amber-100 text-amber-600",
    pending: "bg-orange-100 text-orange-600",
    resolved: "bg-emerald-100 text-emerald-600",
    closed: "bg-slate-100 text-slate-600",
};

const priorityClass = {
    low: "bg-slate-100 text-slate-600",
    medium: "bg-blue-100 text-blue-600",
    high: "bg-orange-100 text-orange-600",
    critical: "bg-red-100 text-red-600",
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconChartBarPopular class="size-5 text-cyan-500" />
                Status & Prioritas
            </CardTitle>
        </CardHeader>

        <CardContent>
            <div class="space-y-3 border-t pt-3">
                <div
                    class="grid grid-cols-[135px_1fr] items-center gap-3 text-sm"
                >
                    <span class="font-semibold text-muted-foreground"
                        >Status</span
                    >

                    <div>
                        <Badge
                            :class="[
                                'min-w-24 justify-center rounded-full border-0',
                                statusClass[ticket.status] ??
                                    'bg-slate-100 text-slate-600',
                            ]"
                        >
                            {{
                                statusLabels[ticket.status] ??
                                ticket.status ??
                                "-"
                            }}
                        </Badge>
                    </div>
                </div>

                <div
                    class="grid grid-cols-[135px_1fr] items-center gap-3 text-sm"
                >
                    <span class="font-semibold text-muted-foreground"
                        >Prioritas</span
                    >

                    <div>
                        <Badge
                            :class="[
                                'min-w-28 justify-center rounded-full border-0',
                                priorityClass[ticket.priority] ??
                                    'bg-slate-100 text-slate-600',
                            ]"
                        >
                            {{
                                priorityLabels[ticket.priority] ??
                                "Belum ditentukan"
                            }}
                        </Badge>
                    </div>
                </div>

                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground"
                        >Impact</span
                    >
                    <span class="font-medium">{{ ticket.impact ?? "-" }}</span>
                </div>

                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground"
                        >Urgency</span
                    >
                    <span class="font-medium">{{ ticket.urgency ?? "-" }}</span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
