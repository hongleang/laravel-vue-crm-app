import type { RouteRecordRaw } from 'vue-router'

const modules = import.meta.glob('./**/*.ts', {
  eager: true,
  import: 'default',
})

let routes: RouteRecordRaw[] = []

Object.keys(modules).forEach((path) => {
  if (!path.endsWith('/index.ts')) {
    const mod = modules[path]
    if (typeof mod === 'object' && mod !== null) {
      const route = mod as RouteRecordRaw[]
      routes = routes.concat(...route)
    } else {
      console.warn(`Invalid module at ${path}`)
    }
  }
})

export default routes
