import http from '@/services/http'
import { defineStore } from 'pinia'
import type { LocationQuery } from 'vue-router'

export type UserListResource = {
  id: number
  name: string
  email: string
  mobile: string
  created_at: string
}

export type UserResource = {
  id: number
  first_name: string
  last_name: string
  email: string
  mobile: string
  created_at: string
}

export type UserFormResource = {
  id: number
  first_name: string
  last_name: string
  email: string
  mobile: string
}

export type Link = {
  url: string | null
  label: string
  active: boolean
}

type Pagination = {
  current_page: number
  from: number
  last_page: number
  links: Link[]
  path: string | null
  per_page: number
  to: number
  total: number
}

export const useUsersStore = defineStore('users', {
  state: () => ({
    collection: [] as UserListResource[] | [],
    resource: null as UserResource | null,
    pagination: null as Pagination | null,
    loading: false,
  }),
  actions: {
    async getCollection({ params }: { params?: LocationQuery } = {}) {
      try {
        this.loading = true
        const response = await http.get('/users', { params })
        this.collection = response.data.data
        this.pagination = response.data.meta
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async getResource({ id }: { id: number; params?: Record<string, any> }) {
      try {
        this.loading = true
        const response = await http.get(`/users/${id}`)
        this.resource = response.data.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async storeResource({ payload }: { payload: UserFormResource }) {
      try {
        this.loading = true
        await http.post(`/users`, payload)
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateResource({ id, payload }: { id: number; payload: UserFormResource }) {
      try {
        this.loading = true
        const response = await http.put(`/users/${id}`, payload)
        this.resource = response.data.data
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },

    async loadNewPage(url: string) {
      try {
        this.loading = true
        const query = new URL(url).search // "?page=2"
        const response = await http.get('/users' + query)
        this.collection = response.data.data
        this.pagination = response.data.meta
      } catch (error) {
        throw error
      } finally {
        this.loading = false
      }
    },
  },
})
