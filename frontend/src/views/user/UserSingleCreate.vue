<template>
  <AppLayout title="Users" subheading="Users | User single" container>
    <div class="card">
      <div class="card-body">
        <div class="vstack gap-3">
          <div class="row gy-3">
            <div class="col-12 col-lg-6">
              <InputText form-group name="first_name" type="text" label="First name *" />
            </div>
            <div class="col-12 col-lg-6">
              <InputText form-group name="last_name" type="text" label="Last Name *" />
            </div>
          </div>
          <div class="row gy-3">
            <div class="col-12 col-lg-6">
              <InputText form-group name="email" type="email" label="Email Address *" />
            </div>
            <div class="col-12 col-lg-6">
              <InputText form-group name="mobile" type="text" label="Mobile Number *" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row align-items-center gy-3 mt-4">
      <div class="col">
        <RouterLink :to="{ name: 'user-list' }" class="btn btn-outline-secondary">
          Cancel
        </RouterLink>
      </div>
      <div class="col-auto">
        <button @click="save" class="btn btn-dark" :disabled="isSubmitting">Save User</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/components/layout/AppLayout.vue'
import InputText from '@/components/ui/InputText.vue'
import { useUsersStore, type UserFormResource } from '@/stores/users'
import { handleValidationError } from '@/utils/helpers'
import { useForm } from 'vee-validate'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toast-notification'
import * as yup from 'yup'

const userStore = useUsersStore()

const router = useRouter()

const toast = useToast()

const { isSubmitting, handleSubmit } = useForm<UserFormResource>({
  validationSchema: {
    first_name: yup.string().required().label('First name'),
    last_name: yup.string().required().label('Last name'),
    email: yup.string().email().required().label('Email address'),
    mobile: yup.string().required().label('Mobile number'),
  },
})

const save = handleSubmit(async (payload, action) => {
  try {
    await userStore.storeResource({ payload })
    toast.success('User has been successfully created!', {
      position: 'top-right',
    })

    router.push({ name: 'user-list' })
  } catch (error) {
    handleValidationError(error, action)
  }
})
</script>

<style scoped></style>
