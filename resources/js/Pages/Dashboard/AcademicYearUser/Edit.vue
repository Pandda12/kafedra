<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
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
    user: props.user.user,
    role: props.user.role
})

const updateUser = () => {
    form.patch(route('dashboard.academic_year_user.update', {
        academicYear: props.year.slug,
        academic_year_user: props.user
    }))
}

const deleteUser = () => {
    form.delete(route('dashboard.academic_year_user.destroy', {
        academicYear: props.year.slug,
        academic_year_user: props.user
    }))
}
</script>

<template>
    <Head title="Изменить пользователя" />
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
                name: 'Изменить пользователя'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Изменить пользователя</h1>
            <DangerButton @click.prevent="deleteUser">Удалить из учебного года</DangerButton>
        </div>
        <form @submit.prevent="updateUser" class="grid grid-cols-3 gap-x-5">
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

                <button
                    class="inline-flex items-center w-fit rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                    type="submit"
                >
                    Обновить
                </button>
            </div>
        </form>
    </DashboardLayout>
</template>
