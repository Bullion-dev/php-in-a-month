import { Navigate, Route, Routes } from 'react-router-dom'
import { isStaff, useAuth } from './auth'
import Reports from './pages/Reports'
import Layout from './components/Layout'
import AuthPage from './pages/AuthPage'
import { AdminDashboard, UserDashboard } from './pages/Dashboard'
import AdminBookings from './pages/AdminBookings'
import AdminRooms from './pages/AdminRooms'
import Browse from './pages/Browse'
import MyBookings from './pages/MyBookings'

// Route guard: only lets the right kind of person through.
function Guard({ role }) {
  const { user } = useAuth()
  if (!user) return <Navigate to="/login" replace />
  const mine = isStaff(user) ? 'admin' : 'user'
  if (mine !== role) return <Navigate to={mine === 'admin' ? '/admin' : '/app'} replace />
  return <Layout role={role} />
}

export default function App() {
  return (
    <Routes>
      <Route path="/login" element={<AuthPage />} />
      <Route path="/signup" element={<AuthPage signup />} />
      <Route path="/admin" element={<Guard role="admin" />}>
        <Route index element={<AdminDashboard />} />
        <Route path="bookings" element={<AdminBookings />} />
        <Route path="rooms" element={<AdminRooms />} />
        <Route path="reports" element={<Reports />} />
      </Route>
      <Route path="/app" element={<Guard role="user" />}>
        <Route index element={<UserDashboard />} />
        <Route path="rooms" element={<Browse />} />
        
        <Route path="bookings" element={<MyBookings />} />
      </Route>
      <Route path="*" element={<Navigate to="/login" replace />} />
    </Routes>
  )
}
