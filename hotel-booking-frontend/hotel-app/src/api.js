// THE RECEPTIONIST: every request to your PHP backend goes through here.
import { mockRequest } from './mock'
const BASE = import.meta.env.VITE_API_URL
const DEMO = import.meta.env.VITE_USE_MOCK === 'true' // true = fake data, no backend needed

// One entry per PHP file in your project folder.
export const ENDPOINTS = {
  login: '/login.php',
  logout: '/logout.php',
  register: '/register.php',
  rooms: '/get-rooms.php',
  addRoom: '/add-rooms.php',
  updateRoom: '/update-rooms.php',
  deleteRoom: '/delete-rooms.php',
  available: '/available-rooms.php',
  addBooking: '/add-booking.php',
  myBookings: '/my-bookings.php',
  bookings: '/get-bookings.php',
  stats: '/dashboard-stats.php',
}

//Room photos are stored as "uploads/room_123.jpg", so we add the server address in front.
export const imgUrl = (p) => (p ? `${BASE}/${p}` : null)

//wait for the network then create a receptionist which is request
async function request(path, { method = 'GET', body } = {}) {
  if (DEMO) return mockRequest(path, method, body)
  const isForm = body instanceof FormData // room forms carry a photo, so they go as FormData
  const res = await fetch(BASE + path, {
    method,
    credentials: 'include', // attach my cookies to this request, even to a different site
    headers: isForm ? {} : { 'Content-Type': 'application/json' },
    body: body ? (isForm ? body : JSON.stringify(body)) : undefined,
  })
  const data = await res.json().catch(() => null)
  if (res.status === 401 && !path.startsWith(ENDPOINTS.login)) { localStorage.removeItem('user'); location.href = '/login' }
  if (!res.ok) throw new Error(data?.message || `Request failed (${res.status})`)
  return data
}

export const api = { get: (p) => request(p), post: (p, body) => request(p, { method: 'POST', body }) }
// Your PHP answers { status, data: [...] }
export const asList = (d) => (Array.isArray(d) ? d : d?.data ?? [])
