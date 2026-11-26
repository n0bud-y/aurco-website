import { useEffect, useRef, useState } from 'react'
import { useT } from '../lib/i18n'
import './FleetChart.css'

/**
 * Horizontal bars: units per equipment type, largest first.
 * Single series → one brand hue, value at the bar tip, no legend; hover shows the share of the fleet.
 */
export default function FleetChart({ items, title }) {
  const t = useT()
  const ref = useRef(null)
  const [shown, setShown] = useState(false)
  const [hover, setHover] = useState(-1)

  const rows = items
    .map((item) => ({ name: item.name, count: Number(String(item.count).replace(/[^\d.]/g, '')) || 0 }))
    .filter((r) => r.count > 0)
    .sort((a, b) => b.count - a.count)
  const max = Math.max(...rows.map((r) => r.count), 1)
  const total = rows.reduce((sum, r) => sum + r.count, 0)

  // Bars grow the first time the chart scrolls into view.
  useEffect(() => {
    const el = ref.current
    if (!el) return undefined
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { setShown(true); return undefined }
    const io = new IntersectionObserver(([entry]) => { if (entry.isIntersecting) { setShown(true); io.disconnect() } }, { threshold: 0.2 })
    io.observe(el)
    return () => io.disconnect()
  }, [])

  return (
    <figure className={`fleet-chart${shown ? ' is-shown' : ''}`} ref={ref}>
      <figcaption className="fleet-chart__caption">
        <span>{title}</span>
        <span className="fleet-chart__total">{t('{count} units in total', { count: total })}</span>
      </figcaption>
      <ul className="fleet-chart__rows" onPointerLeave={() => setHover(-1)}>
        {rows.map((row, i) => {
          const share = Math.round((row.count / total) * 100)
          return (
            <li
              key={row.name}
              className={`fleet-chart__row${hover === i ? ' is-hover' : ''}${hover > -1 && hover !== i ? ' is-dim' : ''}`}
              onPointerEnter={() => setHover(i)}
              style={{ '--w': `${(row.count / max) * 100}%`, '--d': `${i * 45}ms` }}
            >
              <span className="fleet-chart__name">{row.name}</span>
              <span className="fleet-chart__track">
                <span className="fleet-chart__bar" />
                <span className="fleet-chart__value">{row.count}</span>
              </span>
              {hover === i && (
                <span className="fleet-chart__tip" role="status">{t('{count} units · {share}% of the fleet', { count: row.count, share })}</span>
              )}
            </li>
          )
        })}
      </ul>
    </figure>
  )
}
