<script setup lang="ts">
import {Head, Link} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import IsActive from "@/Components/isActive.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";

defineProps({
    users: {
        type: Array,
        required: true
    }
})

</script>

<template>
    <Head title="Все пользователи" />
    <DashboardLayout :is_full_sidebar="false">
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: 'Все пользователи'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Все пользователи</h1>
            <div class="flex gap-x-4">
                <PrimaryLink :link="route('dashboard.user.create')">
                    Добавить
                </PrimaryLink>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего Пользователи
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ users.length }}
                </span>
            </div>
        </div>
        <div v-if="users.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                &nbsp;
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Пользователь
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Роль
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Статус
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(user, index) in users"
                            :key="index"
                            class="bg-white border-b  border-gray-200 hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <div class="flex flex-col gap-y-2">
                                    <div class="font-medium">{{ user.name }}</div>
                                    <div class="font-normal">{{ user.email }}</div>
                                </div>
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ user.role }}
                            </th>
                            <td class="mx-auto">
                                <div class="flex justify-center">
                                    <isActive :is_active="user.is_active" />
                                </div>
                            </td>

                            <td>
                                <Link
                                    :href="route('dashboard.user.edit', user.id)"
                                    class="flex items-center gap-x-2 text-blue-600"
                                >
                                    <EditIcon />
                                    Редактировать
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
            <span class="font-bold">Нет пользователей</span>
        </div>
    </DashboardLayout>
</template>
