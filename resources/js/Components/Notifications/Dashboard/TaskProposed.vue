<script setup lang="ts">
import {ref} from "vue";
import {useAdminNotifications} from "@/composables/useAdminNotifications.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownIcon from "@/Components/Icons/DropdownIcon.vue";
import DangerButton from "@/Components/DangerButton.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    notification: {
        type: Object,
        required: true
    }
})

const is_open = ref(false)

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
            }
        )

        if (data?.status) {
            await markAsRead(id, status)
            is_open.value = false
        } else {
            throw new Error('Update task status failed')
        }
    } catch (e) {
        console.error(e)
    }
}

</script>

<template>
    <div
        class="px-8 py-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
    >
        <div class="flex justify-between gap-x-8">
            <div class="flex flex-col gap-3">
                <div>
                    Пользователь {{ notification.data.user.name }} добавил задачу
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
            <div class="grid gap-4" :class="{ 'grid-cols-2' : notification.data.task.reject_reason }">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-700">Название</span>
                        <div>{{ notification.data.task.name }}</div>
                    </div>
                    <div class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-700">Описание</span>
                        <div>{{ notification.data.task.description }}</div>
                    </div>
                    <div class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-700">Сроки</span>
                        <div>с {{ notification.data.task.starts_on }} по {{ notification.data.task.ends_on }}</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <DangerButton @click.prevent="updateTaskStatus(props.notification.data.status.delete)">Отклонить</DangerButton>
                <PrimaryButton @click.prevent="updateTaskStatus(props.notification.data.status.assigned)">Подтвердить</PrimaryButton>
            </div>

        </div>
    </div>
</template>