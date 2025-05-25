<template>
  <!-- AppSidebar fixed-->
  <div class="app-sidebar">
    <RouterLink to="/dashboard" class="sidebar-logo">
      <SidebarLogo />
    </RouterLink>
    <hr />
    <ul
      class="nav nav-pills flex-column mb-auto gap-2"
      style="overflow: hidden auto; flex-wrap: no"
    >
      <li>
        <RouterLink to="/dashboard" class="sidebar-item nav-link" active-class="active">
          <i class="fas fa-fw fa-chart-bar me-2"></i>
          Dashboard
        </RouterLink>
      </li>
      <li>
        <RouterLink :to="{ name: 'user-list' }" class="sidebar-item nav-link" active-class="active">
          <i class="far fa-fw fa-building me-2"></i>
          Users
        </RouterLink>
      </li>
      <li>
        <RouterLink to="/dashboard" class="sidebar-item nav-link" active-class="active">
          <i class="fas fa-fw fa-address-book me-2"></i>
          Customers
        </RouterLink>
      </li>
      <li>
        <RouterLink to="/dashboard" class="sidebar-item nav-link" active-class="active">
          <i class="fas fa-fw fa-print me-2"></i>
          Reprots
        </RouterLink>
      </li>
    </ul>
    <hr />
    <ul class="nav nav-pills">
      <li class="flex-grow-1">
        <button @click="logout" class="btn d-block sidebar-item nav-link w-100 text-start">
          <i class="fas fa-fw fa-right-from-bracket me-2"></i>
          Logout
        </button>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import SidebarLogo from '../SidebarLogo.vue'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

function logout() {
  try {
    authStore.logout()
    router.push({ name: 'login' })
  } catch (error) {
    console.log(error)
  }
}
</script>

<style scoped></style>
