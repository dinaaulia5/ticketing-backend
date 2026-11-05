<script setup>
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from "@/Components/ui/pagination";

import { cn } from "@/lib/utils";

defineProps({
    meta: {
        type: Object,
        required: true,
    },

    links: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Pagination>
        <PaginationContent
            class="flex flex-wrap justify-content lg:justify-end"
        >
            <!-- PREVIOUS -->
            <PaginationItem>
                <PaginationPrevious
                    :class="
                        cn(
                            'mb-1',
                            !links.prev && 'cursor-not-allowed opacity-50',
                        )
                    "
                    :href="links.prev"
                />
            </PaginationItem>

            <!-- NUMBER -->
            <PaginationItem
                v-for="(link, index) in meta.links.slice(1, -1)"
                :key="index"
                class="mx-1 mb-1 lg:mb-0"
            >
                <PaginationLink :href="link.url" :is-active="link.active">
                    {{ link.label }}
                </PaginationLink>
            </PaginationItem>

            <!-- NEXT -->
            <PaginationItem>
                <PaginationNext
                    :class="
                        cn(
                            'mb-1',
                            !links.next && 'cursor-not-allowed opacity-50',
                        )
                    "
                    :href="links.next"
                />
            </PaginationItem>
        </PaginationContent>
    </Pagination>
</template>
