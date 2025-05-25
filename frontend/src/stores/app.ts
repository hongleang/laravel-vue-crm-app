import { defineStore } from 'pinia'

export const useCounterStore = defineStore('app', {
  state: () => ({
    sidebarOpen: true,
  }),
  actions: {
    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen
    },
  },
})
