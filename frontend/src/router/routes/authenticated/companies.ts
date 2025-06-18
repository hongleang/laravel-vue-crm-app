import CompanySingleDetails from '@/components/companies/CompanySingleDetails.vue'
import CompanySingleFiles from '@/components/companies/CompanySingleFiles.vue'
import CompanyList from '@/views/companies/CompanyList.vue'
import CompanySingle from '@/views/companies/CompanySingle.vue'
import CompanySingleCreate from '@/views/companies/CompanySingleCreate.vue'
import { toNumber } from 'lodash'
import type { RouteLocationNormalizedGeneric } from 'vue-router'

export default [
  
  {
    path: '/companies',
    name: 'company-list',
    component: CompanyList,
  },
  {
    path: '/companies/create',
    name: 'company-create',
    component: CompanySingleCreate,
  },
  {
    path: '/companies/:companyId',
    name: 'company-single',
    redirect: { name: 'company-single-details' },
    props: (route: RouteLocationNormalizedGeneric) => ({ companyId: toNumber(route.params.companyId) }),
    component: CompanySingle,
    children: [
      {
        path: '/companies/:companyId/details',
        name: 'company-single-details',
        component: CompanySingleDetails,
      },
      {
        path: '/companies/:companyId/files',
        name: 'company-single-files',
        component: CompanySingleFiles,
      },
    ],
  },
]
