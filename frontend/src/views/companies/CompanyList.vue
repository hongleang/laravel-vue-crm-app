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
          <div class="table-responsive">
          </div>
        </div>
      </div>
      <!-- End of Table -->
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { storeToRefs } from 'pinia'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLoggedInUser } from '@/composables/useLoggedInUser'
import FilterCard from '@/components/companies/FilterCard.vue'
import { useCompaniesStore, type CompanyListResource } from '@/stores/companies'

const { loggedInUserCan } = useLoggedInUser()

const router = useRouter()
const companiesStore = useCompaniesStore()
const { collection, loading } = storeToRefs(companiesStore)

const navigateToDetail = (resource: CompanyListResource) => {
  router.push({ name: 'company-single', params: { companyId: resource.id } })
}

onMounted(async () => {
  await companiesStore.getCollection()
})
</script>

<style scoped></style>
