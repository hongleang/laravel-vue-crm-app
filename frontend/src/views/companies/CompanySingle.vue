<template>
  <AppLayout title="Companies" subheading="Companies | Company single" container>
    <template #page-actions>
      <div id="page-action-btn"></div>
    </template>
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <RouterLink
          :to="{ name: 'company-single-details', params: { companyId } }"
          class="nav-link"
          exact-active-class="active"
        >
          Details
        </RouterLink>
      </li>
      <li class="nav-item">
        <RouterLink
          :to="{ name: 'company-single-files', params: { companyId } }"
          class="nav-link"
          exact-active-class="active"
        >
          Files
        </RouterLink>
      </li>
    </ul>

    <RouterView />
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import { useCompaniesStore } from '@/stores/companies'
import { watch } from 'vue'

const props = defineProps<{
  companyId: number
}>()

const companiesStore = useCompaniesStore()

watch(
  () => props.companyId,
  async (companyId) => {
    await companiesStore.getResource({ id: companyId })
  },
  { immediate: true },
)
</script>

<style scoped></style>
