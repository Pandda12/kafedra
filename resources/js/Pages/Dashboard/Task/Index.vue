<script setup lang="ts">
import {onMounted, ref, watch} from "vue";
import {Head, Link} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import ApexChart from "@/Components/Charts/ApexChart.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    tasks: {
        type: Object,
        required: true
    },
    user_type_select: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
})

const assigned_type = ref(null)
const assigned_at = ref(null)
const series = ref([])
const chartMaxCount = ref(0)

const getTasksChartData = () => {
    axios.get(route('dashboard.api.tasks.charts', props.year.slug), {
            params: {
                assigned_type: assigned_type.value,
                assigned_at: assigned_at.value
            }
        })
        .then(function (response) {
            series.value = response.data.data
            getChartCount(response.data.data)
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })

}

const chartOptions = ref({
    chart: {
        type: 'rangeBar',
        toolbar: { show: true },
        zoom: {
            enabled: true,
            allowMouseWheelZoom: false
        }

    },
    plotOptions: {
        bar: {
            horizontal: true,
            barHeight: '20px',
            rangeBarGroupRows: false,
        },
    },
    xaxis: { type: 'datetime' },
    stroke: { width: 1 },
    fill: { type: 'solid', opacity: 0.6 },
    legend: { position: 'top', horizontalAlign: 'left' },
});

const getChartCount = (d) => {
    let max = 0

    d.forEach((e) => {
        if ( max < e.data.length ) {
            max = e.data.length
        }
    })

    chartMaxCount.value = max
}

watch(assigned_type, async (newValue, oldValue) => {
    if ( newValue !== oldValue ) {
        getTasksChartData()
    }
})

watch(assigned_at, async (newValue, oldValue) => {
    if ( assigned_type.value === 'personal' ) {
        getTasksChartData()
    }
})

onMounted(
    getTasksChartData
)

</script>

<template>
    <Head title="Задачи" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: year.name,
                url: route('dashboard.academic_year.show', year.slug)
            },
            {
                name: 'Задачи'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Задачи</h1>
            <PrimaryLink :link="route('dashboard.task.create', year.slug)">
                Добавить задачу
            </PrimaryLink>
        </div>
        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего задач
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ tasks.length }}
                </span>
            </div>
        </div>

        <div v-if="tasks.length">
            <div class="grid grid-cols-2 gap-x-4 mb-8">
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
                        :is_multi="true"
                        placeholder="Для кого"

                    />
                </div>
            </div>
            <ApexChart
                v-if="series.length"
                :series="series"
                :options="chartOptions"
                :height="chartMaxCount * series.length * 25 + 150"
            />
            <div v-else class="flex flex-col p-8 justify-center items-center gap-4 border rounded-xl border-gray-300">
                <div class="p-4 rounded-4xl bg-gray-200">
                    <CrossIcon :x="20" :y="20" />
                </div>
                <span class="font-bold">Нет данных для отображение</span>
            </div>
        </div>

        <div v-if="tasks.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                &nbsp;
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Учебный год
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата начала
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата окончания
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Кол-во исполнителей
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(task, index) in tasks"
                        :key="index"
                        class="bg-white border-b  border-gray-200 hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ index + 1 }}
                        </td>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                            {{ task.name }}
                        </th>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ task.starts_on }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ task.ends_on }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ task.assigned_count }}
                        </td>
                        <td>
                            <Link
                                class="flex items-center gap-x-1 text-blue-600"
                                :href="route('dashboard.task.edit', {academicYear: year.slug, task: task.id})"
                            >
                                <EditIcon/>
                                Редакторовать
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
            <span class="font-bold">Нет задач</span>
        </div>
    </DashboardLayout>
</template>