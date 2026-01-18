<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";

const form = useForm({
    name: null,
    description: null,
    starts_on: null,
    ends_on: null,
})

const createTask = () => {
    form.post(route('employee.task.store'))
}

</script>

<template>
    <Head title="Добавить задачу" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Задачи',
                url: route('employee.tasks.index')
            },
            {
                name: 'Добавить задачу'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Добавить задачу</h1>
        </div>
        <form @submit.prevent="createTask" class="flex flex-col gap-y-5">

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                <div class="grid">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="name" value="Название" />
                        <DashboardInput id="name" v-model="form.name" required />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                </div>

                <div class="flex flex-col gap-y-2">
                    <InputLabel for="description" value="Описание" />
                    <textarea class="px-2 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-600" id="description" v-model="form.description"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="starts_on" value="Дата начала" />
                        <DashboardInput id="starts_on" v-model="form.starts_on" type="date" required />
                        <InputError class="mt-2" :message="form.errors.starts_on" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="ends_on" value="Дата окончания" />
                        <DashboardInput id="ends_on" v-model="form.ends_on" type="date" required />
                        <InputError class="mt-2" :message="form.errors.ends_on" />
                    </div>
                </div>

            </div>

            <button
                class="inline-flex items-center w-fit rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                type="submit"
            >
                Добавить
            </button>

        </form>
    </EmployeeLayout>
</template>
