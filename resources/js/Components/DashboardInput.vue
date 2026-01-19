<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: [Number, String, null],
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="py-2 px-3 rounded-lg border shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-600"
        v-model="model"
        ref="input"
    />
</template>
