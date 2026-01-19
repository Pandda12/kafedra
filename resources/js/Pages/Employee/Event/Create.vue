<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputError from "@/Components/InputError.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    participation_forms: {
        type: Array,
        required: true
    }
})

const form = useForm({
    name: null,
    place: null,
    date: null,
    participation_form_id: null,
    title_of_the_report: null
})

const create = () => {
    form.post(route('employee.events.store'))
}

</script>

<template>
    <Head title="Добавить мероприятие" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Мероприятия',
                url: route('employee.events.index')
            },
            {
                name: 'Добавить мероприятие'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Добавить мероприятие</h1>
        </div>

        <form @submit.prevent="create" class="flex flex-col gap-y-5">

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                <div class="flex flex-col gap-y-2">
                    <InputLabel for="name" value="Название" />
                    <DashboardInput id="name" v-model="form.name" required />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="place" value="Место проведения" />
                        <DashboardInput
                            id="place"
                            v-model="form.place"
                            type="text"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.place" />

                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="date" value="Дата проведения" />
                        <DashboardInput
                            id="date"
                            v-model="form.date"
                            type="date"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.date" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="participation_form" value="Форма участия" />
                        <CustomSelect
                            id="participation_form"
                            v-model="form.participation_form_id"
                            :options="participation_forms"
                        />
                        <InputError class="mt-2" :message="form.errors.participation_form_id" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="title_of_the_report" value="Название доклада (необязательно)" />
                        <DashboardInput id="title_of_the_report" v-model="form.title_of_the_report" type="text" />
                        <InputError class="mt-2" :message="form.errors.title_of_the_report" />
                    </div>
                </div>

            </div>

            <PrimaryButton>Добавить мероприятие</PrimaryButton>
        </form>
    </EmployeeLayout>
</template>
