import { useEffect, useRef, useState } from 'react'
import { useT } from '../lib/i18n'
import './SafetyChart.css'

const num = (v) => Number(String(v ?? '').replace(/[^\d.]/g, '')) || 0
const compact = (n) => (n >= 1e6 ? `${(n / 1e6).toFixed(n >= 1e7 ? 0 : 2)}M` : n >= 1e3 ? `${Math.round(n / 1e3)}K` : String(n))

/**
 * Columns: man-hours worked per year (oldest → newest).
 * Single series in the brand red; value on each cap; hover shows that year's incident counts.
 * The full table below the chart is the accessible view of the same data.
 */
export default function SafetyChart({ stats }) {
  const t = useT()
  const ref = useRef(null)
  const [shown, setShown] = useState(false)
  const [hover, setHover] = useState(-1)

  const rows = [...stats].map((s) => ({ ...s, hours: num(s.manhours) })).sort((a, b) => num(a.year) - num(b.year))
  const max = Math.max(...rows.map((r) => r.hours), 1)
  // Clean axis: round the top up to the next 500,000.
  const top = Math.ceil(max / 5e5) * 5e5
  const ticks = [0, top / 2, top]
  const totalHours = rows.reduce((sum, r) => sum + r.hours, 0)
  const zeroLti = rows.every((r) => num(r.lti) === 0)
  const zeroFatal = rows.every((r) => num(r.fatalities) === 0)

  useEffect(() => {
    const el = ref.current
    if (!el) return undefined
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { setShown(true); return undefined }
    const io = new IntersectionObserver(([e]) => { if (e.isIntersecting) { setShown(true); io.disconnect() } }, { threshold: 0.3 })
    io.observe(el)
    return () => io.disconnect()
  }, [])

  if (!rows.length) return null

  return (
    <div className="safety-board" ref={ref}>
      <figure className={`safety-chart${shown ? ' is-shown' : ''}`}>
        <figcaption className="safety-chart__caption">{t('Man-hours worked per year')}</figcaption>
        <div className="safety-chart__plot" onPointerLeave={() => setHover(-1)}>
          <div className="safety-chart__grid" aria-hidden="true">
            {ticks.slice().reverse().map((tick) => (
              <span key={tick} className="safety-chart__tick"><span>{compact(tick)}</span></span>
            ))}
          </div>
          <ul className="safety-chart__cols" style={{ "--n": rows.length }}>
            {rows.map((r, i) => (
              <li
                key={r.year}
                className={`safety-chart__col${hover === i ? ' is-hover' : ''}${hover > -1 && hover !== i ? ' is-dim' : ''}`}
                style={{ '--h': `${(r.hours / top) * 100}%`, '--d': `${i * 90}ms` }}
                onPointerEnter={() => setHover(i)}
                tabIndex={0}
                onFocus={() => setHover(i)}
                onBlur={() => setHover(-1)}
                aria-label={`${r.year}: ${r.manhours}`}
              >
                <span className="safety-chart__bar">
                  <span className="safety-chart__value">{compact(r.hours)}</span>
                </span>
                <span className="safety-chart__year">{r.year}</span>
                {hover === i && (
                  <span className="safety-chart__tip" role="status">
                    <strong>{r.year}</strong>
                    <span>{t('Man-hours worked')}: {r.manhours}</span>
                    <span>{t('Lost time incidents (LTI)')}: {r.lti}</span>
                    <span>{t('First aid injury / illness cases')}: {r.fai}</span>
                    <span>{t('Near misses')}: {r.nearMiss}</span>
                  </span>
                )}
              </li>
            ))}
          </ul>
        </div>
      </figure>

      <div className="safety-hero">
        <p className="safety-hero__big">{compact(totalHours)}</p>
        <p className="safety-hero__label">{t('man-hours worked, {from}–{to}', { from: rows[0].year, to: rows[rows.length - 1].year })}</p>
        <ul className="safety-hero__flags">
          {zeroLti && <li><span className="safety-hero__zero">0</span>{t('Lost time incidents (LTI)')}</li>}
          {zeroFatal && <li><span className="safety-hero__zero">0</span>{t('On-job fatalities')}</li>}
        </ul>
      </div>
    </div>
  )
}
