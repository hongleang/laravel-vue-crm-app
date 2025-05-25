<template>
  <AppLayout title="Users" subheading="Users | List" container>
    <template #page-actions>
      <button
        v-if="loggedInUserCan('write user')"
        @click="router.push({ name: 'user-create' })"
        class="btn btn-dark"
      >
        <i class="fas fa-fw fa-plus"></i>
        New User
      </button>
    </template>
    <div class="mt-4">
      <!-- Filters -->
      <div class="card border-0 bg-body-light shadow-subtle">
        <div class="card-body">
          <div class="row gx-3 align-items-stretch">
            <div class="col">
              <label class="form-label">Search</label>
              <div class="input-group">
                <div class="input-group-text">
                  <i class="fas fa-fw fa-search"></i>
                </div>
                <input
                  v-model="filters.search"
                  type="text"
                  class="form-control"
                  placeholder="Search..."
                />
              </div>
            </div>
            <div class="col-12 col-lg-3">
              <label class="form-label">Sort by</label>
              <select v-model="filters.sortBy" class="form-select">
                <option v-for="option in sortByOptions" :value="option.value">
                  {{ option.label }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Filters -->

      <!-- Table -->
      <div class="card shadow-subtle mt-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-select table-round align-middle mb-0">
              <thead>
                <tr>
                  <th style="min-width: 200px">Name</th>
                  <th style="min-width: 200px">Email</th>
                  <th style="min-width: 200px">Phone</th>
                  <th style="min-width: 200px">Created At</th>
                  <th style="width: 0"></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  @click="navigate(resource.id)"
                  v-for="resource in collection"
                  :key="resource.id"
                >
                  <td style="min-width: 200px">{{ resource.name }}</td>
                  <td style="min-width: 200px">{{ resource.email }}</td>
                  <td style="min-width: 200px">{{ resource.mobile }}</td>
                  <td style="min-width: 200px">{{ resource.created_at }}</td>
                  <td style="width: 0">
                    <!-- <div class="dropdown">
                      <button
                        class="btn btn-link text-secondary"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                      >
                        <i class="fas fa-fw fa-ellipsis-v"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                          <span class="dropdown-item">
                            
                          </span>
                          <RouterLink class="dropdown-item" to="/dashboard">View</RouterLink>
                        </li>
                        <li>
                          <RouterLink class="dropdown-item" to="/dashboard">Edit</RouterLink>
                        </li>
                        <li>
                          <RouterLink class="dropdown-item" to="/dashboard">Delete</RouterLink>
                        </li>
                      </ul>
                    </div> -->
                  </td>
                </tr>
                <tr v-if="loading">
                  <td style="width: 100%; text-align: center">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="d-flex justify-content-center py-4" v-if="collection.length === 0">
              <span class="text-muted"> No results was found </span>
            </div>
          </div>

          <div v-if="pagination" class="row align-items-center mt-4 sticky-bottom">
            <div class="col">
              <!-- Text -->
              <p class="text-body-secondary mb-0">
                {{ pagination.from }} – {{ pagination.to }} ({{ pagination.total }}
                total)
              </p>
            </div>
            <div class="col-auto">
              <!-- Pagination -->
              <nav aria-label="Page navigation example">
                <ul class="pagination mb-0">
                  <li
                    v-if="pagination.links"
                    v-for="item in pagination.links"
                    :key="id"
                    class="page-item"
                  >
                    <span
                      v-if="showNavigation(pagination.links, item)"
                      class="page-link"
                      :class="{ active: item.active }"
                      @click="loadPage(item.url)"
                    >
                      {{ paginationLabel(item.label) }}
                    </span>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Table -->
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { useUsersStore, type Link } from '@/stores/users'
import { storeToRefs } from 'pinia'
import { onMounted, reactive, useId, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import debounce from 'lodash.debounce'
import { useLoggedInUser } from '@/composables/useLoggedInUser'
import { isEqual } from 'lodash'

const { loggedInUserCan, loggedInUserCannot } = useLoggedInUser()

const router = useRouter()
const route = useRoute()
const id = useId()
const userStore = useUsersStore()
const { collection, pagination, loading } = storeToRefs(userStore)

const navigate = (userId: number) => {
  router.push({ name: 'user-single', params: { userId } })
}

const sortByOptions = [
  {
    label: 'Ascending',
    value: 'asc',
  },
  {
    label: 'Descending',
    value: 'desc',
  },
]

type UserListFilter = {
  search: string
  sortBy: string
}

const filters = reactive<UserListFilter>({
  search: '',
  sortBy: sortByOptions[0].value,
})

const updateQuery = debounce((newFilter: UserListFilter) => {
  router.replace({
    query: {
      ...route.query,
      search: newFilter.search || '',
      sortBy: newFilter.sortBy || '',
    },
  })
}, 400)

watch(filters, (filterValue) => {
  updateQuery(filterValue)
})

watch(
  () => route.query,
  async (newValue, oldValue) => {
    if (!isEqual(newValue, oldValue)) {
      await userStore.getCollection({
        params: newValue
      })
    }
  },
)

onMounted(async () => {
  await userStore.getCollection()
})

const paginationLabel = (label: string): string => {
  const nextBtn = label.toLowerCase().includes('next')
  const previousBtn = label.toLowerCase().includes('previous')
  if (!nextBtn && !previousBtn) {
    return label
  }

  return nextBtn ? '»' : '«'
}
const showNavigation = (links: Link[], item: Link): boolean => {
  // Should not show if there's only one nav button
  return links.filter((link) => link.url).length > 1 && item.url !== null
}

const loadPage = async (url: string | null) => {
  if (url) {
    await userStore.loadNewPage(url)
  }
}
</script>

<style scoped></style>
