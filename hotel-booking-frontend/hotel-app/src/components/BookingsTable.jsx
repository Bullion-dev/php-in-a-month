import { Badge } from './ui'
import { money } from '../format'
// Columns come straight from get-bookings.php / my-bookings.php
export default function BookingsTable({ rows, admin }) {
  const heads = ['ID', admin && 'Guest', 'Room', 'Check in', 'Check out', 'Total', 'Status'].filter(Boolean)
  return (
    <div className="overflow-x-auto rounded-xl border border-slate-200 bg-white">
      <table className="w-full min-w-[560px] text-left text-sm">
        <thead className="text-slate-500"><tr>{heads.map((h) => <th key={h} className="px-4 py-3 font-semibold">{h}</th>)}</tr></thead>
        <tbody>
          {rows.map((r) => (
            <tr key={r.bookings_id} className="whitespace-nowrap border-t border-slate-100">
              <td className="px-4 py-3">#{r.bookings_id}</td>
              {admin && <td className="px-4 py-3">{r.full_name}</td>}
              <td className="px-4 py-3">{r.room_number} · {r.room_type}</td>
              <td className="px-4 py-3">{r.check_in}</td><td className="px-4 py-3">{r.check_out}</td>
              <td className="px-4 py-3">{money(r.total_price)}</td><td className="px-4 py-3"><Badge v={r.status} /></td>
            </tr>
          ))}
          {!rows.length && <tr><td colSpan="7" className="px-4 py-8 text-center text-slate-500">No bookings yet.</td></tr>}
        </tbody>
      </table>
    </div>
  )
}
