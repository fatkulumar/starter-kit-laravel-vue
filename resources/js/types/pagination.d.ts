export interface Pagination {
  current_page: number
  per_page: number
  total: number
  last_page: number
  next_page_url: string | null
  prev_page_url: string | null
  from: number
  to: number
  path: string
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
}
