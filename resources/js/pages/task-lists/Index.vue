<script setup>
import { Form, Head, Link } from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

defineProps({
    taskLists: {
        type: Array,
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
</script>

<template>
    <Head title="My lists" />

    <div class="mx-auto flex w-full max-w-5xl flex-col gap-8 p-6">
        <header>
            <p class="text-sm font-medium text-teal-600 dark:text-teal-400">
                Noteboat
            </p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">
                A little order for your day.
            </h1>
            <p class="mt-2 text-muted-foreground">
                Create a list and give your tasks a place to land.
            </p>
        </header>

        <section class="rounded-2xl border bg-card p-6">
            <h2 class="mb-4 text-lg font-semibold">Create a list</h2>

            <Form
                action="/lists"
                method="post"
                reset-on-success
                v-slot="{ errors, processing }"
                class="flex flex-col gap-4 sm:flex-row sm:items-start"
            >
                <div class="flex-1 space-y-2">
                    <Label for="name">List name</Label>
                    <Input
                        id="name"
                        name="name"
                        placeholder="e.g. Shopping, Side projects, Home"
                        maxlength="100"
                        required
                    />
                    <InputError :message="errors.name" />
                </div>

                <Button type="submit" :disabled="processing" class="sm:mt-6">
                    {{ processing ? "Creating…" : "Create list" }}
                </Button>
            </Form>
        </section>

        <section>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Your lists</h2>
                <span class="text-sm text-muted-foreground">
                    {{ taskLists.length }}
                    {{ taskLists.length === 1 ? "list" : "lists" }}
                </span>
            </div>

            <div
                v-if="taskLists.length === 0"
                class="rounded-2xl border border-dashed p-10 text-center"
            >
                <h3 class="font-medium">Room for your first idea</h3>
                <p class="mt-2 text-sm text-muted-foreground">
                    Create a list above to get started.
                </p>
            </div>

            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="list in taskLists"
                    :key="list.id"
                    class="rounded-2xl border border-t-4 border-t-teal-500 bg-card p-5"
                >
                    <h3 class="font-semibold break-words">
                        <Link
                            :href="`/lists/${list.id}`"
                            class="hover:underline"
                        >
                            {{ list.name }}
                        </Link>
                    </h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        {{ list.tasks_count }}
                        {{ list.tasks_count === 1 ? "task" : "tasks" }}
                    </p>
                </article>
            </div>
        </section>
    </div>
</template>
