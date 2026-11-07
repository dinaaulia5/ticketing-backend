<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Badge } from "@/Components/ui/badge";

import { IconInfoCircle } from "@tabler/icons-vue";

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const ticketTypeLabel = (type) => {
    const types = {
        incident: "Incident",
        service_request: "Service Request",
    };

    return types[type] ?? type ?? "-";
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <!-- Header -->
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconInfoCircle class="size-5 text-cyan-500" />
                Informasi Tiket
            </CardTitle>
        </CardHeader>

        <!-- Content -->
        <CardContent>
            <div class="space-y-3 border-t pt-3">
                <!-- Judul -->
                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground">
                        Judul Tiket
                    </span>

                    <span class="font-medium">
                        {{ ticket.title ?? "-" }}
                    </span>
                </div>

                <!-- Jenis -->
                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground">
                        Jenis
                    </span>

                    <div>
                        <Badge
                            variant="secondary"
                            class="rounded-full bg-blue-50 px-4 text-blue-600 hover:bg-blue-50"
                        >
                            {{ ticketTypeLabel(ticket.ticket_type) }}
                        </Badge>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground">
                        Kategori
                    </span>

                    <span class="font-medium">
                        {{ ticket.category?.name ?? "-" }}
                    </span>
                </div>

                <!-- Subsystem -->
                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground">
                        Subsystem
                    </span>

                    <span class="font-medium">
                        {{ ticket.subsystem?.name ?? "-" }}
                    </span>
                </div>

                <!-- Deskripsi -->
                <div class="grid grid-cols-[135px_1fr] gap-3 text-sm">
                    <span class="font-semibold text-muted-foreground">
                        Deskripsi
                    </span>

                    <div
                        class="rounded-lg bg-muted/60 px-3 py-2 leading-relaxed whitespace-pre-line"
                    >
                        {{ ticket.description ?? "-" }}
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
