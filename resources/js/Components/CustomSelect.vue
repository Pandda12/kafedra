<script setup lang="ts">
import VueSelect from "vue3-select-component";

defineProps({
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: null
    },
    is_multi: {
        type: Boolean,
        default: false
    }
})

const model = defineModel({
    type: [Number, String, null],
    required: true,
});

</script>

<template>
    <VueSelect
        v-model="model"
        :options="options"
        :is-multi="is_multi"
        :placeholder="placeholder"
        :classes="{
            control: 'shadow-sm',
            valueContainer: 'py-2 px-3'
        }"
    />
</template>

<style scoped>
:deep(.control) {
    --vs-border-radius: 8px;
}
:deep(.value-container) {
    padding: 8px 12px;
}
:deep(.menu) {
    z-index: 20 !important;
}
</style>