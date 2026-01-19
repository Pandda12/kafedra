<script setup lang="ts">
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";
import SidebarLink from "@/Components/Sidebars/SidebarLink.vue";
import SettingsIcon from "@/Components/Icons/SettingsIcon.vue";
import {useAdminNotifications} from "@/composables/useAdminNotifications.js";
import PaperIcon from "@/Components/Icons/PaperIcon.vue";
import TasksIcon from "@/Components/Icons/TasksIcon.vue";
import NotificationsIcon from "@/Components/Icons/NotificationsIcon.vue";
import SpeakerIcon from "@/Components/Icons/SpeakerIcon.vue";
import CurrentYearIcon from "@/Components/Icons/CurrentYearIcon.vue";
import HomeIcon from "@/Components/Icons/HomeIcon.vue";
import YearIcon from "@/Components/Icons/YearIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import FileIcon from "@/Components/Icons/FileIcon.vue";
import RatingIcon from "@/Components/Icons/RatingIcon.vue";

const { unreadCount } = useAdminNotifications()

const page = usePage()
const yearSlug = computed(() => page.props.current_year?.slug || '')
</script>

<template>
    <aside class="w-96">
        <div class="flex flex-col gap-y-7 px-6 py-10">
            <div>
                <SidebarLink
                    routeName="dashboard"
                    :icon="HomeIcon"
                    activePattern="dashboard"
                >
                    Админ панель
                </SidebarLink>
            </div>
            <div class="flex flex-col gap-y-2">
                <SidebarLink
                    routeName="dashboard.notification.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="NotificationsIcon"
                    activePattern="dashboard.notification.index"
                >
                    Уведомления
                    <span v-if="unreadCount > 0" class="px-2 rounded-md border border-blue-200 bg-blue-100/80 text-blue-600">
                        {{ unreadCount }}
                    </span>
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.academic_year.index"
                    :icon="YearIcon"
                >
                    Все учебные годы
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.user.index"
                    :icon="UserIcon"
                    activePattern="dashboard.user.index"
                >
                    Все пользователи
                </SidebarLink>
            </div>

            <div class="flex flex-col gap-y-2">
                <SidebarLink
                    routeName="dashboard.academic_year.show"
                    :params="{ academicYear: yearSlug }"
                    :icon="CurrentYearIcon"
                    activePattern="dashboard.academic_year.show"
                >
                    Учебный год
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.task.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="TasksIcon"
                    activePattern="dashboard.task.index"
                >
                    Задачи
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.task.report"
                    :params="{ academicYear: yearSlug }"
                    :icon="FileIcon"
                    activePattern="dashboard.task.report"
                >
                    Отчёты
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.publication.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="PaperIcon"
                    activePattern="dashboard.publication.index"
                >
                    Публикации
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.event.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="SpeakerIcon"
                    activePattern="dashboard.event.index"
                >
                    Мероприятия
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.rating.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="RatingIcon"
                    activePattern="dashboard.rating.index"
                >
                    Рейтинг
                </SidebarLink>
                <SidebarLink
                    routeName="dashboard.academic_year_user.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="UserIcon"
                    activePattern="dashboard.academic_year_user.index"
                >
                    Пользователи
                </SidebarLink>
            </div>
            <div class="">
                <SidebarLink
                    routeName="dashboard.option.index"
                    :params="{ academicYear: yearSlug }"
                    :icon="SettingsIcon"
                    activePattern="dashboard.option.index"
                >
                    Найстройки
                </SidebarLink>
            </div>
        </div>
    </aside>
</template>