<script setup lang="ts">
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
import FileIcon from "@/Components/FileIcon.vue";
import DangerButton from "@/Components/DangerButton.vue";
import CrossIcon from "@/Components/Icons/CrossIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownIcon from "@/Components/Icons/DropdownIcon.vue";
import {useAdminNotifications} from "@/composables/useAdminNotifications.js";

const props = defineProps({
    notification: {
        type: Object,
        required: true
    }
})

const is_open = ref(false)
const is_open_reject_block = ref(false)
const reject_reason = ref(null)

const { markAsRead } = useAdminNotifications()

const toggle = () => { is_open.value = !is_open.value }

const updateTaskStatus = async (status) => {
    const id = props.notification.id

    try {
        const { data } = await axios.patch(
            route('dashboard.api.task.update', props.notification.data.task.id),
            {
                id,
                task_user_id: props.notification.data.task.task_user_id,
                status: status,
                reject_reason: reject_reason.value,
            }
        )

        if (data?.status) {
            await markAsRead(id, status)
            is_open_reject_block.value = false
            reject_reason.value = null
        } else {
            throw new Error('Update task status failed')
        }
    } catch (e) {
        console.error(e)
    }
}


const rejectTask = () => {
    updateTaskStatus(props.notification.data.status.reject)
    is_open_reject_block.value = false
}
</script>

<template>
    <div
        class="px-8 py-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
    >
        <div class="flex justify-between gap-x-8">
            <div class="flex flex-col gap-3">
                <div>
                    Пользователь {{ notification.data.user }} закрыл задачу
                    <span
                        class="underline hover:cursor-pointer text-blue-600"
                        @click.prevent="toggle"
                    >
                        {{ notification.data.task.name }}
                    </span>
                </div>
                <div class="flex flex-col">
                    <span>Учебный год: {{ notification.data.year.name }}</span>
                    <Link :href="notification.data.task.url" class="underline hover:cursor-pointer text-blue-600">Открыть задачу</Link>
                </div>
            </div>
            <div class="flex items-center">
                <DropdownIcon
                    class="transform transition hover:cursor-pointer"
                    :class="{'-rotate-180' : is_open}"
                    @click.prevent="toggle"
                    :x="12"
                    :y="12"
                />
            </div>
        </div>
        <div v-if="is_open" class="flex flex-col gap-4 mt-8">
            <h3 class="text-2xl font-bold">Отчёт</h3>

            <div>
                <span class="text-sm font-medium text-gray-700">Название задачи</span>
                <div>{{ notification.data.task.name }}</div>
            </div>

            <div>
                <span class="text-sm font-medium text-gray-700">Описание</span>
                <div>{{ notification.data.task.description }}</div>
            </div>

            <div>
                <span class="text-sm font-medium text-gray-700">Ответ исполнителя</span>
                <div>{{ notification.data.report_text }}</div>
            </div>

            <div v-if="notification.data.report_file" >
                <span class="text-sm font-medium text-gray-700">Файл</span>
                <div class="flex gap-8">
                    <div class="flex items-center gap-2">
                        <FileIcon :type="notification.data.report_file.extension" :width="32" :height="32" />
                        <span>{{ notification.data.report_file.name }} ({{ notification.data.report_file.size }})</span>
                    </div>
                    <a
                        class="inline-flex items-center rounded-md px-4 py-2 text-md font-semibold text-white bg-blue-600 hover:bg-blue-400"
                        :href="route('dashboard.api.file.download', {file: notification.data.report_file.id})"
                    >
                        Скачать
                    </a>
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <DangerButton @click.prevent="is_open_reject_block = true">Отклонить</DangerButton>
                <PrimaryButton @click.prevent="updateTaskStatus(props.notification.data.status.close)">Подтвердить</PrimaryButton>
            </div>

        </div>
        <div v-if="is_open_reject_block" class="fixed top-0 left-0 w-full h-full z-100 flex flex-col justify-center items-center bg-gray-200/50" :class="{'hidden' : !is_open_reject_block}">
            <div class="flex flex-col gap-4 w-2xl rounded-2xl px-8 py-4 bg-white">

                <div class="flex justify-end">
                    <CrossIcon class="hover:cursor-pointer" @click.prevent="is_open_reject_block = false" :x="24" :y="24" />
                </div>
                <label for="reject_reason" class="text-xl font-bold">Причина отказа</label>

                <textarea
                    id="reject_reason"
                    v-model="reject_reason"
                    class="px-2 py-3 rounded-lg border mt-4 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-600"
                />

                <div>
                    <DangerButton @click.prevent="rejectTask">Отклонить</DangerButton>
                </div>

            </div>
        </div>
    </div>
</template>