import axios from 'axios'

const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true,
  withXSRFToken: true,
})

http.interceptors.request.use(
  (config) => {
    const nonApiRoutes = [
      '/login',
      '/logout',
      '/register',
      '/forgot-password',
      '/reset-password',
      '/verify-email',
      '/email/verification-notification',
    ]

    if (config.url && !nonApiRoutes.includes(config.url)) {
      config.url = `/api${config.url}`
    }

    return config
  },
  (error) => {
    return Promise.reject(error)
  },
)

http.interceptors.response.use(
  (response) => response,
  (error) => {
    return Promise.reject(error)
  },
)

export default http
