<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    roles: {
        type: Array,
        required: true
    }
})

const form = useForm({
    user: null,
    role: null
})

const addUser = () => {
    form.post(route('dashboard.academic_year_user.store', props.year.slug))
}
</script>

<template>
    <Head title="Добавить пользователя" />
    <DashboardLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: props.year.name,
                url: route('dashboard.academic_year.show', props.year.slug)
            },
            {
                name: 'Добавить пользователя'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Добавить пользователя</h1>
        </div>
        <form @submit.prevent="addUser" class="grid grid-cols-3 gap-x-5">
            <div class="grid col-span-2 gap-y-5">
                <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="user" value="Пользователь" />
                        <CustomSelect id="user" :options="users" v-model="form.user" />
                        <InputError class="mt-2" :message="form.errors.user" />
                    </div>

                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="role" value="Роль" />
                        <CustomSelect id="role" :options="roles" v-model="form.role" />
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>
                </div>

                <PrimaryButton>Создать пользователя</PrimaryButton>
            </div>
        </form>
    </DashboardLayout>
</template>
