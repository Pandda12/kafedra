<script setup lang="ts">
import SidebarLink from "@/Components/Sidebars/SidebarLink.vue";
import {useNotifications} from "@/composables/useNotifications.js";
import PaperIcon from "@/Components/Icons/PaperIcon.vue";
import SpeakerIcon from "@/Components/Icons/SpeakerIcon.vue";
import TasksIcon from "@/Components/Icons/TasksIcon.vue";
import NotificationsIcon from "@/Components/Icons/NotificationsIcon.vue";
import HomeIcon from "@/Components/Icons/HomeIcon.vue";
import RatingIcon from "@/Components/Icons/RatingIcon.vue";

const { unreadCount } = useNotifications()

</script>

<template>
    <aside class="w-96">
        <div class="flex flex-col gap-y-7 px-6 py-10">
            <div>
                <SidebarLink
                    routeName="home"
                    :icon="HomeIcon"
                    activePattern="home"
                >
                    Главная
                </SidebarLink>
            </div>

            <div class="flex flex-col gap-y-2">
                <SidebarLink
                    routeName="employee.notification.index"
                    :icon="NotificationsIcon"
                    activePattern="employee.notification.index"
                >
                    Уведомления
                    <span v-if="unreadCount > 0" class="px-2 rounded-md border border-blue-200 bg-blue-100/80 text-blue-600">
                        {{ unreadCount }}
                    </span>
                </SidebarLink>

                <SidebarLink
                    routeName="employee.tasks.index"
                    :icon="TasksIcon"
                    activePattern="employee.tasks.index"
                >
                    Задачи
                </SidebarLink>

                <SidebarLink
                    routeName="employee.publications.index"
                    :icon="PaperIcon"
                    activePattern="employee.publications.index"
                >
                    Публикации
                </SidebarLink>

                <SidebarLink
                    routeName="employee.events.index"
                    :icon="SpeakerIcon"
                    activePattern="employee.events.index"
                >
                    Мероприятия
                </SidebarLink>

                <SidebarLink
                    routeName="employee.rating.index"
                    :icon="RatingIcon"
                    activePattern="employee.rating.index"
                >
                    Рейтинг
                </SidebarLink>
            </div>
        </div>
    </aside>
</template>