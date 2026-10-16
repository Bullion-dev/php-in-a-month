import { useEffect, useState } from 'react'
import { api, asList, ENDPOINTS, imgUrl } from '../api'
import { useAuth } from '../auth'
import { Btn, Field, Modal, Status } from '../components/ui'
import { money } from '../format'

export default function Browse() {
  const { user } = useAuth()
  const [dates, setDates] = useState({ check_in: '', check_out: '' })
  const [rooms, setRooms] = useState([])
  const [searched, setSearched] = useState(false)
  const [state, setState] = useState({ loading: true, error: '' })
  const [room, setRoom] = useState(null) // room being booked
  const [phone, setPhone] = useState('')
  const [ok, setOk] = useState('')

  async function load(path) {
    setState({ loading: true, error: '' })
    try { setRooms(asList(await api.get(path))); setState({ loading: false, error: '' }) }
    catch (e) { setState({ loading: false, error: e.message }) }
  }
  useEffect(() => { load(ENDPOINTS.rooms) }, [])

  // available-rooms.php needs both dates, then returns only the rooms that are free
  const check = () => { setSearched(true); load(`${ENDPOINTS.available}?check_in=${dates.check_in}&check_out=${dates.check_out}`) }
  function search(e) { e.preventDefault(); setOk(''); check() }
  function openBook(r) {
    if (!dates.check_in || !dates.check_out) return setState((s) => ({ ...s, error: 'Pick your check-in and check-out dates first.' }))
    setOk(''); setRoom(r)
  }
  async function confirmBooking(e) {
    e.preventDefault()
    try {
      // add-booking.php wants exactly these field names
      const r = await api.post(ENDPOINTS.addBooking, { full_name: user.name, email: user.email, phone, room_number: room.room_number, room_type: room.room_type, check_in_date: dates.check_in, check_out_date: dates.check_out })
      setRoom(null); setOk(`Booking confirmed. Total: ${money(r.total_price)}`); check()
    } catch (x) { setRoom(null); setState((s) => ({ ...s, error: x.message })) }
  }

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-bold">Rooms</h1>
      <form onSubmit={search} className="grid items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-[1fr_1fr_auto]">
        <Field label="Check-in date" f={{ type: 'date' }} value={dates.check_in} onChange={(v) => setDates({ ...dates, check_in: v })} />
        <Field label="Check-out date" f={{ type: 'date' }} value={dates.check_out} onChange={(v) => setDates({ ...dates, check_out: v })} />
        <Btn type="submit">Check availability</Btn>
      </form>
      {ok && <p className="rounded-lg bg-emerald-50 p-3 text-sm text-emerald-700">{ok}</p>}
      <Status {...state} />
      {!state.loading && <p className="text-sm text-slate-500">{searched ? `${rooms.length} room(s) free for your dates` : 'Showing all rooms. Pick dates to see which are free.'}</p>}
      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {rooms.map((r) => (
          <div key={r.room_id} className="overflow-hidden rounded-xl border border-slate-200 bg-white">
            {r.image_url ? <img src={imgUrl(r.image_url)} alt={r.room_type} className="h-32 w-full object-cover" /> : <div className="grid h-32 place-items-center bg-slate-200 text-3xl text-slate-400">🖼</div>}
            <div className="space-y-1 p-4">
              <p className="font-semibold">{r.room_type} · Room {r.room_number}</p><p className="text-sm text-slate-500">{money(r.price_per_night)} / night</p>
              <Btn className="mt-2 w-full" onClick={() => openBook(r)}>Book now</Btn>
            </div>
          </div>
        ))}
      </div>
      {room && (
        <Modal title="Book room" onClose={() => setRoom(null)}>
          <form onSubmit={confirmBooking} className="space-y-3">
            <p className="font-semibold">{room.room_type} · Room {room.room_number} · {money(room.price_per_night)} / night</p>
            <p className="text-sm text-slate-600">{dates.check_in} to {dates.check_out}</p>
            <Field label="Phone number" f={{ type: 'tel' }} value={phone} onChange={setPhone} />
            <div className="flex justify-end gap-2"><Btn type="button" ghost onClick={() => setRoom(null)}>Cancel</Btn><Btn type="submit">Confirm booking</Btn></div>
          </form>
        </Modal>
      )}
    </div>
  )
}
