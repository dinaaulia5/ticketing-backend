<script setup>
import { ref, computed, watch, onBeforeUnmount } from "vue";
import { router } from "@inertiajs/vue3";

import { Button } from "@/Components/ui/button";

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
    routeName: {
        type: String,
        required: true,
    },
    only: {
        type: Array,
        default: () => ["data", "state"],
    },
});

const emit = defineEmits(["update:params"]);

const searchInput = ref(props.params.search ?? "");

let debounceTimer = null;

// Sinkronisasi nilai search dari halaman induk
watch(
    () => props.params.search,
    (value) => {
        if ((value ?? "") !== searchInput.value) {
            searchInput.value = value ?? "";
        }
    },
);

// Request ke controller
function submitFilter(newParams) {
    router.get(route(props.routeName), newParams, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
        only: props.only,
    });
}

// Live Search
function handleSearch(event) {
    const value = event.target.value;

    searchInput.value = value;

    const newParams = {
        ...props.params,
        search: value,
        page: 1,
    };

    emit("update:params", newParams);

    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(() => {
        submitFilter(newParams);
    }, 400);
}

// Load data
const load = computed({
    get: () => String(props.params.load ?? 10),

    set: (value) => {
        clearTimeout(debounceTimer);

        const newParams = {
            ...props.params,
            load: Number(value),
            page: 1,
        };

        emit("update:params", newParams);
        submitFilter(newParams);
    },
});

// Reset filter
function handleClearAll() {
    clearTimeout(debounceTimer);

    const newParams = {
        ...props.params,
        search: "",
        page: 1,
        load: props.state.load ?? 10,
        field: props.state.field ?? "created_at",
        direction: props.state.direction ?? "desc",
    };

    searchInput.value = "";

    emit("update:params", newParams);
    submitFilter(newParams);
}

onBeforeUnmount(() => {
    clearTimeout(debounceTimer);
});
</script>

<template>
    <div
        class="flex w-full flex-col gap-4 px-4 py-2 lg:flex-row lg:items-center"
    >
        <!-- Live Search -->
        <input
            :value="searchInput"
            @input="handleSearch"
            type="search"
            class="w-full rounded-md border px-3 py-2 sm:w-1/4"
            placeholder="Cari data..."
        />

        <!-- Load -->
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

        <!-- Clear -->
        <Button variant="blue" size="xl" @click="handleClearAll">
            <IconRefresh class="size-4" />
            Bersihkan
        </Button>
    </div>
</template>
