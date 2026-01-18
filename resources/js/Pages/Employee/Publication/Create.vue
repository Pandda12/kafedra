<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DashboardInput from "@/Components/DashboardInput.vue";
import InputError from "@/Components/InputError.vue";
import CustomSelect from "@/Components/CustomSelect.vue";
import FileInput from "@/Components/Forms/FileInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    publication_types: {
        type: Array,
        required: true
    }
})

const form = useForm({
    name: null,
    publication_type_id: null,
    name_of_scientific_event: null,
    author: null,
    co_author: null,
    publisher: null,
    year: null,
    pages: null,
    DOI_url: null,
    bibliographic_description: null,
    repository_url: null,
    file: null as File | null
})

const create = () => {
    form.post(route('employee.publications.store'))
}

</script>

<template>
    <Head title="Добавить публикацию" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Публикации',
                url: route('employee.publications.index')
            },
            {
                name: 'Добавить публикацию'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Добавить публикацию</h1>
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
                        <InputLabel for="publication_type_id" value="Вид публикации" />
                        <CustomSelect
                            id="publication_type_id"
                            v-model="form.publication_type_id"
                            :options="publication_types"
                        />
                        <InputError class="mt-2" :message="form.errors.publication_type_id" />

                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="name_of_scientific_event" value="Название научного мероприятия" />
                        <DashboardInput
                            id="name_of_scientific_event"
                            v-model="form.name_of_scientific_event"
                            type="text"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.name_of_scientific_event" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="author" value="Автор" />
                        <DashboardInput id="author" v-model="form.author" type="text" required />
                        <InputError class="mt-2" :message="form.errors.author" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="co_author" value="Соавтор (необязательно)" />
                        <DashboardInput id="co_author" v-model="form.co_author" type="text" />
                        <InputError class="mt-2" :message="form.errors.co_author" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="publisher" value="Издатель" />
                        <DashboardInput id="publisher" v-model="form.publisher" type="text" />
                        <InputError class="mt-2" :message="form.errors.publisher" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="year" value="Год" />
                        <DashboardInput id="year" v-model="form.year" type="text" />
                        <InputError class="mt-2" :message="form.errors.year" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="pages" value="Страницы" />
                        <DashboardInput id="pages" v-model="form.pages" type="text" />
                        <InputError class="mt-2" :message="form.errors.pages" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="DOI_url" value="Ссылка DOI (необязательно)" />
                        <DashboardInput id="DOI_url" v-model="form.DOI_url" />
                        <InputError class="mt-2" :message="form.errors.DOI_url" />
                    </div>
                </div>

                <div class="flex flex-col gap-y-2">
                    <InputLabel for="bibliographic_description" value="Библиографическое описание" />
                    <DashboardInput id="bibliographic_description" v-model="form.bibliographic_description" required />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="repository_url" value="Ссылка на репозиторий" />
                        <DashboardInput id="repository_url" v-model="form.repository_url" type="text" />
                        <InputError class="mt-2" :message="form.errors.repository_url" />
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <InputLabel for="pages" value="Файл (необязательно)" />
                        <FileInput
                            v-model="form.file"
                            :progress="form.progress"
                            help-text="DOC, DOCX и PDF"
                        />
                        <InputError class="mt-2" :message="form.errors.file" />
                    </div>
                </div>

            </div>

            <PrimaryButton>Добавить публикацию</PrimaryButton>

        </form>
    </EmployeeLayout>
</template>
