<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    active_tasks_count: {
        type: Number,
        required: true
    },
    closed_tasks_count: {
        type: Number,
        required: true
    },
    publications_count: {
        type: Number,
        required: true
    },
    events_count: {
        type: Number,
        required: true
    },
})
</script>

<template>
    <Head title="Главная" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
        {
            name: 'Главная',

        }
        ]" />
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold">Задачи</h1>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <Link
                    class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                    :href="route('employee.tasks.index')"
                    prefetch
                >
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Задач в работе
                    </span>
                        <span class="text-3xl font-semibold tracking-tight text-gray-950">
                        {{ active_tasks_count }}
                    </span>
                </Link>
                <Link
                    class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                    :href="route('employee.tasks.index', {status: 'closed'})"
                    prefetch
                >
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Закрытых задач
                    </span>
                    <span class="text-3xl font-semibold tracking-tight text-gray-950">
                        {{ closed_tasks_count }}
                    </span>
                </Link>
                <Link
                    class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                    :href="route('employee.publications.index')"
                    prefetch
                >
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Публикаций
                    </span>
                    <span class="text-3xl font-semibold tracking-tight text-gray-950">
                        {{ publications_count }}
                    </span>
                </Link>
                <Link
                    class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                    :href="route('employee.events.index')"
                    prefetch
                >
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Мероприятий
                    </span>
                    <span class="text-3xl font-semibold tracking-tight text-gray-950">
                        {{ events_count }}
                    </span>
                </Link>
            </div>
    </EmployeeLayout>
</template>
