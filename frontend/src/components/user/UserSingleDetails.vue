<template>
  <div class="card">
    <div class="card-body">
      <h3 class="fs-5 mb-1">General</h3>
      <p class="text-body-secondary">General information about the organisation.</p>

      <hr class="my-5" />

      <div v-if="loading">
        <div class="text-center text-muted">Loading...</div>
      </div>

      <div v-if="user" class="vstack gap-3">
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

      <div v-else class="text-center">No record found</div>
    </div>
  </div>

  <div v-if="user" class="row align-items-center gy-3 mt-4">
    <div class="col">
      <RouterLink :to="{ name: 'user-list' }" class="btn btn-outline-secondary">Cancel</RouterLink>
    </div>
    <div class="col-auto">
      <button v-if="loggedInUserCan('write user')" @click="save" class="btn btn-dark" :disabled="isSubmitting">
        Save User
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { type UserFormResource, useUsersStore } from '@/stores/users'
import { storeToRefs } from 'pinia'
import { useForm } from 'vee-validate'
import * as yup from 'yup'
import InputText from '../ui/InputText.vue'
import { handleValidationError } from '@/utils/helpers'
import { watchEffect } from 'vue'
import { useToast } from 'vue-toast-notification'
import { useLoggedInUser } from '@/composables/useLoggedInUser'

const userStore = useUsersStore()
const { resource: user, loading } = storeToRefs(userStore)

const { loggedInUserCan } = useLoggedInUser()

const toast = useToast()

const { isSubmitting, handleSubmit, setValues } = useForm<UserFormResource>({
  validationSchema: {
    first_name: yup.string().required().label('First name'),
    last_name: yup.string().required().label('Last name'),
    email: yup.string().email().required().label('Email address'),
    mobile: yup.string().required().label('Mobile number'),
  },
})

const save = handleSubmit(async (values, action) => {
  if (!user.value) return

  try {
    await userStore.updateResource({
      id: user.value.id,
      payload: values,
    })
    toast.success('User has been successfully saved!', {
      position: 'top-right',
    })
  } catch (error) {
    handleValidationError(error, action)
  }
})

watchEffect(() => {
  setValues({
    last_name: user.value?.last_name,
    first_name: user.value?.first_name,
    email: user.value?.email,
    mobile: user.value?.mobile,
  })
})
</script>

<style lang="scss" scoped></style>
