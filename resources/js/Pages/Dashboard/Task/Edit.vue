<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    task: {
        type: Object,
        required: true
    },
    user_type_select: {
        type: Array,
        required: true
    },
    year: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
})

const form = useForm({
    id: props.task.id,
    name: props.task.name,
    description: props.task.description,
    starts_on: props.task.starts_on,
    ends_on: props.task.ends_on,
    assigned_type: props.task.assigned_type,
    assigned_at: props.task.assigned_at,
    rating: props.task.rating
})

const updateTask = () => {
    form.patch(route('dashboard.task.update', {academicYear: props.year.slug, task: form.id}))
}

const deleteTask = () => {
    form.delete(route('dashboard.task.destroy', {academicYear: props.year.slug, task: form.id}))
}

</script>

<template>
    <Head title="Изменить задачу" />
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
                 name: 'Задачи',
                url: route('dashboard.task.index', year.slug)
            },
            {
                name: 'Изменить задачу'
            }
        ]" />
        <div v-if="form.wasSuccessful" class="flex">
            <div class="px-8 py-4 rounded-2xl border shadow-2xl border-gray-200 bg-green-400">
                Задача обновлена
            </div>
        </div>
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Изменить задачу</h1>
            <DangerButton @click.prevent="deleteTask">Удалить задачу</DangerButton>
        </div>
        <form @submit.prevent="updateTask" class="flex flex-col gap-y-5">

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
                <div class="flex flex-col gap-y-2">
                    <InputLabel for="email" value="Для кого" />
                    <CustomSelect v-model="form.assigned_type" :options="user_type_select" placeholder="Для кого"/>
                    <InputError class="mt-2" :message="form.errors.assigned_type" />
                </div>
                <div v-if="form.assigned_type === 'personal'" class="grid">
                    <InputLabel for="assigned_at" value="Выберите сотрудников" />
                    <CustomSelect v-model="form.assigned_at" :options="users" :is_multi="true" placeholder="Для кого" />
                    <InputError class="mt-2" :message="form.errors.assigned_at" />
                </div>
                <div class="flex flex-col gap-y-2">
                    <InputLabel for="rating" value="Рейтинг" />
                    <DashboardInput id="rating" v-model="form.rating" type="number" required />
                    <InputError class="mt-2" :message="form.errors.rating" />
                </div>
            </div>

            <button
                class="inline-flex items-center w-fit rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                type="submit"
            >
                Обновить
            </button>

        </form>
    </DashboardLayout>
</template>
