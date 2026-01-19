<script setup lang="ts">
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
import {useNotifications} from "@/composables/useNotifications.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownIcon from "@/Components/Icons/DropdownIcon.vue";

const props = defineProps({
    notification: {
        type: Object,
        required: true
    }
})

const is_open = ref(false)
const { markAsRead } = useNotifications()

const toggle = () => { is_open.value = !is_open.value }

const updateTaskStatus = async () => {
    const id = props.notification.id

    try {
        await markAsRead(id, props.notification.data.task.task_user_id, props.notification.data.action, 'update')
        is_open.value = false
    } catch (e) {
        console.error(e)
    }
}

</script>

<template>
    <div
        class="px-8 py-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5"
    >
        <div class="flex justify-between gap-x-8 hover:cursor-pointer" @click.prevent="toggle">
            <div>
                {{ notification.data.text }}
                <Link
                    :href="notification.data.open_url" prefetch
                    class="underline text-blue-600"
                >
                    {{ notification.data.task.name }}
                </Link>
            </div>
            <div class="flex items-center">
                <DropdownIcon :x="12" :y="12" class="transform transition" :class="{'-rotate-180' : is_open}" />
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
                    <div class="flex flex-col">
                        <span class="block text-sm font-medium text-gray-700">Рейтинг</span>
                        <div>{{ notification.data.task.rating }}</div>
                    </div>
                </div>
                <div v-if="notification.data.task.reject_reason" class="flex flex-col">
                    <span class="block text-sm font-medium text-gray-700">Причина отказа</span>
                    <div>{{ notification.data.task.reject_reason }}</div>
                </div>
            </div>
            <div class="flex justify-end">
                <PrimaryButton v-if="props.notification.data.is_actionable" @click.prevent="updateTaskStatus">
                    {{ props.notification.data.btn_text }}
                </PrimaryButton>
                <PrimaryButton
                    v-else
                    @click.prevent="markAsRead(props.notification.id, props.notification.data.task.task_user_id, props.notification.data.action, 'read')"
                >
                    Очистить
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>