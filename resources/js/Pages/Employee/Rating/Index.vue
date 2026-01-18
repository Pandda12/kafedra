<script setup lang="ts">
import {onMounted, ref, watch} from "vue";
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import BarChart from "@/Components/Charts/BarChart.vue";

const month = ref(null)
const show = ref(false)

const series = ref([])
const chartOptions = ref({
    chart: {
        height: 350,
        type: 'bar',
    },
    xaxis: {
        categories: [],
        tooltip: {
            enabled: true,
        }
    },

});

defineProps({
    months: {
        type: Array,
        required: true
    }
})

const getRating = () => {
    axios.get(route('employee.api.rating.charts'), {
        params: {
            month: month.value
        }
    })
        .then(function (response) {

            show.value = (response.data.data.items?.length ?? 0) > 0

            series.value = [{
                name: "Рейтинг",
                data: response.data.data.rating,
            }]

            chartOptions.value = {
                ...chartOptions.value,
                xaxis: {
                    ...chartOptions.value.xaxis,
                    categories: response.data.data.items,
                },
            }
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
}

watch(month, async (newValue, oldValue) => {
    if ( newValue !== oldValue ) {
        getRating()
    }
})

onMounted(
    getRating
)
</script>

<template>
    <Head title="Рейтинг" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Рейтинг'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Рейтинг</h1>
        </div>

        <div>
            <div class="grid grid-cols-2 gap-x-4">
                <div class="flex flex-col gap-y-2">
                    <InputLabel for="email" value="Общий рейтинг за месяц" />
                    <CustomSelect
                        v-model="month"
                        :options="months"
                        placeholder="Текущий месяц"
                    />
                </div>
            </div>
            <BarChart v-if="show" :series="series" :options="chartOptions" />
        </div>

        <div v-if="!show" class="flex flex-col p-8 justify-center items-center gap-4 border rounded-xl border-gray-300">
            <div class="p-4 rounded-4xl bg-gray-200">
                <CrossIcon :x="20" :y="20" />
            </div>
            <span class="font-bold">Нет данных для отображение</span>
        </div>
    </EmployeeLayout>
</template>