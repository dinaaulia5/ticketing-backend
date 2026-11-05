<template>
    <!-- DROPDOWN TRAINING MATERIALS -->
    <div
        v-if="expandedModules.includes(module.id)"
        class="border-t bg-muted/20 px-4 py-4"
    >
        <!-- Header Materi -->
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold">Training Materials</h3>

                <p class="mt-1 text-xs text-muted-foreground">
                    Daftar materi dalam modul
                    {{ module.title }}.
                </p>
            </div>

            <Button size="sm" @click="openMaterialModal(module)">
                <IconPlus class="mr-1 size-4" />
                Tambah Materi
            </Button>
        </div>

        <!-- Materi Kosong -->
        <EmptyState
            v-if="!module.materials || module.materials.length === 0"
            :icon="IconBook"
            title="Belum Ada Materi"
            subtitle="Tambahkan materi pembelajaran untuk modul ini."
        />

        <!-- Daftar Materi -->
        <div v-else class="flex flex-col gap-2">
            <div
                v-for="material in module.materials"
                :key="material.id"
                class="flex items-center gap-3 rounded-lg border bg-background px-3 py-3"
            >
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted"
                >
                    <IconVideo
                        v-if="material.type === 'VIDEO'"
                        class="size-5"
                    />

                    <IconClipboardCheck
                        v-else-if="material.type === 'QUIZ'"
                        class="size-5"
                    />

                    <IconFileText v-else class="size-5" />
                </div>

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium">
                        {{ material.title }}
                    </p>

                    <p class="mt-1 text-xs text-muted-foreground">
                        {{ material.type ?? "Materi" }}
                        <span class="mx-1">·</span>
                        {{ material.duration_minutes ?? 0 }}
                        menit
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
