<template>
  <AppLayout title="Companies" subheading="Companies | Company single" container>
    <div class="card">
      <div class="card-body">
        <div class="vstack gap-3">
          <!-- <InputText form-group name="name" type="text" label="Name *" />

          <InputText form-group name="industry" type="text" label="Industry *" />

          <InputText form-group name="email" type="text" label="Email Address *" />

          <InputText form-group name="phone" type="text" label="Mobile Number *" />

          <InputText form-group name="address" type="text" label="Address *" /> -->
        </div>
      </div>
    </div>

    <div class="row align-items-center gy-3 mt-4">
      <div class="col">
        <RouterLink :to="{ name: 'company-list' }" class="btn btn-outline-secondary">
          Cancel
        </RouterLink>
      </div>
      <div class="col-auto">
        <button @click="save" class="btn btn-dark" :disabled="isSubmitting">Save Changes</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
// import InputText from '@/components/ui/InputText.vue'
import { useCompaniesStore, type CompanyFormResource } from '@/stores/companies'
import { handleValidationError } from '@/utils/helpers'
import { isAxiosError } from 'axios'
import { storeToRefs } from 'pinia'
import { useForm } from 'vee-validate'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toast-notification'
import * as yup from 'yup'

const companiesStore = useCompaniesStore()
const { resource } = storeToRefs(companiesStore)

const router = useRouter()

const toast = useToast()

const { isSubmitting, handleSubmit } = useForm<CompanyFormResource>({
  validationSchema: {
    name: yup.string().required().label('Name'),
    industry: yup.string().required().label('Industry'),
    email: yup.string().email().required().label('Email address'),
    phone: yup.number().required().label('Phone'),
    address: yup.string().required().label('Address'),
  },
})

const save = handleSubmit(async (payload, action) => {
  try {
    await companiesStore.storeResource({ payload })
    toast.success('Company has been successfully created!', {
      position: 'top-right',
    })

    if (resource.value) {
      router.push({ name: 'company-single', params: { companyId: resource.value.id } })
    } else {
      throw new Error('Something went wrong!')
    }
  } catch (error) {
    handleValidationError(error, action)
    if (!isAxiosError(error) && error instanceof Error) {
      toast.error(error.message, {
        position: 'top-right',
      })
    }
  }
})

onMounted(() => companiesStore.resetStore())
</script>

<style scoped></style>
