<script setup>

const props = defineProps({
    series: {
        type: Array,
        required: true
    },
    options: {
        type: Object,
        required: true
    },
    height: {
        type: [Number, String],
        default: 450
    }
});

</script>

<template>
    <div class="w-3/4">
        <apexchart
            ref="chart"
            type="bar"
            :height="height"
            :series="series"
            :options="options"
        />
    </div>
</template>