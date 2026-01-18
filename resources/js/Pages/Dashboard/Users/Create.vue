<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    roles: {
        type: Array,
        required: true
    }
})

const form = useForm({
    first_name: null,
    second_name: null,
    last_name: null,
    email: null,
    role: null,
    is_active: false
})

const createUser = () => {
    form.post(route('dashboard.user.store'))
}
</script>

<template>
    <Head title="Добавить пользователя" />
    <DashboardLayout :is_full_sidebar="false">
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Админ панель',
                url: route('dashboard')
            },
            {
                name: 'Добавить пользователя'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Добавить пользователя</h1>
        </div>
        <form @submit.prevent="createUser" class="grid grid-cols-3 gap-x-5">
            <div class="grid col-span-2 gap-y-5">
                <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <InputLabel for="first_name" value="Имя" />
                            <DashboardInput id="first_name" v-model="form.first_name" />
                            <InputError class="mt-2" :message="form.errors.first_name" />
                        </div>
                    </div>
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <InputLabel for="second_name" value="Фамилия" />
                            <DashboardInput id="second_name" v-model="form.second_name" />
                            <InputError class="mt-2" :message="form.errors.second_name" />
                        </div>
                    </div>
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <InputLabel for="last_name" value="Отчество (необязательно)" />
                            <DashboardInput id="last_name" v-model="form.last_name" />
                            <InputError class="mt-2" :message="form.errors.last_name" />
                        </div>
                    </div>
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <InputLabel for="email" value="Почта" />
                            <DashboardInput id="email" v-model="form.email" type="email" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>
                    <div class="grid">
                        <div class="flex flex-col gap-y-2">
                            <InputLabel for="role" value="Роль" />
                            <CustomSelect id="role" :options="roles" v-model="form.role" />
                            <InputError class="mt-2" :message="form.errors.role" />
                        </div>
                    </div>
                </div>

                <PrimaryButton>Создать пользователя</PrimaryButton>
            </div>
            <div class="flex flex-col">
                <div class="flex flex-col gap-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                    <h3 class="text-base font-semibold text-gray-950">
                        Статус
                    </h3>
                    <div class="flex items-center">
                        <ToggleSwitch v-model:active="form.is_active" />
                        <span class="ml-4">Активировать пользователя</span>
                    </div>
                </div>
            </div>
        </form>
    </DashboardLayout>
</template>
