<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import DropdownIcon from "@/Components/Icons/DropdownIcon.vue";

defineProps({
    breadcrumbs: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <nav class="flex items-center gap-x-2">
        <ol class="flex flex-wrap items-center gap-x-2">
            <li
                v-for="(item, index) in breadcrumbs"
                :key="index"
                class="flex items-center gap-x-2 text-gray-500"
            >
                <DropdownIcon v-if="index > 0" class="-rotate-90" :x="12" :y="12" color="#6b7280" />
                <Link
                    v-if="item.url"
                    :href="item.url"
                    class="hover:text-gray-700"
                >
                    {{ item.name }}
                </Link>
                <span v-else>{{ item.name }}</span>
            </li>
        </ol>
    </nav>
</template>