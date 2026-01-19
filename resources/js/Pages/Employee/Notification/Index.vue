<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import {useNotifications} from "@/composables/useNotifications.js";
import EmployeeNotificationItem from "@/Components/Notifications/EmployeeNotificationItem.vue";

const { notifications, unreadCount } = useNotifications()

</script>

<template>
    <Head title="Уведомления" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Уведомления'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Уведомления</h1>

        </div>
        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего уведомлений
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ unreadCount }}
                </span>
            </div>
        </div>

        <div v-if="notifications.length" class="flex flex-col gap-4">
            <EmployeeNotificationItem
                v-for="(notification, index) in notifications"
                :key="index"
                :notification="notification"
            />
        </div>
        <div v-else class="flex flex-col p-8 justify-center items-center gap-4 border rounded-xl border-gray-300">
            <div class="p-4 rounded-4xl bg-gray-200">
                <CrossIcon :x="20" :y="20" />
            </div>
            <span class="font-bold">Нет уведомлений</span>
        </div>
    </EmployeeLayout>
</template>