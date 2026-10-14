<script setup>
import { Form, Head, Link, router } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ref } from "vue";
import TaskItem from "@/components/TaskItem.vue";

const props = defineProps({
    taskList: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "My lists",
                href: "/lists",
            },
        ],
    },
});

const isReordering = ref(false);
const updatingTaskId = ref(null);

function moveTask(task, direction) {
    // Prevent multiple reordering requests at the same time
    if (isReordering.value) {
        return;
    }

    isReordering.value = true;

    router.patch(
        `/lists/${props.taskList.id}/tasks/${task.id}/move`,
        { direction },
        {
            preserveScroll: true,
            onFinish: () => {
                isReordering.value = false;
            },
        },
    );
}

function updateCompletion(task, event) {
    const checkbox = event.target;
    const isCompleted = checkbox.checked;

    updatingTaskId.value = task.id;

    router.patch(
        `/lists/${props.taskList.id}/tasks/${task.id}/completion`,
        { is_completed: isCompleted },
        {
            preserveScroll: true,
            onFinish: () => {
                const savedTask = props.taskList.tasks.find(
                    (item) => item.id === task.id,
                );

                checkbox.checked = savedTask
                    ? savedTask.is_completed
                    : task.is_completed;

                updatingTaskId.value = null;
            },
        },
    );
}

function deleteTask(task) {
    // Confirm deletion with the user
    if (!window.confirm(`Delete "${task.title}"?`)) {
        return;
    }

    router.delete(`/lists/${props.taskList.id}/tasks/${task.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="taskList.name" />

    <div class="mx-auto flex w-full max-w-5xl flex-col gap-6 p-6">
        <header>
            <Link
                href="/lists"
                class="text-sm text-muted-foreground hover:underline"
            >
                Back to my lists
            </Link>

            <h1 class="mt-4 text-3xl font-semibold tracking-tight break-words">
                {{ taskList.name }}
            </h1>

            <p class="mt-2 text-muted-foreground">
                {{ taskList.tasks.length }}
                {{ taskList.tasks.length === 1 ? "task" : "tasks" }}
            </p>
        </header>

        <section class="rounded-2xl border bg-card p-6">
            <h2 class="mb-4 text-lg font-semibold">Add a task</h2>

            <Form
                :action="`/lists/${taskList.id}/tasks`"
                method="post"
                reset-on-success
                :options="{ preserveScroll: true }"
                v-slot="{ errors, processing }"
                class="flex flex-col gap-4"
            >
                <div class="space-y-2">
                    <Label for="title">Task</Label>
                    <Input
                        id="title"
                        name="title"
                        placeholder="What needs doing?"
                        maxlength="255"
                        required
                    />
                    <InputError :message="errors.title" />
                </div>

                <div class="space-y-2">
                    <Label for="priority">Priority</Label>
                    <select
                        id="priority"
                        name="priority"
                        class="h-10 w-full rounded-md border border-input bg-background px-3 text-sm"
                    >
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                    <InputError :message="errors.priority" />
                </div>

                <Button type="submit" :disabled="processing" class="self-start">
                    {{ processing ? "Adding…" : "Add task" }}
                </Button>
            </Form>
        </section>

        <div
            v-if="taskList.tasks.length === 0"
            class="rounded-2xl border border-dashed p-10 text-center"
        >
            <h2 class="font-medium">A fresh start</h2>
            <p class="mt-2 text-sm text-muted-foreground">
                No tasks in this list yet.
            </p>
        </div>

        <ul v-else class="space-y-3">
            <TaskItem
                v-for="(task, index) in taskList.tasks"
                :key="task.id"
                :task="task"
                :is-first="index === 0"
                :is-last="index === taskList.tasks.length - 1"
                :is-reordering="isReordering"
                :is-updating-completion="updatingTaskId !== null"
                @move="moveTask(task, $event)"
                @delete="deleteTask(task)"
                @completion-change="updateCompletion(task, $event)"
            />
        </ul>
    </div>
</template>
