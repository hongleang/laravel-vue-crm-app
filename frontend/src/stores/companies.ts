import { createCrudStore } from './utils/createCrudStore'

export const useCompaniesStore = createCrudStore<CompanyListResource, CompanyResource>(
  'companies',
  {},
)
export type CompanyListResource = {
  id: number
  name: string
  industry: string
  phone: string
  email: string
  address: string
}

type File = {
  id: number
  hash: string
  name: string
  extension: string
  bytes: number
}

type Note = {
  id: number
  content: string
  created_by: string
}

export type CompanyResource = {
  id: number
  name: string
  industry: string
  phone: string
  email: string
  address: string
  files: File[] | null
  notes: Note[] | null
}
