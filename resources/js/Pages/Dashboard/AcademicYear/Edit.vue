<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import InputError from "@/Components/InputError.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    }
})

const form = useForm({
    id: props.year.id,
    name: props.year.name,
    is_active: props.year.is_active,
    starts_on: props.year.starts_on,
    ends_on: props.year.ends_on,
    description: props.year.description
});

const createAcademicYear = () => {
    form.patch(route('dashboard.academic_year.update', props.year.slug))
}

const deleteAcademicYear = () => {
    form.delete(route('dashboard.academic_year.destroy', props.year))
}
</script>

<template>
    <Head title="Изменить учебный год" />
    <DashboardLayout :is_full_sidebar="false">
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
                name: 'Учебные года'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Изменить учебный год</h1>
            <DangerButton @click.prevent="deleteAcademicYear">Удалить учебный год</DangerButton>
        </div>
        <form @submit.prevent="createAcademicYear" class="grid grid-cols-3 gap-x-5">
            <div class="grid col-span-2 gap-y-5">
                <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <label for="name">Название</label>
                            <DashboardInput id="name" v-model="form.name" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-5">
                        <div class="flex flex-col gap-y-2">
                            <label for="starts_on">Начало</label>
                            <DashboardInput id="starts_on" v-model="form.starts_on" type="date" />
                            <InputError class="mt-2" :message="form.errors.starts_on" />
                        </div>
                        <div class="flex flex-col gap-y-2">
                            <label for="ends_on">Конец</label>
                            <DashboardInput id="ends_on" v-model="form.ends_on" type="date" />
                            <InputError class="mt-2" :message="form.errors.ends_on" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <label for="description">Описание</label>
                        <textarea class="px-2 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-600" id="description" v-model="form.description"></textarea>
                    </div>
                </div>

                <button
                    class="inline-flex items-center w-fit rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                    type="submit"
                >
                    Обновить
                </button>
            </div>
            <div class="flex flex-col">
                <div class="flex flex-col gap-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                    <h3 class="text-base font-semibold text-gray-950">
                        Статус
                    </h3>
                    <div class="flex items-center">
                        <ToggleSwitch v-model:active="form.is_active" />
                        <span class="ml-4">Активировать учебный год</span>
                    </div>
                    <div class="text-gray-500">
                        В системе активен только один год. При активации текущего активный статус с другого года снимается
                    </div>

                </div>
            </div>
        </form>
    </DashboardLayout>
</template>
