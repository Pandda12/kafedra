import { ref, computed } from 'vue'
import axios from 'axios'

const notifications = ref([])
const isLoaded = ref(false)
const isLoading = ref(false)

export function useAdminNotifications() {
    const unreadCount = computed(() => notifications.value.length)

    const fetchNotifications = async () => {
        if (isLoading.value) return

        isLoading.value = true
        try {
            const { data } = await axios.get(route('dashboard.api.notification.index'))
            notifications.value = data.data ?? []
            isLoaded.value = true
        } catch (e) {
            console.error('Failed to load notifications', e)
        } finally {
            isLoading.value = false
        }
    }

    const markAsRead = async (id, status) => {
        try {
            await axios.patch(route('dashboard.api.notification.update', id), {
                id: id,
                status: status
            })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });


            notifications.value = notifications.value.filter(n => n.id !== id)
        } catch (e) {
            console.error('Failed to mark notification as read', e)
        }
    }

    return {
        notifications,
        unreadCount,
        isLoaded,
        isLoading,
        fetchNotifications,
        markAsRead,
    }
}
