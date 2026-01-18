<script setup lang="ts">
import {onMounted, ref} from "vue";
import {Head, Link} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import ApexChart from "@/Components/Charts/ApexChart.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import TaskStatus from "@/Components/TaskStatus.vue";
import TaskDateColor from "@/Components/TaskDateColor.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";

const props = defineProps({
    tasks: {
        type: Object,
        required: true
    }
})

const series = ref([])
const status = new URL(window.location).searchParams.get('status');
const is_closed = status === 'closed'

const getTasksChartData = () => {
    axios.get(route('employee.api.tasks.charts'))
        .then(function (response) {
            series.value = response.data.data
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
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
            barHeight: '80%',
            rangeBarGroupRows: false,
        },
    },
    xaxis: { type: 'datetime' },
    stroke: { width: 1 },
    fill: { type: 'solid', opacity: 0.6 },
    legend: { position: 'top', horizontalAlign: 'left' },
    width: '100%'
});

onMounted(
    getTasksChartData
)

</script>

<template>
    <Head title="Задачи" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Задачи'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Задачи</h1>
            <PrimaryLink :link="route('employee.task.create')">Предложить задачу</PrimaryLink>
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
        <div>
            <PrimaryLink v-if="is_closed" :link="route('employee.tasks.index')">
                Все задачи
            </PrimaryLink>
            <PrimaryLink v-else :link="route('employee.tasks.index', {status: 'closed'})" prefetch>
                Закрытые задачи
            </PrimaryLink>
        </div>
        <div v-if="tasks.length && status !== 'closed'">
            <ApexChart :series="series" :options="chartOptions" :height="450" />
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
                                Название
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Статус
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Срок с
                            </th>
                            <template v-if="is_closed">
                                <th  scope="col" class="px-6 py-3">
                                    Срок до
                                </th>
                                <th  scope="col" class="px-6 py-3">
                                    Закрыта
                                </th>
                                <th scope="col" colspan="2" class="px-6 py-3">
                                    Рейтинг
                                </th>
                            </template>
                            <th v-else scope="col" colspan="2" class="px-6 py-3">
                                Срок до
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
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ task.name }}
                            </th>
                            <td v-if="!is_closed" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <TaskStatus :status-value="task.status.value" :status-name="task.status.name" />
                            </td>
                            <td v-else class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                               {{ task.status.closed_status }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ task.starts_on }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <TaskDateColor v-if="!is_closed" :date="task.ends_on" :color="task.color" />
                                <span v-else>{{ task.ends_on }}</span>
                            </td>
                            <template v-if="is_closed">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ task.status.completed_at }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ task.rating }}
                                </td>
                            </template>
                            <td>
                                <Link
                                    :href="route('employee.tasks.show', task.id)"
                                >
                                    Перейти
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
    </EmployeeLayout>
</template>