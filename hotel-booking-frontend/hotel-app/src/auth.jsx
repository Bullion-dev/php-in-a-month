import { createContext, useContext, useState } from 'react'
import { api, ENDPOINTS } from './api'

const Ctx = createContext(null)
export const useAuth = () => useContext(Ctx)
export const isStaff = (u) => ['admin', 'staff'].includes(u?.role) // staff and admin use the admin panel

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => JSON.parse(localStorage.getItem('user') || 'null'))

  async function login(email, password) {
    const d = await api.post(ENDPOINTS.login, { email, password }) // login.php answers { id, name, email, role }
    const u = { id: d.id, name: d.name, email: d.email, role: d.role }
    localStorage.setItem('user', JSON.stringify(u)) // the login itself is remembered by the PHP session cookie
    setUser(u)
    return u
  }
  const register = (f) => api.post(ENDPOINTS.register, f)
  function logout() { localStorage.removeItem('user'); setUser(null) }

  return <Ctx.Provider value={{ user, login, register, logout }}>{children}</Ctx.Provider>
}
