import { asList, ENDPOINTS } from '../api'
import { useAuth } from '../auth'
import { useFetch } from '../useFetch'
import BookingsTable from '../components/BookingsTable'
import { Stat, Status } from '../components/ui'

// Admin: numbers from dashboard-stats.php, table from get-bookings.php
export function AdminDashboard() {
  const s = useFetch(ENDPOINTS.stats), b = useFetch(ENDPOINTS.bookings)
  if (s.loading || b.loading || s.error || b.error) return <Status loading={s.loading || b.loading} error={s.error || b.error} />
  const d = s.data.data
  return (
    <div className="space-y-5">
      <h1 className="text-2xl font-bold">Dashboard</h1>
      <div className="grid gap-3 sm:grid-cols-3">
        <Stat label="Total bookings" value={d.total_bookings} /><Stat label="Total customers" value={d.total_customers} /><Stat label="Total rooms" value={d.total_rooms} />
      </div>
      <h2 className="text-lg font-bold">Recent bookings</h2>
      <BookingsTable admin rows={asList(b.data).slice(0, 5)} />
    </div>
  )
}

// Customer: everything comes from my-bookings.php
export function UserDashboard() {
  const { user } = useAuth()
  const b = useFetch(ENDPOINTS.myBookings)
  if (b.loading || b.error) return <Status loading={b.loading} error={b.error} />
 const rows = asList(b.data)
const is = (r, s) => String(r.status).trim().toLowerCase() === s // ignores capitals and stray spaces
const n = (s) => rows.filter((r) => is(r, s)).length
  return (
    <div className="space-y-5">
      <h1 className="text-2xl font-bold">Welcome, {user.name}!</h1>
      <div className="grid gap-3 sm:grid-cols-3"><Stat label="Total bookings" value={rows.length} /><Stat label="Upcoming" value={n('upcoming')} /><Stat label="Completed" value={n('completed')} /></div>
      <h2 className="text-lg font-bold">Upcoming bookings</h2>
      <BookingsTable rows={rows.filter((r) => is(r, 'upcoming'))} />
    </div>
  )
}
