import { useState } from 'react'
import { api, asList, ENDPOINTS, imgUrl } from '../api'
import { useFetch } from '../useFetch'
import { Btn, Field, Modal, Status } from '../components/ui'

export default function AdminRooms() {
  const { data, loading, error, reload } = useFetch(ENDPOINTS.rooms)
  const [form, setForm] = useState(null) // null = closed, {} = add, {id,...} = edit
  const [file, setFile] = useState(null)
  const [err, setErr] = useState('')

  async function save(e) {
    e.preventDefault(); setErr('')
    // FormData because add-rooms.php / update-rooms.php read the photo from $_FILES
    const fd = new FormData()
    fd.append('room_number', form.room_number); fd.append('room_type', form.room_type); fd.append('price_per_night', form.price_per_night)
    if (form.id) fd.append('id', form.id)
    if (file) fd.append('image_url', file)
    try { await api.post(form.id ? ENDPOINTS.updateRoom : ENDPOINTS.addRoom, fd); setForm(null); setFile(null); reload() }
    catch (x) { setErr(x.message) }
  }
  async function remove(r) {
    if (!confirm(`Delete room ${r.room_number}?`)) return
    try { await api.post(ENDPOINTS.deleteRoom, { id: r.room_id }); setErr(''); reload() } catch (x) { setErr(x.message) }
  }
  const close = () => { setForm(null); setFile(null); setErr('') }
  const set = (k) => (v) => setForm({ ...form, [k]: v })

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between"><h1 className="text-2xl font-bold">Rooms</h1><Btn onClick={() => setForm({})}>+ Add room</Btn></div>
      <Status loading={loading} error={error || (!form && err)} />
      {!loading && !error && (
        <div className="overflow-x-auto rounded-xl border border-slate-200 bg-white">
          <table className="w-full min-w-[520px] text-left text-sm">
            <thead className="text-slate-500"><tr>{['Photo', 'Room no.', 'Type', 'Price / night', 'Action'].map((h) => <th key={h} className="px-4 py-3 font-semibold">{h}</th>)}</tr></thead>
            <tbody>
              {asList(data).map((r) => (
                <tr key={r.room_id} className="border-t border-slate-100">
                  <td className="px-4 py-2">{r.image_url ? <img src={imgUrl(r.image_url)} alt="" className="h-10 w-14 rounded object-cover" /> : <span className="text-slate-300">🖼</span>}</td>
                  <td className="px-4 py-3">{r.room_number}</td><td className="px-4 py-3">{r.room_type}</td><td className="px-4 py-3">${r.price_per_night}</td>
                  <td className="space-x-3 whitespace-nowrap px-4 py-3">
                    <button className="font-medium underline" onClick={() => setForm({ id: r.room_id, room_number: r.room_number, room_type: r.room_type, price_per_night: r.price_per_night })}>Edit</button>
                    <button className="font-medium text-red-600 underline" onClick={() => remove(r)}>Delete</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
      {form && (
        <Modal title={form.id ? 'Edit room' : 'Add room'} onClose={close}>
          <form onSubmit={save} className="space-y-3">
            <Field label="Room number" value={form.room_number} onChange={set('room_number')} />
            <Field label="Room type (e.g. Standard, Deluxe, Suite)" value={form.room_type} onChange={set('room_type')} />
            <Field label="Price per night" f={{ type: 'number' }} value={form.price_per_night} onChange={set('price_per_night')} />
            <label className="block text-sm font-medium">Photo (optional)<input type="file" accept="image/*" onChange={(e) => setFile(e.target.files[0])} className="mt-1 block w-full text-sm" /></label>
            {err && <p className="rounded-lg bg-red-50 p-3 text-sm text-red-700">{err}</p>}
            <div className="flex justify-end gap-2"><Btn type="button" ghost onClick={close}>Cancel</Btn><Btn type="submit">Save</Btn></div>
          </form>
        </Modal>
      )}
    </div>
  )
}
