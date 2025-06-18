<template>
  <Teleport to="#page-action-btn">
    <button class="btn btn-dark">
      <i class="fas fa-fw fa-plus me-1"></i>
      Add File
    </button>
  </Teleport>
  <div class="card">
    <div class="card-body">
      <h3 class="fs-5 mb-1">{{ company?.name }}</h3>
      <p class="text-body-secondary">List of files associated with the company.</p>

      <hr class="my-5" />

      <div v-if="loading">
        <div class="text-center text-muted">Loading...</div>
      </div>

      <div v-if="company?.files?.length">
        <ul class="list-group">
          <li v-for="file in company.files" :key="file.id" class="list-group-item">
            <div class="row">
              <div class="col">
                <h4 class="fw-semibold">{{ file.name }}</h4>
                <p class="text-muted">{{ file.bytes }}</p>
              </div>
              <div class="col-lg-auto">
                
              </div>
            </div>
          </li>
        </ul>
      </div>

      <div v-if="!company?.files?.length" class="text-center">No record found</div>
    </div>
  </div>

  <div v-if="company" class="row align-items-center gy-3 mt-4">
    <div class="col">
      <RouterLink :to="{ name: 'company-list' }" class="btn btn-outline-secondary">
        Cancel
      </RouterLink>
    </div>
    <!-- <div class="col-auto">
      <button
        v-if="loggedInUserCan('write company')"
        @click="save"
        class="btn btn-dark"
        :disabled="isSubmitting"
      >
        Save Changes
      </button>
    </div> -->
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import InputText from '../ui/InputText.vue'
import { handleValidationError } from '@/utils/helpers'
import { watchEffect } from 'vue'
import { useToast } from 'vue-toast-notification'
import { useLoggedInUser } from '@/composables/useLoggedInUser'
import { useCompaniesStore, type CompanyFormResource } from '@/stores/companies'

const companiesStore = useCompaniesStore()
const { resource: company, loading } = storeToRefs(companiesStore)

const { loggedInUserCan } = useLoggedInUser()
</script>

<style lang="scss" scoped></style>
