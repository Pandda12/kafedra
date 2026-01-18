<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    year: {
        type: Object,
        required: true
    },
    task_count: {
        type: Number,
        required: true
    },
    publication_count: {
        type: Number,
        required: true
    },
    event_count: {
        type: Number,
        required: true
    }
})
</script>

<template>
    <Head :title="year.name" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: year.name,
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">{{ year.name }}</h1>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <Link
                class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                :href="route('dashboard.task.index', year.slug)"
            >
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего задач
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ task_count }}
                </span>
            </Link>
            <Link
                class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                :href="route('dashboard.publication.index', year.slug)"
            >
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего публикаций
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ publication_count }}
                </span>
            </Link>
            <Link
                class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
                :href="route('dashboard.event.index', year.slug)"
            >
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего мероприятий
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ event_count }}
                </span>
            </Link>
        </div>

    </DashboardLayout>
</template>
