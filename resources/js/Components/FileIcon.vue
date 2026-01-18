<script setup lang="ts">
import WordFileIcon from "@/Components/Icons/WordFileIcon.vue";
import PdfFileIcon from "@/Components/Icons/PdfFileIcon.vue";

defineProps({
    type: {
        type: String,
        required: true
    },
    width: {
        type: Number,
        default: 512
    },
    height: {
        type: Number,
        default: 512
    }
})
</script>

<template>
    <WordFileIcon v-if="type === 'doc' || type === 'docx'" :x="width" :y="height" />
    <PdfFileIcon v-if="type === 'pdf'" :x="width" :y="height" />
</template>
