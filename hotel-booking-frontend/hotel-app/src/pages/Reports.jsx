import { asList, ENDPOINTS } from '../api'
import { useFetch } from '../useFetch'
import { Stat, Status } from '../components/ui'

export default function Reports() {
  const { data, loading, error } = useFetch(ENDPOINTS.bookings)
  if (loading || error) return <Status loading={loading} error={error} />

  const rows = asList(data)
  const is = (r, s) => String(r.status).trim().toLowerCase() === s
  const count = (s) => rows.filter((r) => is(r, s)).length

  // revenue: add up total_price, skipping cancelled bookings
  const revenue = rows
    .filter((r) => !is(r, 'cancelled'))
    .reduce((sum, r) => sum + Number(r.total_price), 0)

  // chart: how many bookings start on each check-in date (last 7 dates)
  const perDate = {}
  rows.forEach((r) => { perDate[r.check_in] = (perDate[r.check_in] || 0) + 1 })
  const bars = Object.entries(perDate).sort().slice(-7)
  const max = Math.max(1, ...bars.map(([, n]) => n))

  return (
    <div className="space-y-5">
      <h1 className="text-2xl font-bold">Reports</h1>
      <div className="grid gap-3 sm:grid-cols-4">
        <Stat label="Total bookings" value={rows.length} />
        <Stat label="Upcoming" value={count('upcoming')} />
        <Stat label="Cancelled" value={count('cancelled')} />
        <Stat label="Revenue" value={`$${revenue.toLocaleString()}`} />
      </div>
      <div className="rounded-xl border border-slate-200 bg-white p-4">
        <p className="font-semibold">Bookings by check-in date</p>
        <div className="mt-4 flex h-40 items-end gap-2">
          {bars.map(([date, n]) => (
            <div key={date} className="flex flex-1 flex-col items-center justify-end gap-1">
              <span className="text-xs text-slate-500">{n}</span>
              <div className="w-full rounded-t bg-slate-600" style={{ height: `${(n / max) * 100}px` }} />
              <span className="text-[10px] text-slate-500">{date.slice(5)}</span>
            </div>
          ))}
          {!bars.length && <p className="text-sm text-slate-500">No bookings yet.</p>}
        </div>
      </div>
    </div>
  )
}