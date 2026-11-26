import { useLayoutEffect, useMemo, useRef, useState } from 'react'
import { Head } from '@inertiajs/react'
import { gsap } from 'gsap'
import { Flip } from 'gsap/Flip'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { prefersReducedMotion } from '../motion/config'
import PageHero from '../components/PageHero'
import Lightbox from '../components/Lightbox'
import SectionHeading from '../components/ui/SectionHeading'
import FilterBar from '../components/ui/FilterBar'
import './Gallery.css'
import { useT } from '../lib/i18n'

gsap.registerPlugin(Flip, ScrollTrigger)

export default function Gallery() {
  const t = useT()
  const { galleryPage } = useContent()
  const allLabel = galleryPage.filters[0] // "All" in the current language
  const [filter, setFilter] = useState(allLabel)
  const [openIndex, setOpenIndex] = useState(-1)
  const gridRef = useRef(null)
  const flipState = useRef(null)

  const shown = (item) => filter === allLabel || item.category === filter
  const items = useMemo(() => galleryPage.items.filter(shown), [filter, galleryPage.items]) // eslint-disable-line react-hooks/exhaustive-deps
  const counts = useMemo(() => {
    const c = { [allLabel]: galleryPage.items.length }
    galleryPage.items.forEach((item) => { c[item.category] = (c[item.category] ?? 0) + 1 })
    return c
  }, [galleryPage.items, allLabel])

  const choose = (next) => {
    if (gridRef.current && !prefersReducedMotion()) flipState.current = Flip.getState(gridRef.current.children)
    setFilter(next)
    setOpenIndex(-1)
  }

  useLayoutEffect(() => {
    const state = flipState.current
    if (!state) return
    flipState.current = null
    Flip.from(state, {
      duration: 0.7,
      ease: 'power3.inOut',
      stagger: 0.02,
      absolute: true,
      onEnter: (els) => gsap.fromTo(els, { opacity: 0, scale: 0.9 }, { opacity: 1, scale: 1, duration: 0.6, delay: 0.1 }),
      onLeave: (els) => gsap.to(els, { opacity: 0, scale: 0.9, duration: 0.35 }),
      onComplete: () => ScrollTrigger.refresh(),
    })
  }, [filter])

  return (
    <>
      <Head title={t("Gallery")} />
      <PageHero title={galleryPage.hero.title} text={galleryPage.hero.text} crumb={t("Gallery")} image={(galleryPage.items[1] ?? galleryPage.items[0])?.src} />

      <section className="gallery section" aria-labelledby="gallery-title">
        <div className="container">
          <SectionHeading
            id="gallery-title"
            eyebrow={galleryPage.eyebrow}
            title={galleryPage.title}
            text={galleryPage.text}
          />
          <FilterBar
            options={galleryPage.filters}
            value={filter}
            onChange={choose}
            label={t("Filter photos by category")}
            counts={counts}
          />
          <p className="visually-hidden" role="status">{t('{count} photos shown', { count: items.length })}</p>
          <ul className="gallery__grid" ref={gridRef}>
            {galleryPage.items.map((item) => {
              const index = items.indexOf(item)
              return (
                <li className={`gallery__item${index < 0 ? ' is-filtered' : ''}`} key={item.src} data-flip-id={item.src}>
                  <button type="button" className="gallery__tile" onClick={() => setOpenIndex(index)} tabIndex={index < 0 ? -1 : 0}>
                    <img src={item.src} alt="" loading="lazy" decoding="async" />
                    <span className="gallery__meta">
                      <span className="gallery__category">{item.category}</span>
                      <span className="gallery__caption">{item.caption}</span>
                    </span>
                    <span className="gallery__zoom" aria-hidden="true">+</span>
                    <span className="visually-hidden">{t("Open larger photo")}</span>
                  </button>
                </li>
              )
            })}
          </ul>
        </div>
      </section>

      <Lightbox items={items} index={openIndex} onClose={() => setOpenIndex(-1)} onNavigate={setOpenIndex} />
    </>
  )
}
