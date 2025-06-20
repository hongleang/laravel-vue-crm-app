<template>
  <AppLayout title="Companies" subheading="Companies | List" container>
    <template #page-actions>
      <button
        v-if="loggedInUserCan('write company')"
        @click="router.push({ name: 'company-create' })"
        class="btn btn-dark"
      >
        <i class="fas fa-fw fa-plus"></i>
        New Company
      </button>
    </template>
    <div class="mt-4">
      <FilterCard />

      <!-- Table -->
      <div class="card shadow-subtle mt-4">
        <div class="card-body">
          <BaseTable
            :data="collection"
            :columns="columns"
            :pagination="pagination"
            @load-new-page="loadNewPage"
          />
        </div>
      </div>
      <!-- End of Table -->
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { storeToRefs } from 'pinia'
import { h, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLoggedInUser } from '@/composables/useLoggedInUser'
import FilterCard from '@/components/companies/FilterCard.vue'
import { useCompaniesStore, type CompanyListResource } from '@/stores/companies'
import BaseTable from '@/components/ui/table/BaseTable.vue'
import { type ColumnDef } from '@tanstack/vue-table'

const { loggedInUserCan } = useLoggedInUser()

const router = useRouter()
const companiesStore = useCompaniesStore()
const { collection, pagination } = storeToRefs(companiesStore)

const navigateToDetail = (resource: CompanyListResource) => {
  router.push({ name: 'company-single', params: { companyId: resource.id } })
}

const columns: ColumnDef<CompanyListResource>[] = [
  {
    id: 'name',
    header: 'Name',
    size: 200,
    accessorFn: (row) => row.name,
  },
  {
    id: 'industry',
    header: 'Industry',
    accessorFn: (row) => row.industry,
    size: 300
  },
  {
    id: 'email',
    header: 'Email',
    size: 200,
    accessorFn: (row) => row.email,
  },
  {
    id: 'phone',
    header: 'Phone',
    size: 200,
    accessorFn: (row) => row.phone,
  },
  {
    id: 'address',
    header: 'Address',
    size: 400,
    accessorFn: (row) => row.address,
  },
]

const loadNewPage = async (page: string) => {
  await companiesStore.loadNewPage(page)
}

onMounted(async () => {
  await companiesStore.getCollection()
})
</script>

<style scoped></style>
