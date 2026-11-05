import { cva } from "class-variance-authority";

export { default as Badge } from "./Badge.vue";

export const badgeVariants = cva(
    "h-5 gap-1 rounded-4xl border border-transparent px-2 py-0.5 text-xs font-medium transition-all has-data-[icon=inline-end]:pr-1.5 has-data-[icon=inline-start]:pl-1.5 [&>svg]:size-3! group/badge inline-flex w-fit shrink-0 items-center justify-center overflow-hidden whitespace-nowrap focus-visible:border-ring focus-visible:ring-3 focus-visible:ring-ring/50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 [&>svg]:pointer-events-none",
    {
        variants: {
            variant: {
                default:
                    "bg-primary text-primary-foreground [a]:hover:bg-primary/80",
                secondary:
                    "bg-secondary text-secondary-foreground [a]:hover:bg-secondary/80",
                destructive:
                    "bg-destructive/10 [a]:hover:bg-destructive/20 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 text-destructive dark:bg-destructive/20",
                outline:
                    "border-border text-foreground [a]:hover:bg-muted [a]:hover:text-muted-foreground",
                ghost: "hover:bg-muted hover:text-muted-foreground dark:hover:bg-muted/50",
                link: "text-primary underline-offset-4 hover:underline",
                red: "text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700",
                orange: "text-white bg-gradient-to-r from-orange-500 via-orange-600 to-orange-700",
                cyan: "text-white bg-gradient-to-r from-cyan-500 via-cyan-600 to-cyan-700",
                sky: "text-white bg-gradient-to-r from-sky-500 via-sky-600 to-sky-700",
                purple: "text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700",
                rose: "text-white bg-gradient-to-r from-rose-500 via-rose-600 to-rose-700",
                pink: "text-white bg-gradient-to-r from-pink-500 via-pink-600 to-pink-700",
                fuchsia:
                    "text-white bg-gradient-to-r from-fuchsia-500 via-fuchsia-600 to-fuchsia-700",
                violet: "text-white bg-gradient-to-r from-violet-500 via-violet-600 to-violet-700",
                blue: "text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700",
                lime: "text-white bg-gradient-to-r from-lime-500 via-lime-600 to-lime-700",
                teal: "text-white bg-gradient-to-r from-teal-500 via-teal-600 to-teal-700",
            },
        },
        defaultVariants: {
            variant: "default",
        },
    },
);
