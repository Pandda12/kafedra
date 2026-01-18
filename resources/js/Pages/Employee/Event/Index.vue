<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";

defineProps({
    events: {
        type: Array,
        required: true
    },
    event_rating: {
        type: Number,
        required: true
    }
})

</script>

<template>
    <Head title="Мероприятия" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Мероприятия'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Мероприятия</h1>
            <PrimaryLink :link="route('employee.events.create')">
                Добавить мероприятие
            </PrimaryLink>
        </div>
        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего мероприятий
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ events.length }}
                </span>
            </div>
        </div>

        <div v-if="events.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                &nbsp;
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Название мероприятия
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Место проведения
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата мероприятия
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Форма участия
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Название доклада
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Рейтинг
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата добавления
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(event, index) in events"
                            :key="index"
                            class="bg-white border-b  border-gray-200 hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ event.name }}
                            </th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ event.place }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ event.date }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ event.participation_form }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ event.title_of_the_report }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ event_rating }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ event.date_added }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-right text-gray-900 whitespace-nowrap" colspan="8">
                                Текущий рейтинг за мероприятия: {{ event_rating * events.length }}
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
            <span class="font-bold">Нет мероприятий</span>
        </div>
    </EmployeeLayout>
</template>