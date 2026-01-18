<script setup lang="ts">
import {ref, watch} from "vue";
import {Head, Link} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import InProgress from "@/Components/Report/InProgress.vue";
import Closed from "@/Components/Report/Closed.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    task_statuses: {
        type: Array,
        required: true
    }
})

const assigned_at = ref(null)
const status = ref(null)

const tasks = ref([])

const getReports = () => {
    axios.get(route('dashboard.api.reports', props.year.slug), {
            params: {
                user: assigned_at.value,
                status: status.value
            }
        })
        .then(function (response) {
            tasks.value = response.data.data
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .finally(function () {
            // always executed
        });
}

watch([assigned_at, status], ([newAssigned, newStatus], [oldAssigned, oldStatus]) => {
    // вызов только когда оба заполнены
    if (newAssigned == null || newStatus == null) return

    // сработает при изменении любого из двух
    if (newAssigned !== oldAssigned || newStatus !== oldStatus) {
        getReports()
    }
})

</script>

<template>
    <Head title="Отчёты" />
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
                name: 'Отчёты'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Отчёты</h1>
        </div>

        <div class="grid grid-cols-2 gap-x-4">
            <div class="flex flex-col gap-y-2">
                <InputLabel for="email" value="Выбор сотрудника" />
                <CustomSelect
                    v-model="assigned_at"
                    :options="users"
                    placeholder="Для кого"
                />
            </div>

            <div class="flex flex-col gap-y-2">
                <InputLabel for="email" value="Выбор стуса" />
                <CustomSelect
                    v-model="status"
                    :options="task_statuses"
                    placeholder="Статус"
                />
            </div>
        </div>
        <div v-if="tasks.length" class="flex flex-col gap-y-4">
            <div
                v-for="(task, index) in tasks"
                class="px-8 py-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                :key="index"
            >
                <InProgress
                    v-if="task.type === 'in_progress'"
                    :task="task.task"
                />
                <Closed
                    v-if="task.type === 'closed'"
                    :task="task.task"
                />
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