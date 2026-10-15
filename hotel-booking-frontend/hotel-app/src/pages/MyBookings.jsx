import { asList, ENDPOINTS } from '../api'
import { useFetch } from '../useFetch'
import BookingsTable from '../components/BookingsTable'
import { Status } from '../components/ui'

export default function MyBookings() {
  const { data, loading, error } = useFetch(ENDPOINTS.myBookings)
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">My bookings</h1>
      <Status loading={loading} error={error} />
      {!loading && !error && <BookingsTable rows={asList(data)} />}
    </div>
  )
}
