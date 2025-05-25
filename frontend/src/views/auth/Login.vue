<template>
  <div class="auth-layout bg-body-secondary">
    <main class="auth-layout-card shadow w-100 m-auto">
      <form>
        <AuthLogo class="mb-5" />
        <h1 class="h3 mb-3 fw-normal">Sign In</h1>

        <InputText
          form-group
          name="email"
          type="email"
          label="Email address"
          placeholder="name@example.com"
        />
        <div class="mb-3">
          <div class="row mb-1">
            <div class="col">
              <label for="password">Password</label>
            </div>
            <div class="col-12 col-lg-auto">
              <RouterLink :to="{ name: 'forgot-password' }" class="text-muted">
                Forgot password?
              </RouterLink>
            </div>
          </div>
          <InputText form-group name="password" type="password" />
        </div>
        <div class="form-check text-start my-3">
          <input v-model="remember" class="form-check-input" type="checkbox" />
          <label class="form-check-label"> Remember me </label>
        </div>
        <button
          @click="login"
          class="btn btn-dark w-100 py-2"
          type="button"
          :disabled="isSubmitting"
        >
          {{ isSubmitting ? 'Signing in...' : 'Sign in' }}
        </button>
      </form>
    </main>
  </div>
</template>

<script setup lang="ts">
import AuthLogo from '@/components/AuthLogo.vue'
import InputText from '@/components/ui/InputText.vue'
import { useAuthStore } from '@/stores/auth'
import { handleValidationError } from '@/utils/helpers'
import { useForm } from 'vee-validate'
import { useRouter } from 'vue-router'
import * as yup from 'yup'

const router = useRouter()
const authStore = useAuthStore()

const { isSubmitting, handleSubmit, defineField } = useForm({
  validationSchema: {
    email: yup.string().email().required().label('Email address'),
    password: yup.string().required().label('Password'),
    remember: yup.boolean()
  },
  initialValues: {
    email: import.meta.env.VITE_DEFAULT_EMAIL ?? '',
    password: import.meta.env.VITE_DEFAULT_PASSWORD ?? '',
    remember: false
  },
})

const [remember] = defineField('remember')

const login = handleSubmit(async (values, action) => {
  try {
    await authStore.login(values)
    router.push({ name: 'dashboard' })
  } catch (error) {
    handleValidationError(error, action)
  }
})
</script>
