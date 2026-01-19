<script setup lang="ts">
import YearIcon from "@/Components/Icons/YearIcon.vue";
import SidebarLink from "@/Components/Sidebars/SidebarLink.vue";
import HomeIcon from "@/Components/Icons/HomeIcon.vue";
import UserIcon from "@/Components/Icons/UserIcon.vue";
import NotificationsIcon from "@/Components/Icons/NotificationsIcon.vue";
import {useAdminNotifications} from "@/composables/useAdminNotifications.js";

const { unreadCount } = useAdminNotifications()
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
        </div>
    </aside>
</template>