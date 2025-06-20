type UserStatus = 'Enabled' | 'Disabled' | 'Archived' | 'Pending'

export type LoggedInUser = {
  id: number
  name: string
  email: string
  status: UserStatus
  roles: string[]
  abilities: string[]
}

export type Credential = {
  email: string
  password: string
  remember: boolean
}

type Pagination = {
  current_page: number
  from: number
  last_page: number
  links: Link[]
  path: string | null
  per_page: number
  to: number
  total: number
}
export interface Link {
  url: string | null
  label: string
  active: boolean
}
