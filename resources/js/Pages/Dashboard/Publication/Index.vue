<script setup lang="ts">
import { onMounted, ref, watch, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import InputLabel from "@/Components/InputLabel.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import BarChart from "@/Components/Charts/BarChart.vue";
import IsActive from "@/Components/isActive.vue";

const props = defineProps({
    year: { type: Object, required: true },
    user_type_select: { type: Array, required: true },
    users: { type: Array, required: true },
    publications: { type: Array, required: true }
});

const assigned_type = ref<string | null>(null);
const assigned_at = ref<number[] | null>(null);

const series = ref<any[]>([]);
const chartOptions = ref({
    chart: { height: 350, type: "bar" as const },
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
        categories: [] as string[],
        tooltip: { enabled: true },
    },
});

const apexSeries = computed(() => JSON.parse(JSON.stringify(series.value)));
const apexOptions = computed(() => JSON.parse(JSON.stringify(chartOptions.value)));

const isChartReady = computed(() => {
    const cats = chartOptions.value?.xaxis?.categories ?? [];
    const data = series.value?.[0]?.data ?? [];
    return Array.isArray(cats) && cats.length > 0 && Array.isArray(data) && data.length > 0;
});

const normalize = (usersRaw: any, countRaw: any) => {
    const usersArr = Array.isArray(usersRaw) ? usersRaw : [];
    const countArr = Array.isArray(countRaw) ? countRaw : [];

    const categories = usersArr.map((u) => String(u ?? ""));
    const values = countArr
        .map((v) => Number(v))
        .map((v) => (Number.isFinite(v) ? v : 0));

    const len = Math.min(categories.length, values.length);

    return {
        categories: categories.slice(0, len),
        values: values.slice(0, len),
    };
};

const getPublicationsChartData = async () => {
    try {
        const { data } = await axios.get(route("dashboard.api.publications.charts", props.year.slug), {
            params: {
                assigned_type: assigned_type.value,
                assigned_at: assigned_at.value,
            },
        });

        const normalized = normalize(data?.data?.users, data?.data?.count);

        if (normalized.categories.length === 0 || normalized.values.length === 0) {
            series.value = [];
            chartOptions.value = {
                chart: { height: 350, type: "bar" },
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
                xaxis: { categories: [], tooltip: { enabled: true } },
            };
            return;
        }

        series.value = [
            {
                name: "Публикаций",
                data: normalized.values,
            },
        ];

        chartOptions.value = {
            chart: { height: 350, type: "bar" },
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
                categories: normalized.categories,
                tooltip: { enabled: true },
            },
        };
    } catch (error) {
        console.log(error);
        series.value = [];
        chartOptions.value = {
            chart: { height: 350, type: "bar" },
            xaxis: { categories: [], tooltip: { enabled: true } },
        };
    }
};

watch(assigned_type, (newValue, oldValue) => {
    if (newValue === oldValue) return;

    if (newValue !== "personal") {
        assigned_at.value = null;
    }

    getPublicationsChartData();
});

watch(
    assigned_at,
    () => {
        if (assigned_type.value === "personal") {
            getPublicationsChartData();
        }
    },
    { deep: true }
);

onMounted(() => {
    getPublicationsChartData();
});
</script>

<template>
    <Head title="Публикации" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
      { name: 'Админ панель', url: route('dashboard') },
      { name: year.name, url: route('dashboard.academic_year.show', year.slug) },
      { name: 'Публикации' }
    ]" />

        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Публикации</h1>
        </div>

        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500">Всего публикаций</span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
          {{ publications.length }}
        </span>
            </div>
        </div>

        <div v-if="publications.length">
            <div class="grid grid-cols-2 gap-x-4">
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
            </div>

            <BarChart
                v-if="isChartReady"
                :series="apexSeries"
                :options="apexOptions"
            />

            <div v-else class="mt-6 text-sm text-gray-500">
                Нет данных для графика по выбранным фильтрам.
            </div>
        </div>

        <div v-if="publications.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">&nbsp;</th>
                            <th scope="col" class="px-6 py-3">Библиографическое описание</th>
                            <th scope="col" class="px-6 py-3">Ссылка на репозиторий</th>
                            <th scope="col" class="px-6 py-3">Файл</th>
                            <th scope="col" colspan="2" class="px-6 py-3">Дата добавления</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(publication, index) in publications"
                            :key="publication.id ?? index"
                            class="bg-white border-b border-gray-200 hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </td>

                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ publication.bibliographic_description }}
                            </th>

                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <a
                                    class="flex items-center gap-2 underline text-blue-600"
                                    :href="publication.repository_url"
                                    target="_blank"
                                >
                                    Открыть ссылку <EditIcon />
                                </a>
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <IsActive :is_active="!!publication.file" />
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ publication.date_added }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <Link
                                    class="flex items-center gap-2 text-blue-600"
                                    :href="route('dashboard.publication.show', {
                                        academicYear: year.slug,
                                        publication: publication.id
                                    })"
                                >
                                    Открыть <EditIcon />
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-else class="flex flex-col p-8 justify-center items-center gap-4 border rounded-xl border-gray-300">
            <div class="p-4 rounded-4xl bg-gray-200">
                <CrossIcon :x="20" :y="20" />
            </div>
            <span class="font-bold">Нет публикаций</span>
        </div>
    </DashboardLayout>
</template>
