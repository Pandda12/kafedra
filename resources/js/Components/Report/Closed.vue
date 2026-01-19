<script setup lang="ts">
import {ref} from "vue";
import FileIcon from "@/Components/FileIcon.vue";

defineProps({
    task: {
        type: Object,
        required: true
    }
})

const is_open = ref(false)

const toggle = () => {
    is_open.value = !is_open.value
}
</script>

<template>
    <div>
        <div>
            <span
                @click.prevent="toggle"
                class="underline hover:cursor-pointer text-blue-600"
            >
            {{ task.name }}
            </span> – с {{ task.starts_on }} по {{ task.ends_on }}
        </div>
        <div class="flex flex-col">
            <span v-if="task.rejected_at">Отклонена {{ task.rejected_at }}</span>
            <span v-if="task.completed_at">Закрыта {{ task.completed_at }}</span>
            <span>Статус: {{ task.status }}</span>
        </div>
    </div>
    <div v-if="is_open" class="flex flex-col gap-4 mt-8">
        <h3 class="text-2xl font-bold">Отчёт</h3>

        <div>
            <span class="text-sm font-medium text-gray-700">Название задачи</span>
            <div>{{ task.name }}</div>
        </div>

        <div>
            <span class="text-sm font-medium text-gray-700">Описание</span>
            <div>{{ task.description }}</div>
        </div>

        <div>
            <span class="text-sm font-medium text-gray-700">Ответ исполнителя</span>
            <div>{{ task.report_text }}</div>
        </div>
        <div v-if="task.report_file" >
            <span class="text-sm font-medium text-gray-700">Файл</span>
            <div class="flex gap-8">
                <div class="flex items-center gap-2">
                    <FileIcon :type="task.report_file.extension" :width="32" :height="32" />
                    <span>{{ task.report_file.name }} ({{ task.report_file.size }})</span>
                </div>
                <a
                    class="inline-flex items-center rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                    :href="route('dashboard.api.file.download', {file: task.report_file.id})"
                >
                    Скачать
                </a>
            </div>
        </div>
    </div>
</template>