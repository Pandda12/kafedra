<script setup lang="ts">
import {ref} from "vue";

defineProps({
    task: {
        type: Object,
        required: true
    }
})

const is_open = ref(false)

const toggle = () => {
    is_open.value = !is_open.value
}
</script>

<template>
    <div>
        <span @click.prevent="toggle" class="underline hover:cursor-pointer text-blue-600">{{ task.name }}</span> – с {{ task.starts_on }} по {{ task.ends_on }}
    </div>
    <div v-if="is_open" class="mt-8">
        {{ task.description }}
    </div>
</template>