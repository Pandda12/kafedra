<script setup lang="ts">
import {onMounted} from "vue";
import {Link} from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import EmployeeSidebar from "@/Components/Sidebars/EmployeeSidebar.vue";
import {useNotifications} from "@/composables/useNotifications.js";

const { fetchNotifications, isLoaded } = useNotifications()

onMounted(() => {
    if ( !isLoaded.value ) {
        fetchNotifications()
    }

    setInterval(fetchNotifications, 10000)
})

</script>

<template>
    <div class="fixed start-0 w-full flex justify-between items-center px-8 py-3 border border-gray-200 shadow-sm z-50 bg-white">
        <Link
            class="flex items-center gap-x-5 font-bold uppercase text-gray-700"
            :href="route('home')"
        >
            <ApplicationLogo class="h-12 w-12 fill-current" />
            Кафедра
        </Link>
        <div>
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <!-- Settings Dropdown -->
                <div class="relative ms-3">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                >
                                    {{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.second_name }}

                                    <svg
                                        class="-me-0.5 ms-2 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink
                                :href="route('profile.edit')"
                            >
                                Профиль
                            </DropdownLink>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Выйти
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </div>
    <div class="flex w-full mt-20">
        <EmployeeSidebar />
        <div class="mx-auto h-full w-full px-4 md:px-6 lg:px-8 max-w-7xl">
            <div class="flex flex-col gap-y-7 px-6 py-10">
                <slot/>
            </div>
        </div>
    </div>
</template>
