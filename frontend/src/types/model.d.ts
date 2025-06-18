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

export interface Pagination {
  total: number
  perPage: number
  currentPage: number
  lastPage: number
}

export interface Link {
  url: string | null
  label: string
  active: boolean
}