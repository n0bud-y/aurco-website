import { useEffect, useRef, useState } from 'react'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { useLocale, useT } from '../lib/i18n'
import { supportsWebGL } from '../lib/webgl'
import { introReady } from '../motion/intro'
import { prefersReducedMotion } from '../motion/config'
import Button from './ui/Button'
import CountUp from './ui/CountUp'
import Header from './Header'
import './Hero.css'

gsap.registerPlugin(ScrollTrigger)

function Figure({ value }) {
  const match = /^(\d[\d,]*)(.*)$/.exec(value ?? '')
  return match ? <CountUp value={Number(match[1].replace(/,/g, ''))} suffix={match[2]} duration={2200} /> : value
}

export default function Hero() {
  const t = useT()
  const locale = useLocale()
  const { hero, stats } = useContent()
  const cardRef = useRef(null)
  const sceneRef = useRef(null)
  const factsRef = useRef(null)
  const [has3d, setHas3d] = useState(false)

  // Three key figures along the bottom of the hero: projects, safe man-hours, equipment.
  const facts = [0, 3, 7].map((i) => stats?.items?.[i]).filter(Boolean)

  // The 3D steel structure, loaded only on the home page and only where WebGL works.
  useEffect(() => {
    if (!supportsWebGL()) return undefined
    let cancelled = false
    let api = null
    let trigger = null

    import('../three/heroScene').then(({ mountHeroScene }) => {
      if (cancelled || !sceneRef.current) return
      api = mountHeroScene(sceneRef.current, {
        rtl: locale === 'ar',
        reduced: prefersReducedMotion(),
        compact: window.matchMedia('(max-width: 900px)').matches,
      })
      setHas3d(true)
      introReady.then(() => { if (!cancelled) api.build() })
      trigger = ScrollTrigger.create({
        trigger: cardRef.current,
        start: 'top top',
        end: 'bottom top',
        scrub: true,
        onUpdate: (self) => api.setScroll(self.progress),
      })
    })

    return () => {
      cancelled = true
      trigger?.kill()
      api?.dispose()
    }
  }, [locale])

  // Figures slide up once the intro loader has finished.
  useEffect(() => {
    if (prefersReducedMotion() || !factsRef.current) return undefined
    const items = factsRef.current.children
    gsap.set(items, { y: 30, opacity: 0 })
    let tween
    introReady.then(() => {
      tween = gsap.to(items, { y: 0, opacity: 1, duration: 0.9, stagger: 0.12, delay: 1.3, ease: 'power3.out' })
    })
    return () => tween?.kill()
  }, [])

  return (
    <section className="hero-frame hero" id="home" aria-labelledby="hero-title">
      <div className={`hero-frame__card hero__card${has3d ? ' has-3d' : ''}`} ref={cardRef}>
        {hero.image && <img className="hero-frame__bg hero__photo" src={hero.image} alt="" fetchPriority="high" />}
        <div className="hero__scene" ref={sceneRef} />
        <div className="hero__shade" aria-hidden="true" />
        <Header />
        <div className="hero__content">
          <p className="hero__eyebrow"><span className="hero__eyebrow-line" aria-hidden="true" />{hero.eyebrow}</p>
          <h1 className="hero__title" id="hero-title">{hero.title}</h1>
          <p className="hero__text">{hero.text}</p>
          <div className="hero__actions">
            <Button to="/contact" icon>{t('Get Started')}</Button>
            <Button to="/projects" variant="outline" icon>{t('View Projects')}</Button>
          </div>
        </div>
        {facts.length > 0 && (
          <ul className="hero__facts" ref={factsRef}>
            {facts.map((fact) => (
              <li key={fact.label}>
                <span className="hero__fact-value"><Figure value={fact.value} /></span>
                <span className="hero__fact-label">{fact.label}</span>
              </li>
            ))}
          </ul>
        )}
        <div className="hero__scroll" aria-hidden="true"><span /></div>
      </div>
    </section>
  )
}
