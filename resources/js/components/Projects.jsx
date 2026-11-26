import { useLayoutEffect, useRef } from 'react'
import { Link } from '@inertiajs/react'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { useLocale, useT } from '../lib/i18n'
import { prefersReducedMotion } from '../motion/config'
import { ArrowCircleRight, MapPin } from './ui/Icons'
import './Projects.css'

gsap.registerPlugin(ScrollTrigger)

const SHOWN = 6

/** Featured projects as a horizontal reel, pinned while the page scrolls (swipe on phones). */
export default function Projects() {
  const t = useT()
  const locale = useLocale()
  const { projects, projectsPage } = useContent()
  const sectionRef = useRef(null)
  const trackRef = useRef(null)
  const barRef = useRef(null)

  const items = (projectsPage?.items?.length ? projectsPage.items : projects.items).slice(0, SHOWN)
  const total = projectsPage?.items?.length ?? items.length

  useLayoutEffect(() => {
    if (prefersReducedMotion() || !trackRef.current) return undefined
    const mm = gsap.matchMedia()
    mm.add('(min-width: 1024px)', () => {
      const track = trackRef.current
      const dir = locale === 'ar' ? 1 : -1
      const distance = () => Math.max(0, track.scrollWidth - track.parentElement.clientWidth)
      const tween = gsap.to(track, {
        x: () => dir * distance(),
        ease: 'none',
        scrollTrigger: {
          trigger: sectionRef.current,
          start: 'top top',
          end: () => `+=${distance()}`,
          pin: true,
          scrub: 0.6,
          invalidateOnRefresh: true,
          onUpdate: (self) => { if (barRef.current) barRef.current.style.transform = `scaleX(${self.progress})` },
        },
      })
      // Each card's photo drifts a little inside its frame while the reel moves.
      track.querySelectorAll('.reel-card__img').forEach((img) => {
        gsap.fromTo(img, { xPercent: -6 * dir }, {
          xPercent: 6 * dir, ease: 'none',
          scrollTrigger: { trigger: img.closest('.reel-card'), containerAnimation: tween, start: 'left right', end: 'right left', scrub: true },
        })
      })
      ScrollTrigger.refresh()
    })
    return () => mm.revert()
  }, [locale, items.length])

  return (
    <section className="reel section" id="projects" aria-labelledby="projects-title" ref={sectionRef}>
      <div className="container reel__head">
        <div>
          <p className="eyebrow">{projects.eyebrow}</p>
          <h2 className="heading-2 reel__title" id="projects-title">{projects.title}</h2>
        </div>
        <p className="reel__text">{projects.text}</p>
      </div>

      <div className="reel__viewport">
        <ul className="reel__track" ref={trackRef}>
          {items.map((item, i) => (
            <li className="reel-card" key={item.title}>
              <Link href="/projects" className="reel-card__link">
                <div className="reel-card__media">
                  {item.image && <img className="reel-card__img" src={item.image} alt="" loading="lazy" decoding="async" />}
                </div>
                <div className="reel-card__shade" aria-hidden="true" />
                <span className="reel-card__index" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
                {(item.category || item.tag) && <span className="reel-card__tag">{item.category ?? item.tag}</span>}
                <div className="reel-card__body">
                  <h3 className="reel-card__title">{item.title}</h3>
                  <p className="reel-card__meta">
                    {(item.location || item.meta) && <span><MapPin />{item.location ?? item.meta}</span>}
                    {item.client && <span>{item.client}</span>}
                    {item.year && <span>{item.year}</span>}
                  </p>
                </div>
              </Link>
            </li>
          ))}
          <li className="reel-card reel-card--cta">
            <Link href="/projects" className="reel-card__link">
              <span className="reel-card__count">{total}</span>
              <span className="reel-card__cta-label">{t('View all projects')}</span>
              <ArrowCircleRight className="reel-card__cta-icon" />
            </Link>
          </li>
        </ul>
      </div>

      <div className="container reel__progress" aria-hidden="true"><span ref={barRef} /></div>
    </section>
  )
}
