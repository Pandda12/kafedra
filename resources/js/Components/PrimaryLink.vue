<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
defineProps({
    link: {
        type: String,
        required: true
    }
})
</script>

<template>
    <Link
        :href="link"
        class="inline-flex items-center rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
    >
        <slot/>
    </Link>
</template>