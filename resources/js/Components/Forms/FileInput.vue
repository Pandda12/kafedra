<script setup lang="ts">
const props = defineProps({
    modelValue: {
        type: [File, Array, null],
        default: null
    },
    id: {
        type: String,
        default: 'file_input'
    },
    accept: {
        type: String,
        default: '.doc,.docx,.pdf'
    },
    helpText: {
        type: String,
        default: 'DOC, DOCX и PDF'
    },
    progress: {
        type: Object,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    },
    multiple: {
        type: Boolean,
        default: false
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

function onFileChange(event) {
    const files = event.target.files;
    const value = props.multiple ? Array.from(files || []) : (files?.[0] ?? null);

    emit('update:modelValue', value);
    emit('change', value);
}
</script>

<template>

    <input
        class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4"
        :id="id"
        type="file"
        :accept="accept"
        :multiple="multiple"
        :disabled="disabled"
        aria-describedby="file_input_help"
        @input="onFileChange"
    />

    <progress
        v-if="progress && progress.percentage"
        :value="progress.percentage"
        max="100"
    >
        {{ progress.percentage }}%
    </progress>

    <p class="mt-1 text-sm text-gray-500" id="file_input_help">
        {{ helpText }}
    </p>
</template>