<script setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/Components/ui/alert-dialog";

defineProps({
    action: {
        type: Function,
        required: true,
    },

    title: {
        type: String,
        default: "Apakah anda benar-benar yakin?",
    },

    description: {
        type: String,
        default:
            "Tindakan ini tidak dapat dibatalkan. Tindakan ini akan menghapus data secara permanen dan menghapus data anda dari server kami",
    },
});
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <slot />
        </AlertDialogTrigger>

        <AlertDialogContent class="sm:max-w-md">
            <AlertDialogHeader>
                <AlertDialogTitle>
                    {{ title }}
                </AlertDialogTitle>

                <AlertDialogDescription>
                    {{ description }}
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter>
                <AlertDialogCancel> Cancel </AlertDialogCancel>

                <AlertDialogAction @click="action">
                    Continue
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
