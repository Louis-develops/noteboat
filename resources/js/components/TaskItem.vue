<script setup>
defineProps({
    task: {
        type: Object,
        required: true,
    },
    isFirst: {
        type: Boolean,
        default: false,
    },
    isLast: {
        type: Boolean,
        default: false,
    },
    isReordering: {
        type: Boolean,
        default: false,
    },
    isUpdatingCompletion: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["move", "delete", "completion-change"]);
</script>

<template>
    <li class="flex flex-wrap items-center gap-4 rounded-xl border bg-card p-4">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <input
                :id="`task-completed-${task.id}`"
                type="checkbox"
                :checked="task.is_completed"
                :disabled="isUpdatingCompletion"
                class="h-4 w-4 shrink-0 cursor-pointer accent-teal-600 disabled:cursor-wait"
                @change="emit('completion-change', $event)"
            />

            <label
                :for="`task-completed-${task.id}`"
                class="min-w-0 cursor-pointer break-words"
                :class="{
                    'text-muted-foreground line-through': task.is_completed,
                }"
            >
                {{ task.title }}
            </label>
        </div>

        <div class="ml-auto flex shrink-0 items-center gap-3">
            <span
                class="rounded-full px-2.5 py-1 text-xs font-medium capitalize"
                :class="{
                    'bg-rose-100 text-rose-800': task.priority === 'high',
                    'bg-amber-100 text-amber-800': task.priority === 'medium',
                    'bg-teal-100 text-teal-800': task.priority === 'low',
                }"
            >
                {{ task.priority }}
            </span>

            <div class="flex items-center gap-1">
                <button
                    type="button"
                    class="rounded-md border px-2 py-1 hover:bg-muted disabled:cursor-not-allowed disabled:opacity-30"
                    :disabled="isFirst || isReordering"
                    :aria-label="`Move ${task.title} up`"
                    title="Move up"
                    @click="emit('move', 'up')"
                >
                    <span aria-hidden="true">↑</span>
                </button>

                <button
                    type="button"
                    class="rounded-md border px-2 py-1 hover:bg-muted disabled:cursor-not-allowed disabled:opacity-30"
                    :disabled="isLast || isReordering"
                    :aria-label="`Move ${task.title} down`"
                    title="Move down"
                    @click="emit('move', 'down')"
                >
                    <span aria-hidden="true">↓</span>
                </button>
            </div>

            <button
                type="button"
                class="text-sm font-medium text-red-600 hover:underline"
                :aria-label="`Delete task: ${task.title}`"
                @click="emit('delete')"
            >
                X
            </button>
        </div>
    </li>
</template>
