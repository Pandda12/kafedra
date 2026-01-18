<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    options: {
        type: Object,
        required: true
    }
})

const form = useForm({
    task_date_color: {
        red: props.options.task_date_color.red,
        yellow: props.options.task_date_color.yellow,
        green: props.options.task_date_color.green,
    },
    publication_rating: props.options.publication_rating,
    event_rating: props.options.event_rating
})

const saveOptions = () => {
    form.post(route('dashboard.option.store', props.year.slug))
}
</script>

<template>
    <Head title="Настройки" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: year.name,
                url: route('dashboard.academic_year.show', year.slug)
            },
            {
                name: 'Настройки'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Настройки</h1>
        </div>

        <div v-if="form.wasSuccessful" class="flex">
            <div class="px-8 py-4 rounded-2xl border shadow-2xl border-gray-200 bg-green-400">
                Настройки обновлены
            </div>
        </div>

        <form @submit.prevent="saveOptions" class="flex flex-col gap-y-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <h2>Настройки цветовых предупреждений</h2>

                <div class="flex flex-col gap-y-2 w-1/2">
                    <div class="flex justify-between items-center gap-2">
                        <InputLabel for="red" value="Красный (меньше или равной дней)" class="p-2 rounded-xl bg-red-600/50" />
                        <div class="flex justify-between items-center gap-6">
                            <div class="h-3 w-3 rounded-2xl bg-red-600"></div>
                            <DashboardInput id="red" v-model="form.task_date_color.red" type="number" required />
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.red" />
                </div>

                <div class="flex flex-col gap-y-2 w-1/2">
                    <div class="flex justify-between items-center gap-2">
                        <InputLabel for="yellow" value="Жёлтый (меньше или равной дней)" class="p-2 rounded-xl bg-yellow-400/50" />
                        <div class="flex justify-between items-center gap-6">
                            <div class="h-3 w-3 rounded-2xl bg-yellow-400"></div>
                            <DashboardInput id="yellow" v-model="form.task_date_color.yellow" type="number" required />
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.yellow" />
                </div>

                <div class="flex flex-col gap-y-2 w-1/2">
                    <div class="flex justify-between items-center gap-2">
                        <InputLabel for="green" value="Зелёный (меньше или равной дней)" class="p-2 rounded-xl bg-green-600/50" />
                        <div class="flex justify-between items-center gap-6">
                            <div class="h-3 w-3 rounded-2xl bg-green-600"></div>
                            <DashboardInput id="green" v-model="form.task_date_color.green" type="number" required />
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.green" />
                </div>

            </div>

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <h2>Настройки рейтинга</h2>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="publication_rating" value="Рейтинг мероприятий" />
                        <DashboardInput id="publication_rating" v-model="form.publication_rating" type="number" required />
                        <InputError class="mt-2" :message="form.errors.publication_rating" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="event_rating" value="Рейтинг публикаций" />
                        <DashboardInput id="event_rating" v-model="form.event_rating" type="number" required />
                        <InputError class="mt-2" :message="form.errors.event_rating" />
                    </div>
                </div>

            </div>

            <PrimaryButton>Сохранить настройки</PrimaryButton>
        </form>
    </DashboardLayout>
</template>
