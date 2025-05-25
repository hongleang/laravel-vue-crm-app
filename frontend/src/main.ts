import 'vue-toast-notification/dist/theme-sugar.css'
import './styles/app.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { abilitiesPlugin } from '@casl/vue'

import App from './App.vue'
import router from './router'

import ToastPlugin from 'vue-toast-notification'

// Bootstrap JS
import * as bootstrap from 'bootstrap'
import { ability } from './utils/abilities'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(abilitiesPlugin, ability)
app.use(ToastPlugin)

app.mount('#app')
