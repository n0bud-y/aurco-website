import { useEffect, useMemo, useRef, useState } from 'react'
import { Link } from '@inertiajs/react'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { useT } from '../lib/i18n'
import { supportsWebGL } from '../lib/webgl'
import { prefersReducedMotion } from '../motion/config'
import outline from '../data/ksa-outline.json'
import { ArrowCircleRight, CloseIcon, MapPin } from './ui/Icons'
import './PresenceMap.css'

/** Regional presence on a 3D map of the Kingdom (falls back to the profile's map image). */
export default function PresenceMap() {
  const t = useT()
  const { aboutPage, projectsPage } = useContent()
  const presence = aboutPage?.presence
  const locations = (presence?.locations ?? []).filter((l) => l.lat && l.lng)
  const stageRef = useRef(null)
  const labelRefs = useRef([])
  const apiRef = useRef(null)
  const [has3d, setHas3d] = useState(false)
  const [active, setActive] = useState(-1) // hovered / focused
  const [selected, setSelected] = useState(-1) // clicked

  // Projects whose location mentions each city (case studies first, then the track-record tables).
  const work = useMemo(() => {
    const all = [
      ...(projectsPage?.items ?? []).map((p) => ({ title: p.title, location: p.location, year: p.year })),
      ...(projectsPage?.trackRecord ?? []).flatMap((s) => s.rows.map((r) => ({ title: r.title, location: r.location, year: r.year }))),
    ]
    return locations.map((loc) => {
      const name = (loc.name ?? '').toLowerCase()
      const seen = new Set()
      return all.filter((p) => {
        const key = `${p.title}|${p.year}`
        if (!name || !(p.location ?? '').toLowerCase().includes(name) || seen.has(key)) return false
        seen.add(key)
        return true
      })
    })
  }, [locations, projectsPage])

  useEffect(() => {
    if (!locations.length || !supportsWebGL()) return undefined
    let cancelled = false
    let trigger = null
    import('../three/mapScene').then(({ mountMapScene }) => {
      if (cancelled || !stageRef.current) return
      apiRef.current = mountMapScene(stageRef.current, {
        outline,
        locations,
        labels: labelRefs.current,
        reduced: prefersReducedMotion(),
      })
      setHas3d(true)
      trigger = ScrollTrigger.create({
        trigger: stageRef.current,
        start: 'top 75%',
        once: true,
        onEnter: () => apiRef.current?.reveal(),
      })
    })
    return () => {
      cancelled = true
      trigger?.kill()
      apiRef.current?.dispose()
      apiRef.current = null
    }
    // Locations come from the CRM and do not change while the page is open.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  // Hover previews a pin; it falls back to the clicked city when the pointer leaves.
  const preview = (i) => {
    setActive(i)
    apiRef.current?.highlight(i > -1 ? i : selected)
  }
  const select = (i) => {
    const next = selected === i ? -1 : i
    setSelected(next)
    setActive(next)
    apiRef.current?.highlight(next)
  }
  const place = selected > -1 ? locations[selected] : null
  const placeWork = selected > -1 ? work[selected] : []

  if (!presence) return null

  return (
    <section className="presence section" id="presence" aria-labelledby="presence-title">
      <div className="container presence__grid">
        <div className="presence__body">
          <p className="eyebrow" data-reveal="up">{t('Across the Kingdom')}</p>
          <h2 className="heading-2 presence__title" id="presence-title" data-anim="lines">{presence.title}</h2>
          <p className="presence__text" data-reveal="up" data-reveal-delay="120">{presence.text}</p>
          <ul className="presence__list" onPointerLeave={() => preview(-1)}>
            {locations.map((loc, i) => (
              <li key={loc.name} data-reveal="up" data-reveal-delay={160 + i * 60}>
                <button
                  type="button"
                  className={`presence__place${loc.hq ? ' is-hq' : ''}${active === i || selected === i ? ' is-active' : ''}${selected === i ? ' is-selected' : ''}`}
                  aria-pressed={selected === i}
                  aria-controls="presence-card"
                  onClick={() => select(i)}
                  onPointerEnter={() => preview(i)}
                  onFocus={() => preview(i)}
                  onBlur={() => preview(-1)}
                >
                  <MapPin />
                  <span>{loc.name}</span>
                  {loc.note && <small>{loc.note}</small>}
                  {work[i]?.length > 0 && <span className="presence__count">{work[i].length}</span>}
                </button>
              </li>
            ))}
          </ul>

          <div className="presence__card-slot" id="presence-card" aria-live="polite">
            {place && (
              <article className="presence__card" key={place.name}>
                <button type="button" className="presence__card-close" onClick={() => select(selected)}>
                  <CloseIcon /><span className="visually-hidden">{t('Close')}</span>
                </button>
                <p className="presence__card-kicker">{place.note || t('Operating location')}</p>
                <h3 className="presence__card-title"><MapPin />{place.name}</h3>
                {placeWork.length > 0 ? (
                  <>
                    <p className="presence__card-stat"><strong>{placeWork.length}</strong> {t('projects delivered here')}</p>
                    <ul className="presence__card-list">
                      {placeWork.slice(0, 4).map((p) => (
                        <li key={`${p.title}-${p.year}`}><span>{p.title}</span>{p.year && <small>{p.year}</small>}</li>
                      ))}
                    </ul>
                    <Link href="/projects" className="presence__card-link">{t('View all projects')}<ArrowCircleRight /></Link>
                  </>
                ) : (
                  <p className="presence__card-empty">{t('AURCO teams and equipment serve clients in this region.')}</p>
                )}
              </article>
            )}
          </div>
        </div>

        <div className={`presence__stage${has3d ? ' has-3d' : ''}`} data-reveal="zoom">
          <div className="presence__canvas" ref={stageRef} />
          {has3d && (
            <div className="presence__labels" aria-hidden="true">
              {locations.map((loc, i) => (
                <span
                  key={loc.name}
                  ref={(el) => { labelRefs.current[i] = el }}
                  className={`presence__label${loc.hq ? ' is-hq' : ''}${active === i || selected === i ? ' is-active' : ''}`}
                >
                  <span>{loc.name}</span>
                </span>
              ))}
            </div>
          )}
          {!has3d && presence.image && (
            <img className="presence__fallback" src={presence.image} alt={t('Map of AURCO locations: {names}', { names: locations.map((l) => l.name).join('، ') })} loading="lazy" />
          )}
        </div>
      </div>
    </section>
  )
}
