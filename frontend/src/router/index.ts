import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import Login from '@/views/auth/Login.vue'
import ForgotPassword from '@/views/auth/ForgotPassword.vue'
import ResetPassword from '@/views/auth/ResetPassword.vue'
import UserList from '@/views/user/UserList.vue'
import UserSingle from '@/views/user/UserSingle.vue'
import UserSingleDetails from '@/components/user/UserSingleDetails.vue'
import { useAuthStore } from '@/stores/auth'
import { toNumber } from 'lodash'
import UserSingleCreate from '@/views/user/UserSingleCreate.vue'

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
        {
          path: '/users',
          name: 'user-list',
          component: UserList,
        },
        {
          path: '/users/create',
          name: 'user-create',
          component: UserSingleCreate,
        },
        {
          path: '/users/:userId',
          name: 'user-single',
          redirect: { name: 'user-single-details' },
          props: (route) => ({ userId: toNumber(route.params.userId) }),
          component: UserSingle,
          children: [
            {
              path: '/users/:userId/details',
              name: 'user-single-details',
              component: UserSingleDetails,
            },
          ],
        },
      ],
    },
    {
      path: '/',
      name: 'home',
      redirect: '/login',
      children: [
        {
          path: '/login',
          name: 'login',
          component: Login,
        },
        {
          path: '/forgot-password',
          name: 'forgot-password',
          component: ForgotPassword,
        },
        {
          path: '/reset-password',
          name: 'reset-password',
          component: ResetPassword,
        },
      ],
    },
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
