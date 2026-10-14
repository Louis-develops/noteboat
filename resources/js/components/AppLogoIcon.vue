<script setup>
import { Sailboat } from "@lucide/vue";

defineOptions({
    inheritAttrs: false,
});

defineProps({
    className: {
        type: [String, Array, Object],
        default: "",
    },
});
</script>

<template>
    <Sailboat
        v-bind="$attrs"
        :class="className"
        style="fill: none"
        aria-hidden="true"
    />
</template>
