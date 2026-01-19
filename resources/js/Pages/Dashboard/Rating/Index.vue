<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

type Series = { name: string; data: number[] };

const assigned_type = ref<string | null>(null);
const assigned_at = ref<number[]>([]);
const month = ref<number | string | null>(null);

const series = ref<Series[]>([]);
const show = ref(false);

const chartOptions = ref({
    chart: {
        height: 350,
        type: "bar",
    },
    plotOptions: {
        bar: {
            dataLabels: {
                position: 'top'
            },
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) { return val; },
        offsetY: -22,
        style: {
            fontSize: '16px',
            colors: ["#304758"]
        }
    },
    xaxis: {
        categories: [] as (string | string[])[],
        tooltip: {
            enabled: true,
        },
    },
});

const props = defineProps({
    year: { type: Object, required: true },
    months: { type: Array, required: true },
    user_type_select: { type: Array, required: true },
    users: { type: Array, required: true },
});

let debounceTimer: number | null = null;

const getRating = async () => {
    try {
        const response = await axios.get(route("dashboard.api.rating.charts", props.year.slug), {
            params: {
                assigned_type: assigned_type.value,
                assigned_at: assigned_type.value === "personal" ? assigned_at.value : [],
                month: month.value,
            },
        });

        const items = response.data?.data?.items ?? [];
        const rating = response.data?.data?.rating ?? [];

        show.value = items.length > 0;

        series.value = [
            {
                name: "Рейтинг",
                data: rating,
            },
        ];

        chartOptions.value = {
            ...chartOptions.value,
            xaxis: {
                ...chartOptions.value.xaxis,
                categories: items,
            },
        };
    } catch (error) {
        console.log(error);
    }
}

watch(assigned_type, (newType) => {
    if (newType !== "personal") {
        assigned_at.value = [];
    }
});

watch(
    [assigned_type, assigned_at, month],
    () => {
        if (debounceTimer) window.clearTimeout(debounceTimer);

        debounceTimer = window.setTimeout(() => {
            getRating();
        }, 200);
    },
    {
        deep: true,
    }
);

onMounted(() => {
    getRating();
});
</script>

<template>
    <Head title="Рейтинг" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: year.name,
                url: route('dashboard.academic_year.show', year.slug)
            },
            {
                name: 'Рейтинг'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Рейтинг</h1>
        </div>

        <div class="grid grid-cols-3 gap-x-4 mb-8">
            <div class="flex flex-col gap-y-2">
                <InputLabel for="email" value="Для кого" />
                <CustomSelect
                    v-model="assigned_type"
                    :options="user_type_select"
                    placeholder="Для кого"

                />
            </div>
            <div v-if="assigned_type === 'personal'" class="flex flex-col gap-y-2">
                <InputLabel for="assigned_at" value="Выберите сотрудников" />
                <CustomSelect
                    v-model="assigned_at"
                    :options="users"
                    placeholder="Для кого"

                />
            </div>
            <div class="flex flex-col gap-y-2">
                <InputLabel for="email" value="Общий рейтинг за месяц" />
                <CustomSelect
                    v-model="month"
                    :options="months"
                    placeholder="Текущий месяц"
                />
            </div>
        </div>

        <div>
            <BarChart v-if="show" :series="series" :options="chartOptions" />
        </div>

        <div v-if="show">
            <a
                :href="route('dashboard.rating.download', {
                    academicYear: props.year.slug,
                    assigned_type: assigned_type,
                    assigned_at: assigned_at,
                    month: month,
                })"
                class="inline-flex items-center rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
            >
                Скачать
            </a>
        </div>

        <div v-if="!show" class="flex flex-col p-8 justify-center items-center gap-4 border rounded-xl border-gray-300">
            <div class="p-4 rounded-4xl bg-gray-200">
                <CrossIcon :x="20" :y="20" />
            </div>
            <span class="font-bold">Нет данных для отображение</span>
        </div>
    </DashboardLayout>
</template>