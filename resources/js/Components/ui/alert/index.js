import { cva } from "class-variance-authority";

export { default as Alert } from "./Alert.vue";
export { default as AlertAction } from "./AlertAction.vue";
export { default as AlertDescription } from "./AlertDescription.vue";
export { default as AlertTitle } from "./AlertTitle.vue";

export const alertVariants = cva(
    "grid gap-0.5 rounded-lg border px-4 py-3 text-left text-sm has-data-[slot=alert-action]:relative has-data-[slot=alert-action]:pr-18 has-[>svg]:grid-cols-[auto_1fr] has-[>svg]:gap-x-2.5 *:[svg]:row-span-2 *:[svg]:translate-y-0.5 *:[svg]:text-current *:[svg:not([class*=size-])]:size-4 group/alert relative w-full",
    {
        variants: {
            variant: {
                default: "bg-card text-card-foreground",
                destructive:
                    "text-destructive bg-card *:data-[slot=alert-description]:text-destructive/90 *:[svg]:text-current",
                success:
                    "text-white bg-gradient-to-r from-green-400 via-green-500 to-green-500 [&>svg]:text-destructive",
                info: "text-white bg-gradient-to-r from-sky-400 via-sky-500 to-sky-500 [&>svg]:text-destructive",
            },
        },
        defaultVariants: {
            variant: "default",
        },
    },
);
