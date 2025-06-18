import ForgotPassword from '@/views/auth/ForgotPassword.vue'
import Login from '@/views/auth/Login.vue'
import ResetPassword from '@/views/auth/ResetPassword.vue'

export default [
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
]
