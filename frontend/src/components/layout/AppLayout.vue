<template>
  <div class="app-layout bg-body-tertiary">
    <AppSidebar />
    <div class="app-header">
      <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid">
          <div class="d-flex align-items-stretch w-100">
            <div class="d-flex align-items-center gap-3 ms-auto">
              <div class="dropdown">
                <button
                  class="btn btn-link text-secondary"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="far fa-fw fa-bell"></i>
                </button>
                <!-- Notifcation -->
                <ul
                  class="dropdown-menu dropdown-menu-end text-small shadow"
                  style="--bs-dropdown-min-width: 20rem"
                >
                  <ul class="list-group">
                    <li><h6 class="dropdown-header">Notifications</h6></li>

                    <li class="list-group-item list-group-item-action d-flex gap-3 py-3">
                      <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                          <h6 class="mb-0">List group item heading</h6>
                          <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                        </div>
                        <small class="opacity-50 text-nowrap">now</small>
                      </div>
                    </li>
                    <li class="list-group-item list-group-item-action d-flex gap-3 py-3">
                      <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                          <h6 class="mb-0">Another title here</h6>
                          <p class="mb-0 opacity-75">
                            Some placeholder content in a paragraph that goes a little longer so it
                            wraps to a new line.
                          </p>
                        </div>
                        <small class="opacity-50 text-nowrap">3d</small>
                      </div>
                    </li>
                  </ul>
                </ul>
                <!-- End of Notifcation -->
              </div>

              <!-- User ACC -->
              <div v-if="user" class="dropdown">
                <button
                  class="btn btn-icon text-decoration-none"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  {{ getUserInitials(user.name) }}
                </button>
                <ul
                  class="dropdown-menu dropdown-menu-end p-3 gap-3"
                  style="--bs-dropdown-min-width: 15rem"
                >
                  <li class="px-2 mb-4">
                    <h5 class="fs-6 mb-2">{{ user.name }}</h5>
                    <p class="text-muted">{{ user.email }}</p>
                  </li>
                  <li class="mb-3">
                    <RouterLink to="/dashboard" class="dropdown-item small px-1">
                      <i class="far fa-fw fa-user me-1"></i>
                      Profile
                    </RouterLink>
                  </li>
                  <li class="mb-3">
                    <RouterLink to="/dashboard" class="dropdown-item small px-1">
                      <i class="fas fa-fw fa-cog me-1"></i>
                      Setting
                    </RouterLink>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </div>
    <!-- PageCotent -->
    <div class="app-main flex-column">
      <div :class="{ 'container-fluid': fullPage, container }">
        <div class="row align-items-center mb-5">
          <div class="col">
            <h1 class="page-title">{{ title }}</h1>
            <p v-if="subheading" class="page-title-subheading">{{ subheading }}</p>
          </div>
          <div class="col-12 col-lg-auto">
            <slot name="page-actions" />
          </div>
        </div>

        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import AppSidebar from './AppSidebar.vue'
import { useAuthStore } from '@/stores/auth'

defineProps<{
  title: string
  subheading?: string
  fullPage?: boolean
  container?: boolean
}>()

const { user } = storeToRefs(useAuthStore())

const getUserInitials = (name: string): string => {
  const nameArr = name.split(' ')

  if (nameArr.length > 1) {
    return nameArr[0][0].toUpperCase() + nameArr[0][1].toUpperCase()
  } else {
    return nameArr[0][0].toUpperCase()
  }
}
</script>

<style lang="scss" scoped></style>
