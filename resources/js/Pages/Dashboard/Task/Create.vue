<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
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
    name: null,
    description: null,
    starts_on: null,
    ends_on: null,
    assigned_type: null,
    assigned_at: [],
    rating: null
})

const createTask = () => {
    form.post(route('dashboard.task.store', props.year.slug))
}

</script>

<template>
    <Head title="Добавить задачу" />
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
                Добавить
            </button>

        </form>
    </DashboardLayout>
</template>
