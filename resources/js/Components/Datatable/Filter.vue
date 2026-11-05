<script setup>
import { computed, watch, reactive } from "vue";
import { router } from "@inertiajs/vue3";

import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import { IconRefresh } from "@tabler/icons-vue";

const props = defineProps({
    params: {
        type: Object,
        required: true,
    },

    state: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:params"]);

const search = computed({
    get: () => {
        console.log("1. GET SEARCH:", props.params?.search);
        return props.params?.search ?? "";
    },

    set: (value) => {
        console.log("2. SET SEARCH:", value);

        emit("update:params", {
            ...props.params,
            search: value,
        });
    },
});
const load = computed({
    get: () => String(props.params?.load ?? 10),
    set: (value) => {
        emit("update:params", {
            ...props.params,
            page: 1,
            load: Number(value),
        });
    },
});

function handleClearAll() {
    const resettableParams = Object.fromEntries(
        Object.entries(props.params)
            .filter(([key]) => !["page", "load"].includes(key))
            .map(([key]) => [key, ""]),
    );

    emit("update:params", {
        ...props.params,
        ...resettableParams,
        page: 1,
        load: props.state.load,
    });
}

const params = reactive({
    ...props.params,
});

watch(
    () => props.params,
    (newParams) => {
        Object.assign(params, newParams);
    },
    {
        deep: true,
    },
);

watch(
    params,
    () => {
        console.log("3. PARAMS DI WATCH:", params);

        router.get(route("roles.index"), params, {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ["roles", "state"],
        });
    },
    {
        deep: true,
    },
);
</script>

<template>
    <div
        class="flex w-full flex-col gap-4 px-4 py-2 lg:flex-row lg:items-center"
    >
        <input
            :value="search"
            @input="search = $event.target.value"
            class="w-full rounded-md border px-3 py-2 sm:w-1/4"
            placeholder="Search..."
        />

        <Select v-model="load">
            <SelectTrigger class="w-full sm:w-24">
                <SelectValue placeholder="Load" />
            </SelectTrigger>

            <SelectContent>
                <SelectItem
                    v-for="number in [10, 25, 50, 75, 100]"
                    :key="number"
                    :value="String(number)"
                >
                    {{ number }}
                </SelectItem>
            </SelectContent>
        </Select>

        <slot />

        <Button variant="blue" size="xl" @click="handleClearAll">
            <IconRefresh class="size-4" />
            Bersihkan
        </Button>
    </div>
</template>
