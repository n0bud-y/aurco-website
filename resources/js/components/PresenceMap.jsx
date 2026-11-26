import { useEffect, useRef, useState } from 'react'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { useT } from '../lib/i18n'
import { supportsWebGL } from '../lib/webgl'
import { prefersReducedMotion } from '../motion/config'
import outline from '../data/ksa-outline.json'
import { MapPin } from './ui/Icons'
import './PresenceMap.css'

/** Regional presence on a 3D map of the Kingdom (falls back to the profile's map image). */
export default function PresenceMap() {
  const t = useT()
  const { aboutPage } = useContent()
  const presence = aboutPage?.presence
  const locations = (presence?.locations ?? []).filter((l) => l.lat && l.lng)
  const stageRef = useRef(null)
  const labelRefs = useRef([])
  const apiRef = useRef(null)
  const [has3d, setHas3d] = useState(false)
  const [active, setActive] = useState(-1)

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

  const focus = (i) => {
    setActive(i)
    apiRef.current?.highlight(i)
  }

  if (!presence) return null

  return (
    <section className="presence section" id="presence" aria-labelledby="presence-title">
      <div className="container presence__grid">
        <div className="presence__body">
          <p className="eyebrow" data-reveal="up">{t('Across the Kingdom')}</p>
          <h2 className="heading-2 presence__title" id="presence-title" data-anim="lines">{presence.title}</h2>
          <p className="presence__text" data-reveal="up" data-reveal-delay="120">{presence.text}</p>
          <ul className="presence__list" onPointerLeave={() => focus(-1)}>
            {locations.map((loc, i) => (
              <li key={loc.name} data-reveal="up" data-reveal-delay={160 + i * 60}>
                <button
                  type="button"
                  className={`presence__place${loc.hq ? ' is-hq' : ''}${active === i ? ' is-active' : ''}`}
                  onPointerEnter={() => focus(i)}
                  onFocus={() => focus(i)}
                  onBlur={() => focus(-1)}
                >
                  <MapPin />
                  <span>{loc.name}</span>
                  {loc.note && <small>{loc.note}</small>}
                </button>
              </li>
            ))}
          </ul>
        </div>

        <div className={`presence__stage${has3d ? ' has-3d' : ''}`} data-reveal="zoom">
          <div className="presence__canvas" ref={stageRef} />
          {has3d && (
            <div className="presence__labels" aria-hidden="true">
              {locations.map((loc, i) => (
                <span
                  key={loc.name}
                  ref={(el) => { labelRefs.current[i] = el }}
                  className={`presence__label${loc.hq ? ' is-hq' : ''}${active === i ? ' is-active' : ''}`}
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
