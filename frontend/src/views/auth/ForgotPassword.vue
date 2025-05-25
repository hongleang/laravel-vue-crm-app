<template>
  <div class="auth-layout bg-body-secondary">
    <main class="auth-layout-card shadow w-100 m-auto">
      <form>
        <AuthLogo class="mb-5" />
        <h1 class="h3 mb-3 fw-normal">Forgot Password</h1>

        <p class="text-muted">
          Please enter the email address you'd like your password reset information sent to
        </p>

        <div class="mb-3">
          <label for="email" class="mb-1">Email address</label>
          <input type="email" class="form-control" id="email" />
        </div>

        <button @click="send" class="btn btn-dark w-100 py-2" type="button">
          Request reset link
        </button>

        <div class="mt-4">
          <RouterLink :to="{ name: 'login' }" class="text-muted">Back to login</RouterLink>
        </div>
      </form>
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

async function send() {
  router.push({ name: 'reset-password' })
}
</script>
