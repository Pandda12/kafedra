<script setup lang="ts">
import { shallowRef, markRaw, watch, ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    series: { type: Array, required: true },
    options: { type: Object, required: true },
    height: { type: [Number, String], default: 450 },
    openInNewTab: { type: Boolean, default: true },
});

const seriesLocal = shallowRef(markRaw(props.series));
const optionsLocal = shallowRef(markRaw(props.options));

watch(
    () => props.series,
    (val) => { seriesLocal.value = markRaw(val); },
    { deep: false }
);
watch(
    () => props.options,
    (val) => { optionsLocal.value = markRaw(val); },
    { deep: false }
);

const wrap = ref(null);
const chart = ref(null);

function handleDataPointSelection(event, chartContext, config) {
    const sIndex = config.seriesIndex;
    const dIndex = config.dataPointIndex;

    const point = props.series?.[sIndex]?.data?.[dIndex];
    const url = point?.url;

    if (url) {
        if (props.openInNewTab) {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    }
}

let onWheel;
onMounted(() => {
    const container = wrap.value?.querySelector('.apexcharts-canvas');
    if (!container) return;

    onWheel = (e) => {
        const inToolbar = e.target.closest('.apexcharts-toolbar');
        if (!inToolbar) {
            e.preventDefault();
        }
    };

    container.addEventListener('wheel', onWheel, { passive: false });
});

onBeforeUnmount(() => {
    const container = wrap.value?.querySelector('.apexcharts-canvas');
    if (container && onWheel) {
        container.removeEventListener('wheel', onWheel);
    }
});
</script>

<template>
    <div class="w-[90%]">
        <apexchart
            ref="chart"
            type="rangeBar"
            :height="height"
            :series="seriesLocal"
            :options="optionsLocal"
            @dataPointSelection="handleDataPointSelection"
        />
    </div>
</template>
