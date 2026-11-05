<script setup>
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from "./index";

const props = defineProps({
    variant: {
        type: String,
        default: "default",
    },
    size: {
        type: String,
        default: "default",
    },
    class: {
        type: [String, Object, Array],
        default: "",
    },
    asChild: {
        type: Boolean,
        default: false,
    },
    as: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <Primitive
        data-slot="button"
        :data-variant="props.variant"
        :data-size="props.size"
        :as="props.as"
        :as-child="props.asChild"
        :class="
            cn(
                buttonVariants({ variant: props.variant, size: props.size }),
                props.class,
            )
        "
    >
        <slot />
    </Primitive>
</template>
