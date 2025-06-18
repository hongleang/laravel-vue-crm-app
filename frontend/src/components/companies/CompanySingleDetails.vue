<template>
  <div class="card">
    <div class="card-body">
      <h3 class="fs-5 mb-1">{{ company?.name }}</h3>
      <p class="text-body-secondary">General information about the organisation.</p>

      <hr class="my-5" />

      <div v-if="loading">
        <div class="text-center text-muted">Loading...</div>
      </div>

      <div v-if="company" class="vstack gap-3">
        <!-- <InputText form-group name="name" type="text" label="Name *" />

        <InputText form-group name="industry" type="text" label="Industry *" />

        <InputText form-group name="email" type="text" label="Email Address *" />

        <InputText form-group name="phone" type="text" label="Mobile Number *" />

        <InputText form-group name="address" type="text" label="Address *" /> -->
      </div>

      <div v-else class="text-center">No record found</div>
    </div>
  </div>

  <div v-if="company" class="row align-items-center gy-3 mt-4">
    <div class="col">
      <RouterLink :to="{ name: 'company-list' }" class="btn btn-outline-secondary">
        Cancel
      </RouterLink>
    </div>
    <div class="col-auto">
      <button
        v-if="loggedInUserCan('write company')"
        @click="save"
        class="btn btn-dark"
        :disabled="isSubmitting"
      >
        Save Changes
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
// import InputText from '../ui/InputText.vue'
import { handleValidationError } from '@/utils/helpers'
import { watchEffect } from 'vue'
import { useToast } from 'vue-toast-notification'
import { useLoggedInUser } from '@/composables/useLoggedInUser'
import { useCompaniesStore, type CompanyFormResource } from '@/stores/companies'

const companiesStore = useCompaniesStore()
const { resource: company, loading } = storeToRefs(companiesStore)

const { loggedInUserCan } = useLoggedInUser()

const toast = useToast()

const { isSubmitting, handleSubmit, setValues } = useForm<CompanyFormResource>({
  validationSchema: {
    name: yup.string().required().label('Name'),
    industry: yup.string().required().label('Industry'),
    email: yup.string().email().required().label('Email address'),
    phone: yup.number().required().label('Phone'),
    address: yup.string().required().label('Address'),
  },
})

const save = handleSubmit(async (values, action) => {
  if (!company.value) return

  try {
    await companiesStore.updateResource({
      id: company.value.id,
      payload: values,
    })
    toast.success('Company has been successfully saved!', {
      position: 'top-right',
    })
  } catch (error) {
    handleValidationError(error, action)
  }
})

watchEffect(() => {
  setValues({
    name: company.value?.name,
    industry: company.value?.industry,
    email: company.value?.email,
    phone: company.value?.phone,
    address: company.value?.address,
  })
})
</script>

<style lang="scss" scoped></style>
