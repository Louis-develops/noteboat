<script setup>
import { Sailboat } from "@lucide/vue";
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-lg bg-teal-600 text-white"
    >
        <Sailboat class="size-5" aria-hidden="true" />
    </div>

    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">
            Noteboat
        </span>
    </div>
</template>
