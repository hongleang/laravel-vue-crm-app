import http from '@/services/http'
import type { LocationQuery } from 'vue-router'
import type { Pagination } from '@/types/model'
import { defineStore } from 'pinia'

export function createCrudStore<TList, TResource>(
  storeName: string,
  options: {
    baseUrl?: string
    state?: () => Record<string, any>
    actions?: Record<string, (...args: any[]) => any>
    getters?: Record<string, (...args: any[]) => any>
  } = {},
) {
  const url = options.baseUrl ? options.baseUrl : `/${storeName}`

  return defineStore('companies', {
    state: () => ({
      collection: [] as TList[] | [],
      resource: null as TResource | null,
      pagination: null as Pagination | null,
      loading: false,
      ...options.state,
    }),
    getters: {
      getUrl() {
        return url
      },
      ...options.getters,
    },
    actions: {
      async getCollection({ params }: { params?: LocationQuery } = {}) {
        try {
          this.loading = true
          const response = await http.get(url, { params })
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
          const response = await http.get(`${url}/${id}`)
          this.resource = response.data.data
        } catch (error) {
          throw error
        } finally {
          this.loading = false
        }
      },

      async storeResource({ payload }: { payload: TResource }) {
        try {
          this.loading = true
          const response = await http.post(url, payload)

          this.resource = response.data.data
        } catch (error) {
          throw error
        } finally {
          this.loading = false
        }
      },

      async updateResource({ id, payload }: { id: number; payload: TResource }) {
        try {
          this.loading = true
          const response = await http.put(`${url}/${id}`, payload)
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
          const response = await http.get(url + query)
          this.collection = response.data.data
          this.pagination = response.data.meta
        } catch (error) {
          throw error
        } finally {
          this.loading = false
        }
      },

      resetStore() {
        this.collection = []
        this.resource = null
        this.pagination = null
      },
    },
  })
}
