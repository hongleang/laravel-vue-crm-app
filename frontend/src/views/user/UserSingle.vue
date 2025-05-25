<template>
  <AppLayout title="Users" subheading="Users | User single" container>
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <RouterLink
          :to="{ name: 'user-single-details', params: { userId } }"
          class="nav-link"
          exact-active-class="active"
        >
          Details
        </RouterLink>
      </li>
    </ul>

    <RouterView />
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'

import { useUsersStore } from '@/stores/users'
import { watch } from 'vue'

const props = defineProps<{
  userId: number
}>()

const userStore = useUsersStore()

watch(
  () => props.userId,
  async (userId) => {
    await userStore.getResource({ id: userId })
  },
  { immediate: true },
)
</script>

<style scoped></style>
