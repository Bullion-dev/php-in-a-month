import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { isStaff, useAuth } from '../auth'
import { Btn, Field } from '../components/ui'

export default function AuthPage({ signup }) {
  const [f, setF] = useState({ username: '', email: '', password: '', confirm_password: '' })
  const [err, setErr] = useState('')
  const [busy, setBusy] = useState(false)
  const { login, register } = useAuth()
  const nav = useNavigate()
  const set = (k) => (v) => setF({ ...f, [k]: v })

  async function submit(e) {
    e.preventDefault(); setErr(''); setBusy(true)
    try {
      if (signup) { await register(f); nav('/login') } // register.php checks the two passwords match
      else { const u = await login(f.email, f.password); nav(isStaff(u) ? '/admin' : '/app') }
    } catch (x) { setErr(x.message) } finally { setBusy(false) }
  }
    return (
    <div className="grid min-h-screen md:grid-cols-2">
      {/* LEFT: image panel */}
      <div
        className="relative flex min-h-48 items-center justify-center bg-slate-700 bg-cover bg-center p-10 text-center text-white"
        style={{ backgroundImage: `url(${signup ? '/signup.jpg.jpg' : '/login.jpg.jpg'})` }}
      >
        <div className="absolute inset-0 bg-slate-900/60" />
        <div className="relative flex flex-col items-center gap-2">
          <h1 className="text-3xl font-bold">Hotel Booking System</h1>
          <p className="max-w-xs text-slate-200">{signup ? 'Create your account and start booking.' : 'Welcome back! Sign in to continue.'}</p>
        </div>
      </div>

      {/* RIGHT: the form */}
      <form onSubmit={submit} className="m-auto w-full max-w-md space-y-4 p-8">
        <h2 className="text-2xl font-bold">{signup ? 'Create account' : 'Login'}</h2>
        {import.meta.env.VITE_USE_MOCK === 'true' && (
          <p className="rounded-lg bg-amber-50 p-3 text-sm text-amber-800">Demo mode. Admin: admin@demo.com / admin123. Customer: user@demo.com / user123.</p>
        )}
        {signup && <Field label="Username" value={f.username} onChange={set('username')} />}
        <Field label="Email address" f={{ type: 'email' }} value={f.email} onChange={set('email')} />
        <Field label="Password" f={{ type: 'password' }} value={f.password} onChange={set('password')} />
        {signup && <Field label="Confirm password" f={{ type: 'password' }} value={f.confirm_password} onChange={set('confirm_password')} />}
        {err && <p className="rounded-lg bg-red-50 p-3 text-sm text-red-700">{err}</p>}
        <Btn type="submit" disabled={busy} className="w-full">{busy ? 'Please wait…' : signup ? 'Sign up' : 'Login'}</Btn>
        <p className="text-center text-sm text-slate-500">
          {signup ? <>Already have an account? <Link className="font-semibold text-slate-700 underline" to="/login">Login</Link></>
            : <>Don't have an account? <Link className="font-semibold text-slate-700 underline" to="/signup">Sign up</Link></>}
        </p>
      </form>
    </div>
  )
}