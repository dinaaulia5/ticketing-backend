<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";

import { Button } from "@/Components/ui/button";
import { Badge } from "@/Components/ui/badge";

import { IconUsers, IconUserPlus } from "@tabler/icons-vue";

const props = defineProps({
    assignments: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["add-pic"]);

const activeAssignments = () => {
    return props.assignments.filter((assignment) => !assignment.unassigned_at);
};

const primaryAssignment = () => {
    return activeAssignments().find(
        (assignment) => assignment.assignment_role === "primary",
    );
};

const supportingAssignments = () => {
    return activeAssignments().filter(
        (assignment) => assignment.assignment_role === "supporting",
    );
};

const getInitials = (name) => {
    if (!name) return "-";

    return name
        .split(" ")
        .slice(0, 2)
        .map((word) => word.charAt(0))
        .join("")
        .toUpperCase();
};
</script>

<template>
    <Card class="rounded-xl shadow-none">
        <CardHeader class="pb-3">
            <CardTitle class="flex items-center gap-3 text-base font-bold">
                <IconUsers class="size-5 text-cyan-500" />
                Penugasan (Assignment)
            </CardTitle>
        </CardHeader>

        <CardContent>
            <div class="space-y-3 border-t pt-3">
                <!-- PIC Utama -->
                <div class="grid grid-cols-[110px_1fr] items-center gap-3">
                    <span class="text-sm font-semibold text-muted-foreground">
                        PIC Utama
                    </span>

                    <div
                        v-if="primaryAssignment()"
                        class="flex min-w-0 items-center gap-2"
                    >
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sm font-medium text-slate-700"
                        >
                            {{
                                getInitials(
                                    primaryAssignment().technician?.name,
                                )
                            }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold">
                                {{
                                    primaryAssignment().technician?.name ?? "-"
                                }}
                            </p>

                            <p class="truncate text-xs text-muted-foreground">
                                {{
                                    primaryAssignment().technician?.email ??
                                    "IT Support"
                                }}
                            </p>
                        </div>

                        <Badge
                            class="shrink-0 rounded-full border-0 bg-emerald-100 text-emerald-600 hover:bg-emerald-100"
                        >
                            Assigned
                        </Badge>
                    </div>

                    <span v-else class="text-sm text-muted-foreground">-</span>
                </div>

                <div class="border-t" />

                <!-- PIC Pendukung -->
                <div class="grid grid-cols-[110px_1fr] items-start gap-3">
                    <span
                        class="pt-2 text-sm font-semibold text-muted-foreground"
                    >
                        PIC Pendukung
                    </span>

                    <div class="space-y-2">
                        <div
                            v-for="assignment in supportingAssignments()"
                            :key="assignment.id"
                            class="flex items-center gap-2"
                        >
                            <div
                                class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-medium"
                            >
                                {{ getInitials(assignment.technician?.name) }}
                            </div>

                            <span class="min-w-0 truncate text-sm font-medium">
                                {{ assignment.technician?.name ?? "-" }}
                            </span>
                        </div>

                        <div class="flex justify-end">
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                class="gap-2"
                                @click="emit('add-pic')"
                            >
                                <IconUserPlus class="size-4" />
                                Tambah PIC
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
