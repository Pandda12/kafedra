<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import EmployeeLayout from "@/Layouts/EmployeeLayout.vue";
import TaskStatus from "@/Components/TaskStatus.vue";
import FileInput from "@/Components/Forms/FileInput.vue";

const props = defineProps({
    task: {
        type: Object,
        required: true
    }
})

const form = useForm({
    id: props.task.id,
    description: null,
    file: null as File | null,
})

const updateTask = () => {
    form.transform(data => ({
        ...data,
        status: props.task.action.status,
    })).post(props.task.action.url)
}

</script>

<template>
    <Head :title="props.task.name" />
    <EmployeeLayout>
        <Breadcrumbs :breadcrumbs="[
            {
                name: 'Главная',
                url: route('home')
            },
            {
                name: 'Задачи',
                url: route('employee.tasks.index')
            },
            {
                name: props.task.name
            }
        ]" />
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold">{{ props.task.name }}</h1>
        </div>

        <form @submit.prevent="updateTask" class="flex flex-col gap-y-5">

            <div class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Название</span>
                        <div>{{ task.name }}</div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <span class="block text-sm font-medium text-gray-700">Статус</span>
                        <div>
                            <TaskStatus :status-value="task.status.value" :status-name="task.status.name" />
                        </div>
                        <div v-if="task.reject_reason">
                            <span class="block text-sm font-medium text-gray-700">Причина отклонения</span>
                            <div>{{ task.reject_reason }}</div>
                        </div>
                    </div>
                </div>

                <div v-if="props.task.description" class="flex flex-col gap-y-2">
                    <span class="block text-sm font-medium text-gray-700">Описание</span>
                    <div>{{ props.task.description }}</div>
                </div>

                <div class="grid grid-cols-2 gap-x-5">
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Дата начала</span>
                        <div>{{ props.task.starts_on }}</div>
                    </div>
                    <div class="flex flex-col gap-y-2">
                        <span class="block text-sm font-medium text-gray-700">Дата окончания</span>
                        <div>{{ props.task.ends_on }}</div>
                    </div>
                </div>

                <div class="flex flex-col gap-y-2">
                    <span class="block text-sm font-medium text-gray-700">Рейтинг</span>
                    <div>{{ props.task.rating }}</div>
                </div>

            </div>

            <div
                v-if="props.task.show_upload_form"
                class="flex flex-col gap-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
            >
                <div class="flex flex-col gap-y-2">
                    <span class="block text-sm font-medium text-gray-700">Ответ</span>
                    <textarea class="px-2 py-3 rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-600" id="description" v-model="form.description" required></textarea>
                </div>

                <div class="flex flex-col gap-y-2">

                    <label class="block mb-2 text-sm font-medium text-gray-900" for="file_input">Загрузить файл</label>

                    <FileInput
                        v-model="form.file"
                        :progress="form.progress"
                        help-text="DOC, DOCX и PDF"
                    />
                    <!-- <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                        aria-describedby="file_input_help"
                        id="file_input"
                        type="file"
                        accept=".doc,.docx,.pdf"
                        @input="form.file = $event.target.files[0]"
                    />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <p class="mt-1 text-sm text-gray-500" id="file_input_help">DOC, DOCX и PDF</p> -->

                </div>
            </div>

            <button
                v-if="props.task.action.status"
                class="inline-flex items-center w-fit rounded-md px-4 py-2 text-md font-semibold hover:cursor-pointer text-white bg-blue-600 hover:bg-blue-400"
                type="submit"
            >
                {{ props.task.action.btn_text }}
            </button>

        </form>
    </EmployeeLayout>
</template>
