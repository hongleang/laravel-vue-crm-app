<template>
  <div class="auth-layout bg-body-secondary">
    <main class="auth-layout-card shadow w-100 m-auto">
      <form>
        <AuthLogo class="mb-5" />
        <h1 class="h3 mb-3 fw-normal">Password Request Sent</h1>

        <p class="text-muted">
          Passord request has been sent to your email. Please have a look at your inbox, and click on the reset link provided
        </p>

        <RouterLink :to="{ name: 'login' }" class="btn btn-dark w-100 py-2">Back to login</RouterLink>

        <div class="mt-4">
          Didn't received any email
          <button class="btn btn-link text-muted px-0">Click here</button>
        </div>
      </form>
    </main>
  </div>
</template>

<script setup lang="ts">
import AuthLogo from '@/components/AuthLogo.vue'
</script>
