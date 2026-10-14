const tone = { upcoming: 'bg-blue-100 text-blue-700', completed: 'bg-emerald-100 text-emerald-700', cancelled: 'bg-red-100 text-red-700' }
export const Badge = ({ v }) => <span className={`rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize ${tone[String(v).toLowerCase()] || 'bg-slate-100 text-slate-700'}`}>{v}</span>
export const Btn = ({ ghost, className = '', ...p }) => (
  <button {...p} className={`rounded-lg px-4 py-2 text-sm font-semibold ${ghost ? 'border border-slate-300 bg-white text-slate-700' : 'bg-slate-700 text-white hover:bg-slate-800'} disabled:opacity-50 ${className}`} />
)
export const Stat = ({ label, value }) => (
  <div className="rounded-xl border border-slate-200 bg-white p-4"><p className="text-sm text-slate-500">{label}</p><p className="mt-1 text-3xl font-bold">{value}</p></div>
)
export const Field = ({ label, f, value, onChange }) => {
  const cls = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm'
  const common = { value: value ?? '', onChange: (e) => onChange(e.target.value), required: !f?.optional, className: cls }
  return (
    <label className="block text-sm font-medium">{label}
      <div className="mt-1">
        {f?.options ? <select {...common}><option value="">Select…</option>{f.options.map((o) => <option key={o}>{o}</option>)}</select>
          : f?.type === 'textarea' ? <textarea rows="3" {...common} /> : <input type={f?.type || 'text'} {...common} />}
      </div>
    </label>
  )
}
export const Modal = ({ title, onClose, children }) => (
  <div className="fixed inset-0 z-40 grid place-items-center bg-slate-900/50 p-4" onClick={onClose}>
    <div role="dialog" aria-modal="true" className="max-h-[92vh] w-full max-w-md overflow-auto rounded-2xl bg-white p-5" onClick={(e) => e.stopPropagation()}>
      <div className="mb-4 flex items-center justify-between"><h2 className="text-lg font-bold">{title}</h2><button onClick={onClose} aria-label="Close" className="text-slate-500">✕</button></div>
      {children}
    </div>
  </div>
)
export const Status = ({ loading, error }) => loading ? <p className="py-6 text-slate-500">Loading…</p> : error ? <p className="rounded-lg bg-red-50 p-3 text-sm text-red-700">{error}</p> : null
