<script setup>
import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";
import {
    IconTicket,
    IconPencil,
    IconClock,
    IconChevronDown,
} from "@tabler/icons-vue";
import { formatDateIndo } from "@/lib/utils";

defineProps({
    ticket: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["edit", "change-status"]);
</script>

<template>
    <div
        class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between"
    >
        <!-- Ticket Summary -->
        <div
            class="flex min-w-0 flex-1 items-center justify-between gap-4 rounded-2xl bg-gradient-to-br from-cyan-500 via-cyan-500 to-blue-100 p-5"
        >
            <div class="flex min-w-0 items-center gap-4">
                <!-- Icon -->
                <div
                    class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-400 to-cyan-600 text-white shadow-sm"
                >
                    <IconTicket class="size-7" />
                </div>

                <!-- Ticket Information -->
                <div class="min-w-0 space-y-1">
                    <h2
                        class="text-2xl font-medium leading-relaxed tracking-tight"
                    >
                        {{ ticket.ticket_number }}
                    </h2>

                    <p class="text-sm leading-relaxed">
                        Dibuat pada {{ formatDateIndo(ticket.created_at) }}
                    </p>
                </div>
            </div>

            <!-- Status -->
            <Badge
                class="shrink-0 rounded-full border-0 bg-blue-500 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
            >
                <IconClock class="mr-1.5 size-4" />
                {{ ticket.status }}
            </Badge>
        </div>

        <!-- Actions -->
        <div class="flex shrink-0 items-center gap-3">
            <Button
                type="button"
                variant="outline"
                class="gap-2"
                @click="emit('edit')"
            >
                <IconPencil class="size-4" />
                Edit Tiket
            </Button>

            <Button
                type="button"
                class="gap-2 bg-gradient-to-r from-cyan-500 to-cyan-600 text-white hover:from-cyan-600 hover:to-cyan-700"
                @click="emit('change-status')"
            >
                <IconClock class="size-4" />
                Ubah Status
                <IconChevronDown class="size-4" />
            </Button>
        </div>
    </div>
</template>
