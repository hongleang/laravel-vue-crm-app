import auth from '@/router/routes/auth'
import { useAuthStore } from '@/stores/auth'

export function useLoggedInUser() {
  const authStore = useAuthStore()

  return {
    loggedInUserCan: (permission: string) => authStore.ability.can(permission, "*"),
    loggedInUserCannot: (permission: string) => authStore.ability.cannot(permission, "*"),
  }
}
