import http from '@/services/http'
import { defineStore } from 'pinia'
import { ability, updateAbilityFor } from '@/utils/abilities'
import type { LoggedInUser, Credential } from '@/types/model'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as LoggedInUser | null,
    ability,
  }),
  actions: {
    async getUser() {
      if (this.user) return
      try {
        const response = await http.get('/user')
        this.user = response.data.data
        if (this.user) {
          updateAbilityFor(this.user)
        }
      } catch {}
    },

    async login(credential: Credential) {
      try {
        http.get('/sanctum/csrf-cookie')
        await http.post('/login', {
          email: credential.email,
          password: credential.password,
          remember: credential.remember,
        })
      } catch (error) {
        throw error
      }
    },

    async logout() {
      try {
        await http.post('/logout')
        this.user = null
      } catch (error) {}
    },
  },
})
