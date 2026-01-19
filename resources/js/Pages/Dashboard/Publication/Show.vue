<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import FileIcon from "@/Components/FileIcon.vue";

const props = defineProps({
    year: {
        type: Object,
        required: true
    },
    publication: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <Head :title="publication.name" />
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
                name: 'Публикации',
                url: route('dashboard.publication.index', year.slug)
            },
            {
                name: publication.name
            }
        ]" />

        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">{{ publication.name }}</h1>
        </div>

        <div class="flex flex-col gap-y-5">
            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                <div>
                    <span class="block text-sm font-medium text-gray-700">Публицадия добавлена пользователем</span>
                    <div>{{ publication.created_by }}</div>
                </div>

                <div class="flex flex-col gap-y-2">
                    <span class="block text-sm font-medium text-gray-700">Название</span>
                    <div>{{ publication.name }}</div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Вид публикации</span>
                        <div>{{ publication.publication_type }}</div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Название научного мероприятия</span>
                        <div>{{ publication.name_of_scientific_event }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Автор</span>
                        <div>{{ publication.author }}</div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Соавтор</span>
                        <div>{{ publication.co_author }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Издатель</span>
                        <div>{{ publication.publisher }}</div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Год</span>
                        <div>{{ publication.year }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Страницы</span>
                        <div>{{ publication.pages }}</div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Ссылка DOI</span>
                        <a
                            v-if="publication.DOI_url"
                            class="flex items-center gap-2 underline text-blue-600"
                            :href="publication.DOI_url"
                            target="_blank"
                        >
                            Открыть ссылку
                        </a>
                        <div v-else>-</div>
                    </div>
                </div>

                <div class="flex flex-col gap-y-2">
                    <span class="block text-sm font-medium text-gray-700">Библиографическое описание</span>
                    <div>{{ publication.bibliographic_description }}</div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Ссылка на репозиторий</span>
                        <a
                            class="flex items-center gap-2 underline text-blue-600"
                            :href="publication.repository_url"
                            target="_blank"
                        >
                            Открыть ссылку
                        </a>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Файл</span>
                        <div v-if="publication.file" class="flex gap-8">
                            <div class="flex items-center gap-2">
                                <FileIcon :type="publication.file.extension" :width="32" :height="32" />
                                <span>{{ publication.file.name }} ({{ publication.file.size }})</span>
                            </div>
                            <a
                                class="inline-flex items-center rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                                :href="route('dashboard.api.file.download', {file: publication.file.id})"
                            >
                                Скачать
                            </a>
                        </div>
                        <div v-else>
                            -
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </DashboardLayout>
</template>
