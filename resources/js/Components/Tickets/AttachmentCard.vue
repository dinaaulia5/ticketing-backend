<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Button } from "@/Components/ui/button";

import {
    IconPaperclip,
    IconFileTypePdf,
    IconPhoto,
    IconFile,
    IconEye,
    IconDownload,
} from "@tabler/icons-vue";

defineProps({
    attachments: {
        type: Array,
        default: () => [],
    },
});

const formatFileSize = (size) => {
    if (!size) return "0 KB";

    if (size < 1024 * 1024) {
        return `${(size / 1024).toFixed(1)} KB`;
    }

    return `${(size / (1024 * 1024)).toFixed(1)} MB`;
};

const getFileIcon = (fileType) => {
    if (fileType?.includes("pdf")) return IconFileTypePdf;

    if (fileType?.startsWith("image/")) return IconPhoto;

    return IconFile;
};

const getFileColor = (fileType) => {
    if (fileType?.includes("pdf")) return "text-red-500";

    if (fileType?.startsWith("image/")) return "text-blue-500";

    return "text-slate-500";
};

const previewFile = (file) => {
    if (!file.preview_url) return;

    window.open(file.preview_url, "_blank", "noopener,noreferrer");
};

const downloadFile = (file) => {
    if (!file.download_url) return;

    window.open(file.download_url, "_blank", "noopener,noreferrer");
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <!-- Header -->
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconPaperclip class="size-5 text-cyan-500" />
                Lampiran ({{ attachments.length }})
            </CardTitle>
        </CardHeader>

        <!-- Content -->
        <CardContent>
            <div class="space-y-2 border-t pt-3">
                <!-- Empty State -->
                <div
                    v-if="attachments.length === 0"
                    class="flex flex-col items-center justify-center gap-2 py-6 text-muted-foreground"
                >
                    <IconPaperclip class="size-8 opacity-50" />

                    <p class="text-sm">Belum ada lampiran pada tiket ini.</p>
                </div>

                <!-- Attachment List -->
                <div
                    v-for="file in attachments"
                    :key="file.id"
                    class="flex max-w-md items-center justify-between gap-3 rounded-lg bg-muted/50 px-3 py-2"
                >
                    <div class="flex min-w-0 items-center gap-3">
                        <component
                            :is="getFileIcon(file.file_type)"
                            :class="[
                                'size-7 shrink-0',
                                getFileColor(file.file_type),
                            ]"
                        />

                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold">
                                {{ file.file_name }}
                            </p>

                            <p class="text-xs text-muted-foreground">
                                {{ formatFileSize(file.file_size) }}
                                ·
                                {{
                                    file.file_type
                                        ?.split("/")
                                        .pop()
                                        ?.toUpperCase()
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-1">
                        <Button
                            v-if="file.preview_url"
                            type="button"
                            variant="ghost"
                            size="icon"
                            class="size-8"
                            title="Lihat lampiran"
                            @click="previewFile(file)"
                        >
                            <IconEye class="size-4" />
                        </Button>

                        <Button
                            v-if="file.download_url"
                            type="button"
                            variant="ghost"
                            size="icon"
                            class="size-8"
                            title="Download lampiran"
                            @click="downloadFile(file)"
                        >
                            <IconDownload class="size-4" />
                        </Button>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
