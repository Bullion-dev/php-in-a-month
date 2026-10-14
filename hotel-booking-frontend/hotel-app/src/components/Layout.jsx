import { useState } from 'react'
import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import { useAuth } from '../auth'

const NAV = {
  admin: [['/admin', 'Dashboard'], ['/admin/bookings', 'Bookings'], ['/admin/rooms', 'Rooms']],
  user: [['/app', 'Home'], ['/app/rooms', 'Browse rooms'], ['/app/bookings', 'My bookings']],
}
export default function Layout({ role }) {
  const [open, setOpen] = useState(false)
  const { user, logout } = useAuth()
  const nav = useNavigate()
  return (
    <div className="min-h-screen bg-slate-100 text-slate-800 md:grid md:grid-cols-[230px_1fr]">
      {open && <div className="fixed inset-0 z-20 bg-black/40 md:hidden" onClick={() => setOpen(false)} />}
      <aside className={`fixed inset-y-0 left-0 z-30 flex w-60 flex-col gap-1 border-r border-slate-200 bg-white p-3 transition-transform md:static md:w-auto md:translate-x-0 ${open ? '' : '-translate-x-full'}`}>
        <p className="px-3 py-3 text-lg font-bold">{role === 'admin' ? 'Admin Panel' : 'User Panel'}</p>
        {NAV[role].map(([to, label]) => (
          <NavLink key={to} to={to} end={to === '/admin' || to === '/app'} onClick={() => setOpen(false)}
            className={({ isActive }) => `rounded-lg px-3 py-2 text-sm font-medium ${isActive ? 'bg-slate-200 text-slate-900' : 'text-slate-500 hover:bg-slate-100'}`}>{label}</NavLink>
        ))}
        <button onClick={() => { logout(); nav('/login') }} className="mt-auto rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-500 hover:bg-slate-100">Logout</button>
      </aside>
      <div className="min-w-0">
        <header className="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
          <button className="rounded-lg border border-slate-300 px-3 py-1 md:hidden" onClick={() => setOpen(true)} aria-label="Open menu">☰</button>
          <span className="ml-auto text-sm text-slate-600">{user?.name}</span>
        </header>
        <main className="p-4 md:p-6"><Outlet /></main>
      </div>
    </div>
  )
}
