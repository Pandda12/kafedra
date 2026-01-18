<script setup lang="ts">
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import FileIcon from "@/Components/FileIcon.vue";

const props = defineProps({
    publication_rating: {
        type: Number,
        required: true
    },
    publications: {
        type: Array,
        required: true
    }
})

// const publication_total_rating = ref()

</script>

<template>
    <Head title="Публикации" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Публикации'
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">Публикации</h1>
            <PrimaryLink :link="route('employee.publications.create')">
                Добавить публикацию
            </PrimaryLink>
        </div>
        <div class="grid grid-cols-3 gap-x-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Всего публикаций
                </span>
                <span class="text-3xl font-semibold tracking-tight text-gray-950">
                    {{ publications.length }}
                </span>
            </div>
        </div>

        <div v-if="publications.length">
            <div class="relative border border-gray-200 overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                &nbsp;
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Библиографическое описание
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Ссылка на репозиторий
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата добавления
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Файл
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Рейтинг
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(publication, index) in publications"
                            :key="index"
                            class="bg-white border-b  border-gray-200 hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ index + 1 }}
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap truncate max-w-28">
                                {{ publication.bibliographic_description }}
                            </th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <a
                                    class="flex items-center gap-2 underline text-blue-600"
                                    :href="publication.repository_url"
                                    target="_blank"
                                >
                                    Открыть ссылку <EditIcon/>
                                </a>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ publication.date_added }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <div v-if="publication.file" class="flex items-center gap-2 ">
                                    <FileIcon :type="publication.file.type" :width="32" :height="32" />
                                    <span class="truncate max-w-28">
                                        {{ publication.file.name }} ({{ publication.file.size }})
                                    </span>
                                </div>
                                <div v-else>
                                    -
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ publication_rating }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b  border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-right text-gray-900 whitespace-nowrap" colspan="6">
                                Текущий рейтинг за публикации: {{ publication_rating * publications.length }}
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
            <span class="font-bold">Нет публикаций</span>
        </div>
    </EmployeeLayout>
</template>