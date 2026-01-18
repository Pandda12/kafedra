<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import IsActive from "@/Components/isActive.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";

defineProps({
    years: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head title="Учебные года" />
    <DashboardLayout :is_full_sidebar="false">
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: 'Учебные года'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Учебные года</h1>
            <PrimaryLink :link="route('dashboard.academic_year.create')">
                Добавить
            </PrimaryLink>
        </div>
        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего учебных годов
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ years.length }}
                </span>
            </div>
        </div>
        <div v-if="years.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Учебный год
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Статус
                            </th>
                            <th scope="col" colspan="2" class="px-6 py-3">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(year, index) in years"
                            :key="index"
                            class="bg-white border-b  border-gray-200 hover:bg-gray-50"
                        >
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ year.name }}
                            </th>
                            <td>
                                <div class="px-8">
                                    <isActive :is_active="year.is_active" />
                                </div>
                            </td>
                            <td>
                                <Link
                                    :href="route('dashboard.academic_year.show', year.slug)"
                                >
                                    Перейти
                                </Link>
                            </td>
                            <td>
                                <Link
                                    class="flex items-center gap-x-1 text-blue-600"
                                    :href="route('dashboard.academic_year.edit', year.slug)"
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
        <div v-else>
            Нет учебных годов
        </div>
    </DashboardLayout>
</template>
