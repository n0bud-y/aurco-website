import { useLayoutEffect, useRef } from 'react'
import { Link } from '@inertiajs/react'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { prefersReducedMotion } from '../motion/config'
import { introReady } from '../motion/intro'
import Header from './Header'
import './PageHero.css'
import { useLocale, useT } from '../lib/i18n'

gsap.registerPlugin(ScrollTrigger)

/**
 * Banner at the top of every inner page: navy card, a project photo cut on a
 * diagonal, a moving blueprint grid and a large outlined title that drifts on scroll.
 */
export default function PageHero({ title, text, crumb, image, children }) {
  const t = useT()
  const rtl = useLocale() === 'ar'
  const { hero } = useContent()
  const cardRef = useRef(null)
  const photo = image || hero.image

  useLayoutEffect(() => {
    if (prefersReducedMotion() || !cardRef.current) return undefined
    const card = cardRef.current
    const ctx = gsap.context(() => {
      const media = card.querySelector('.page-hero__media')
      const img = card.querySelector('.page-hero__img')
      if (media) {
        // The photo wipes in from the outer edge (right in English, left in Arabic).
        const closed = rtl ? 'polygon(0 0, 0 0, 0 100%, 0 100%)' : 'polygon(100% 0, 100% 0, 100% 100%, 100% 100%)'
        const open = rtl ? 'polygon(0 0, 78% 0, 100% 100%, 0 100%)' : 'polygon(22% 0, 100% 0, 100% 100%, 0% 100%)'
        gsap.set(media, { clipPath: closed })
        introReady.then(() => {
          gsap.to(media, { clipPath: open, duration: 1.5, ease: 'power4.inOut', delay: 0.2 })
          gsap.fromTo(img, { scale: 1.3 }, { scale: 1.06, duration: 2.4, ease: 'power3.out', delay: 0.2 })
        })
        gsap.to(img, { yPercent: 12, ease: 'none', scrollTrigger: { trigger: card, start: 'top top', end: 'bottom top', scrub: true } })
      }
      gsap.fromTo(card.querySelector('.page-hero__word'), { xPercent: 0 }, {
        xPercent: rtl ? 18 : -18, ease: 'none', scrollTrigger: { trigger: card, start: 'top top', end: 'bottom top', scrub: true },
      })
    }, card)
    return () => ctx.revert()
  }, [photo, rtl])

  return (
    <section className="hero-frame page-hero" aria-labelledby="page-title">
      <div className="hero-frame__card page-hero__card" ref={cardRef}>
        <div className="page-hero__grid" aria-hidden="true" />
        <span className="page-hero__word" aria-hidden="true">{title}</span>
        {photo && (
          <div className="page-hero__media" aria-hidden="true">
            <img className="page-hero__img" src={photo} alt="" fetchPriority="high" />
          </div>
        )}
        <Header />
        <div className="page-hero__content">
          <nav className="page-hero__crumbs" aria-label={t("Breadcrumb")}>
            <ol>
              <li><Link href="/">{t("Home")}</Link></li>
              <li aria-current="page">{crumb || title}</li>
            </ol>
          </nav>
          <h1 className="page-hero__title" id="page-title">{title}</h1>
          {text && <p className="page-hero__text">{text}</p>}
          {children && <div className="page-hero__actions">{children}</div>}
        </div>
        <span className="page-hero__bar" aria-hidden="true" />
      </div>
    </section>
  )
}
