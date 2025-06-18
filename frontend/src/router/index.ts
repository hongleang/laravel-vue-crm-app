import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import authRoutes from './routes/auth'
import routes from './routes/authenticated'
import Dashboard from '@/views/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      meta: {
        requireAuth: true,
      },
      children: [
        {
          path: '/dashboard',
          name: 'dashboard',
          component: Dashboard,
        },
        ...routes,
      ],
    },
    ...authRoutes,
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  await authStore.getUser()

  if (to.meta.requireAuth) {
    if (authStore.user) {
      return next()
    } else {
      return next({ name: 'login' })
    }
  } else {
    if (authStore.user) {
      return next({ name: 'dashboard' })
    } else {
      return next()
    }
  }
})

export default router
