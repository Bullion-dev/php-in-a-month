import { useState } from 'react'
import { asList, ENDPOINTS } from '../api'
import { useFetch } from '../useFetch'
import { Status } from '../components/ui'

export default function AdminCustomers() {
  const { data, loading, error } = useFetch(ENDPOINTS.customers)
  const [q, setQ] = useState('')
  const rows = asList(data).filter((c) => JSON.stringify(Object.values(c)).toLowerCase().includes(q.toLowerCase()))

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Customers</h1>
      <input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Search by name, email or phone…" aria-label="Search" className="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm" />
      <Status loading={loading} error={error} />
      {!loading && !error && (
        <div className="overflow-x-auto rounded-xl border border-slate-200 bg-white">
          <table className="w-full min-w-[480px] text-left text-sm">
            <thead className="text-slate-500">
              <tr>{['ID', 'Name', 'Email', 'Phone'].map((h) => <th key={h} className="px-4 py-3 font-semibold">{h}</th>)}</tr>
            </thead>
            <tbody>
              {rows.map((c) => (
                <tr key={c.customer_id} className="whitespace-nowrap border-t border-slate-100">
                  <td className="px-4 py-3">#{c.customer_id}</td>
                  <td className="px-4 py-3">{c.full_name}</td>
                  <td className="px-4 py-3">{c.email}</td>
                  <td className="px-4 py-3">{c.phone}</td>
                </tr>
              ))}
              {!rows.length && <tr><td colSpan="4" className="px-4 py-8 text-center text-slate-500">No customers yet.</td></tr>}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}