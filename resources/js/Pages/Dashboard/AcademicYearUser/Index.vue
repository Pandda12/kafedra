<script setup lang="ts">
import {ref} from "vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import IsActive from "@/Components/isActive.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import InputError from "@/Components/InputError.vue";
import FileInput from "@/Components/Forms/FileInput.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    import_roles: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
})

const show_upload_users = ref(false)

const form = useForm({
    role: null,
    file: null as File | null
})

const toggleShowUploadUsers = () => {
    show_upload_users.value = !show_upload_users.value
}

const uploadUsers = () => {
    form.post(route('dashboard.user.import', props.year.slug))
}
</script>

<template>
    <Head title="Пользователи" />
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
                name: 'Пользователи'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Пользователи</h1>
            <div class="flex gap-x-4">
                <PrimaryButton @click="toggleShowUploadUsers">
                    Загрузить пользователей
                </PrimaryButton>
                <PrimaryLink :link="route('dashboard.academic_year_user.create', year.slug)">
                    Добавить
                </PrimaryLink>
            </div>
        </div>
        <div v-if="show_upload_users">
            <form @submit.prevent="uploadUsers">
                <div class="grid col-span-2 gap-y-5">
                    <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                        <div class="grid">
                            <div class="flex flex-col gap-y-2">
                                <InputLabel for="role" value="Роль" />
                                <CustomSelect id="role" :options="import_roles" v-model="form.role" required />
                                <InputError class="mt-2" :message="form.errors.role" />
                            </div>
                        </div>
                        <div class="grid">
                            <div class="flex flex-col gap-y-2">
                                <InputLabel for="file" value="Файл импорта" />
                                <FileInput
                                    id="file"
                                    v-model="form.file"
                                    :progress="form.progress"
                                    help-text="XLSX"
                                    accept="xlsx"
                                />
                                <InputError class="mt-2" :message="form.errors.file" />
                            </div>
                        </div>
                    </div>

                    <PrimaryButton>Импортировать пользователей</PrimaryButton>
                </div>
            </form>
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
        <div v-if="users.length" class="flex gap-x-8">
            <PrimaryLink
                :link="route('dashboard.academic_year_user.index', year.slug)"
            >
                Все сотрудники
            </PrimaryLink>

            <PrimaryLink
                :link="route('dashboard.academic_year_user.index', { academicYear: year.slug, type: 'full-time' })"
            >
                Штатные сотрудники
            </PrimaryLink>

            <PrimaryLink
                :link="route('dashboard.academic_year_user.index', { academicYear: year.slug, type: 'part-time' })"
            >
                Сотрудники по совместительству
            </PrimaryLink>
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
                                Имя
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Почта
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
                                {{ user.name }}
                            </th>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ user.email }}
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
                                    :href="user.edit_link"
                                    class="flex items-center gap-x-2 text-blue-600"
                                >
                                    Редактировать
                                    <EditIcon />
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
