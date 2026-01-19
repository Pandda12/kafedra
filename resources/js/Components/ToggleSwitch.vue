<script setup lang="ts">
import { motion } from "motion-v"
import {ref, watch} from "vue"

const props = defineProps({
    active: {
        type: Boolean,
        required: true
    }
});
const emit = defineEmits<{
    (e: 'update:active', value: boolean): void
}>();

const isOn = ref(props.active);

watch(() => props.active, v => {
    isOn.value = v;
});

function toggleSwitch() {
    isOn.value = !isOn.value;
    emit('update:active', isOn.value);
}
</script>

<template>
    <button
        class="flex w-14 rounded-full cursor-pointer p-1"
        :class="[isOn ? 'justify-end bg-blue-600' : 'justify-start bg-gray-400']"
        @click.prevent="toggleSwitch"
    >
        <motion.div
            :data-state="isOn"
            class="w-6 h-6 rounded-full bg-white"
            layout
            :transition="{
                type: 'spring',
                visualDuration: 0.2,
                bounce: 0.2
            }"
        />
    </button>
</template>
