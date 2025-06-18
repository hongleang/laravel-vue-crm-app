<template>
  <!-- Filters -->
  <div class="card border-0 bg-body-light shadow-subtle">
    <div class="card-body">
      <div class="row gx-3 align-items-stretch">
        <div class="col">
          <label class="form-label">Search</label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fas fa-fw fa-search"></i>
            </span>
            <input
              class="form-control"
              v-model="filters.search"
              type="text"
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
</template>

<script setup lang="ts">
import { debounce, isEqual } from 'lodash'
import { reactive, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCompaniesStore } from '@/stores/companies'

const router = useRouter()
const route = useRoute()
const companiesStore = useCompaniesStore()

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

type CompanyListFilter = {
  search: string
  sortBy: string
}

const filters = reactive<CompanyListFilter>({
  search: '',
  sortBy: sortByOptions[0].value,
})

const updateQuery = debounce((newFilter: CompanyListFilter) => {
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
      await companiesStore.getCollection({
        params: newValue,
      })
    }
  },
)
</script>

<style scoped></style>
