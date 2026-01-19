<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    academic_years: {
        type: Number,
        required: true
    },
    users: {
        type: Number,
        required: true
    },
    tasks: {
        type: Number,
        required: true
    },
    publications: {
        type: Number,
        required: true
    },
    events: {
        type: Number,
        required: true
    }
})
</script>

<template>
    <Head title="Админ панель" />
    <DashboardLayout :is_full_sidebar="false">
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Админ панель</h1>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего учебных годов
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ academic_years }}
                </span>
            </div>

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего пользователей
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ users }}
                </span>
            </div>

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего задач
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ tasks }}
                </span>
            </div>

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего публикаций
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ publications }}
                </span>
            </div>

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего мероприятий
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ events }}
                </span>
            </div>
        </div>

    </DashboardLayout>
</template>
