<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import PageHeader from "@/Components/PageHeader.vue";
import Banner from "@/Components/Banner.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";

import TicketSummaryHeader from "@/Components/Tickets/TicketSummaryHeader.vue";
import InformationCard from "@/Components/Tickets/InformationCard.vue";
import AttachmentCard from "@/Components/Tickets/AttachmentCard.vue";
import AssignmentCard from "@/Components/Tickets/AssignmentCard.vue";
import HistoryCard from "@/Components/Tickets/HistoryCard.vue";
import StatusPriorityCard from "@/Components/Tickets/StatusPriorityCard.vue";
import CommentCard from "@/Components/Tickets/CommentCard.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    ticket: {
        type: Object,
        required: true,
    },

    pageSettings: {
        type: Object,
        required: true,
    },

    state: {
        type: Object,
        default: () => ({}),
    },

    items: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();

const auth = computed(() => page.props.auth);

const openModal = ref(false);
const openEditModal = () => {
    openModal.value = true;
};
const goBack = () => {
    router.get(route("tickets.index"));
};
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <Head :title="`Detail Nomor Tiket: ${ticket.ticket_number}`" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- Page Header -->
        <PageHeader
            :title="`Detail Nomor Tiket: ${ticket.ticket_number}`"
            :description="pageSettings.subtitle"
            @back="goBack"
        />

        <!-- Ticket Summary -->
        <TicketSummaryHeader
            :ticket="ticket"
            @edit="openEditModal"
            @change-status="openStatusModal"
        />

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
            <!-- Kolom Kiri -->
            <div class="space-y-6 xl:col-span-3">
                <InformationCard :ticket="ticket" />

                <AttachmentCard :attachments="ticket.attachments ?? []" />

                <!-- Komentar kita buat terakhir -->
                <CommentCard
                    :ticket="ticket"
                    :comments="ticket.comments ?? []"
                    :current-user="auth?.user ?? {}"
                />
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6 xl:col-span-2">
                <StatusPriorityCard :ticket="ticket" />

                <AssignmentCard
                    :assignments="ticket.assignments ?? []"
                    @add-pic="openAssignmentModal"
                />

                <HistoryCard :histories="ticket.histories ?? []" />
            </div>
        </div>
    </div>
</template>
