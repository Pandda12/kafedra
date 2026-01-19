<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    date: string
    color?: 'red' | 'yellow' | 'green' | null
}>()

const colorClass = computed(() => {
    switch (props.color) {
        case 'red':
            return 'bg-red-600/50'
        case 'yellow':
            return 'bg-yellow-400/50'
        case 'green':
            return 'bg-green-600/50'
        default:
            return ''
    }
})
</script>

<template>
  <span class="p-2 rounded-md" :class="colorClass">
    {{ date }}
  </span>
</template>
