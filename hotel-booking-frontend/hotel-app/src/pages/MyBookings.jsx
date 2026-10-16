import { useState } from 'react'
import { api, asList, ENDPOINTS } from '../api'
import { useFetch } from '../useFetch'
import BookingsTable from '../components/BookingsTable'
import { Status } from '../components/ui'

export default function MyBookings() {
  const { data, loading, error, reload } = useFetch(ENDPOINTS.myBookings)
  const [msg, setMsg] = useState('')

  async function cancel(b) {
    if (!confirm(`Cancel booking #${b.bookings_id}?`)) return
    try {
      await api.post(ENDPOINTS.cancelBooking, { id: b.bookings_id })
      setMsg(`Booking #${b.bookings_id} cancelled.`)
      reload()
    } catch (e) { setMsg(e.message) }
  }

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">My bookings</h1>
      {msg && <p className="rounded-lg bg-slate-100 p-3 text-sm">{msg}</p>}
      <Status loading={loading} error={error} />
      {!loading && !error && <BookingsTable rows={asList(data)} onCancel={cancel} />}
    </div>
  )
}