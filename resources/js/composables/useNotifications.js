import { ref, computed } from 'vue'
import axios from 'axios'

const notifications = ref([])
const isLoaded = ref(false)
const isLoading = ref(false)

export function useNotifications() {
    const unreadCount = computed(() => notifications.value.length)

    const fetchNotifications = async () => {
        if (isLoading.value) return

        isLoading.value = true
        try {
            const { data } = await axios.get(route('employee.api.notification.index'))
            notifications.value = data.data ?? []
            isLoaded.value = true
        } catch (e) {
            console.error('Failed to load notifications', e)
        } finally {
            isLoading.value = false
        }
    }

    const markAsRead = async (id, task_id, status, type) => {
        try {
            await axios.patch(route('employee.api.notification.update', id), {
                id: id,
                task_id: task_id,
                status: status,
                type: type
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
