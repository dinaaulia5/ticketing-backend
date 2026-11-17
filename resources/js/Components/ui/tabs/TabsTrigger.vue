<script setup>
import { reactiveOmit } from "@vueuse/core";
import { TabsTrigger, useForwardProps } from "reka-ui";
import { cn } from "@/lib/utils";

const props = defineProps({
    value: { type: [String, Number], required: true },
    disabled: { type: Boolean, required: false },
    asChild: { type: Boolean, required: false },
    as: { type: null, required: false },
    class: {
        type: [Boolean, null, String, Object, Array],
        required: false,
        skipCheck: true,
    },
});

const delegatedProps = reactiveOmit(props, "class");

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <TabsTrigger
        data-slot="tabs-trigger"
        :class="
            cn(
                'relative inline-flex h-10 flex-1 items-center justify-center rounded-lg px-4 text-sm font-medium transition-all',
                'text-muted-foreground hover:bg-cyan-500/10 hover:text-cyan-600',
                'data-[state=active]:!bg-cyan-500 data-[state=active]:!text-white',
                'data-active:!bg-cyan-500 data-active:!text-white',
                props.class,
            )
        "
        v-bind="forwardedProps"
    >
        <slot />
    </TabsTrigger>
</template>
