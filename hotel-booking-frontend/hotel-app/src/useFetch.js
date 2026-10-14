import { useCallback, useEffect, useState } from 'react'
import { api } from './api'
// Loads a GET endpoint and gives back { data, loading, error, reload }.
export function useFetch(path) {
  const [s, set] = useState({ data: null, loading: true, error: '' })
  const load = useCallback(() => {
    set((x) => ({ ...x, loading: true }))
    api.get(path).then((data) => set({ data, loading: false, error: '' })).catch((e) => set({ data: null, loading: false, error: e.message }))
  }, [path])
  useEffect(load, [load])
  return { ...s, reload: load }
}
