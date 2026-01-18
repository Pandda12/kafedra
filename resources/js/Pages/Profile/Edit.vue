<script setup lang="ts">
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import EmployeeLayout from '@/Layouts/EmployeeLayout.vue'

defineProps({
    mustVerifyEmail: { type: Boolean, default: false },
    status: { type: String, default: '' },
})

const page = usePage()

const isAdmin = computed(() => {
    return page.props.auth?.user?.role?.value === 'admin'
})

const Layout = computed(() => (isAdmin.value ? DashboardLayout : EmployeeLayout))
</script>

<template>
    <Head title="Профиль" />

    <component :is="Layout" :is_full_sidebar="false">
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-xl p-6 shadow-sm ring-1 bg-white ring-gray-950/5">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="rounded-xl p-6 shadow-sm ring-1 bg-white ring-gray-950/5">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

            </div>
        </div>
    </component>
</template>
