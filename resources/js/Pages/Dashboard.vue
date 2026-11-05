<script setup>
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import { computed, ref } from "vue";
import { PlaneLanding } from "lucide-vue-next";

const selectedPeriod = ref("30");

import {
    Plane,
    Ticket,
    Users,
    TriangleAlert,
    Bot,
    Building2,
    TrendingUp,
} from "lucide-vue-next";
import Select from "@/Components/ui/select/Select.vue";
import SelectTrigger from "@/Components/ui/select/SelectTrigger.vue";
import SelectContent from "@/Components/ui/select/SelectContent.vue";
import SelectItem from "@/Components/ui/select/SelectItem.vue";
import SelectValue from "@/Components/ui/select/SelectValue.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    items: Array,
    pageSettings: Object,
    statistics: Object,
    bookingAnalytics: Object,
    flightStatusChart: Object,
    liveFlights: Array,
});
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <Head title="Dashboard" />
        <BreadcrumbHeader :items="items" />

        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />
    </div>
</template>
