import { useAuthStore } from '@/stores/auth'

export function useLoggedInUser() {
  const authStore = useAuthStore()

  function loggedInUserCan(permission: string): boolean {
    // * permission should two words in a form of 'action subject' in lowercase eg 'read user', 'write user'
    const permissionArr = permission.split(' ')
    if (permissionArr.length !== 2) return false

    const [action, subject] = permissionArr

    const subjectCapitalize = subject[0].toUpperCase() + subject.slice(1)

    return authStore.ability.can(action, subjectCapitalize)
  }

  function loggedInUserCannot(permission: string): boolean {
    // * permission should two words in a form of 'action subject' in lowercase eg 'read user', 'write user'
    const permissionArr = permission.split(' ')
    if (permissionArr.length !== 2) return false

    const [action, subject] = permissionArr

    const subjectCapitalize = subject[0].toUpperCase() + subject.slice(1)

    return authStore.ability.cannot(action, subjectCapitalize)
  }

  return {
    loggedInUserCan,
    loggedInUserCannot,
  }
}
