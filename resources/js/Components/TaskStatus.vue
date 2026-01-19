<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps({
    statusName: {
        type: String,
        required: true,
    },
    statusValue: {
        type: String,
        required: true,
    },
})

const statusClasses = computed(() => {
    switch (props.statusValue) {
        case 'assigned':
            return ['text-gray-600', 'bg-gray-200']
        case 'accepted':
            return ['text-green-700', 'bg-green-100']
        case 'rejected':
            return ['text-red-700', 'bg-red-100']
        case 'revised':
            return ['text-yellow-700', 'bg-yellow-100']
        case 'closed':
            return ['text-gray-700', 'bg-gray-100']
        case 'review':
            return ['text-blue-700', 'bg-blue-100']
        default:
            return ['text-gray-600', 'bg-gray-200']
    }
})
</script>

<template>
    <div class="inline-flex px-2 py-1 rounded font-bold" :class="statusClasses">
        {{ props.statusName }}
    </div>
</template>