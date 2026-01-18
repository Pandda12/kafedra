<script setup lang="ts">
import { computed, type Component } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = withDefaults(defineProps<{
    routeName: string
    params?: Record<string, any>
    icon: Component
    activePattern?: string | string[]
}>(), {
    params: () => ({}),
    activePattern: ''
})

const isActive = computed(() => {
    if (props.activePattern) return route().current(props.activePattern as any)
    return route().current(props.routeName)
})

const href = computed(() => route(props.routeName, props.params))
</script>

<template>
    <Link
        :href="href"
        class="flex items-center gap-3 font-medium rounded-lg p-2 hover:bg-gray-100"
        :class="[isActive ? 'bg-gray-100' : '']"
        prefetch
    >
        <component
            :is="icon"
            :class="isActive ? 'text-blue-600' : 'text-gray-400'"
            class="checked"
        />
        <span class="flex justify-between w-full" :class="[isActive ? 'text-blue-600' : 'text-gray-700']">
            <slot />
        </span>
    </Link>
</template>
