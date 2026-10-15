import { useState } from 'react'
import { asList, ENDPOINTS } from '../api'
import { useFetch } from '../useFetch'
import BookingsTable from '../components/BookingsTable'
import { Status } from '../components/ui'

export default function AdminBookings() {
  const { data, loading, error } = useFetch(ENDPOINTS.bookings)
  const [q, setQ] = useState('')
  const rows = asList(data).filter((r) => JSON.stringify(Object.values(r)).toLowerCase().includes(q.toLowerCase()))
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Bookings</h1>
      <input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Search by guest name, room type or ID…" aria-label="Search" className="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" />
      <Status loading={loading} error={error} />
      {!loading && !error && <BookingsTable admin rows={rows} />}
    </div>
  )
}
