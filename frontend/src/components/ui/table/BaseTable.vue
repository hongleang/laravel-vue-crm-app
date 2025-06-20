<template>
  <div class="table-responsive">
    <table class="table table-hover table-select table-round align-middle mb-0">
      <thead>
        <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
          <th
            v-for="header in headerGroup.headers"
            :key="header.id"
            :style="{ width: `${header.getSize()}px` }"
          >
            <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in table.getRowModel().rows" :key="item.id">
          <td v-for="cell in item.getVisibleCells()" :key="cell.id">
            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div v-if="pagination" class="row align-items-center mt-4 sticky-bottom">
    <div class="col">
      <!-- Text -->
      <p class="text-body-secondary mb-0">
        {{ pagination.from }} – {{ pagination.to }} ({{ pagination.total }}
        total)
      </p>
    </div>
    <div class="col-auto">
      <!-- Pagination -->
      <nav aria-label="Page navigation example">
        <ul v-if="pagination.links" class="pagination mb-0">
          <li v-for="item in pagination.links" :key="item.label" class="page-item">
            <span
              :disable="showNavigation(pagination.links, item)"
              class="page-link"
              :class="{ active: item.active }"
              @click="loadPage(item.url)"
            >
              {{ paginationLabel(item.label) }}
            </span>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup lang="ts" generic="T">
import type { Link, Pagination } from '@/types/model'
import { useVueTable, FlexRender, type ColumnDef, getCoreRowModel } from '@tanstack/vue-table'

import { toRefs } from 'vue'

const props = defineProps<{
  data: T[]
  columns: ColumnDef<T>[]
  pagination?: Pagination | null
}>()

const emit = defineEmits<{
  (e: 'load-new-page', url: string): Promise<void>
}>()

const { data } = toRefs(props)

const table = useVueTable({
  columns: props.columns,
  data,
  getCoreRowModel: getCoreRowModel(),
  manualPagination: true,
  pageCount: props.pagination?.total,
  state: {
    ...(props.pagination && {
      pagination: {
        pageIndex: props.pagination?.current_page || 1,
        pageSize: 1,
      },
    }),
  },
})

const paginationLabel = (label: string): string => {
  const nextBtn = label.toLowerCase().includes('next')
  const previousBtn = label.toLowerCase().includes('previous')
  if (!nextBtn && !previousBtn) {
    return label
  }

  return nextBtn ? '»' : '«'
}

const showNavigation = (links: Link[], item: Link): boolean => {
  // Should not show if there's only one nav button
  return links.filter((link) => link.url).length > 1 && item.url !== null
}

const loadPage = async (url: string | null) => {
  if (url) {
    emit('load-new-page', url)
  }
}
</script>

<style scoped></style>
