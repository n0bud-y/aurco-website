import { useCallback, useEffect, useRef, useState } from 'react'
import { gsap } from 'gsap'
import { useContent } from '../content/useContent'
import { prefersReducedMotion } from '../motion/config'
import SectionHeading from './ui/SectionHeading'
import { ArrowRightLong, Star } from './ui/Icons'
import { useT } from '../lib/i18n'
import './Testimonials.css'

const DURATION = 8000

/** One large quote at a time, cycling automatically; pauses while hovered or focused. */
export default function Testimonials() {
  const t = useT()
  const { testimonials } = useContent()
  const items = testimonials.items
  const [index, setIndex] = useState(0)
  const [paused, setPaused] = useState(false)
  const quoteRef = useRef(null)
  const barRef = useRef(null)

  const go = useCallback((step) => setIndex((i) => (i + step + items.length) % items.length), [items.length])

  // Animate each new quote in.
  useEffect(() => {
    if (prefersReducedMotion() || !quoteRef.current) return
    gsap.fromTo(quoteRef.current.children, { y: 34, opacity: 0 }, { y: 0, opacity: 1, duration: 0.9, stagger: 0.08, ease: 'power3.out' })
  }, [index])

  // Progress bar drives the auto-advance.
  useEffect(() => {
    if (items.length < 2 || prefersReducedMotion() || !barRef.current) return undefined
    const tween = gsap.fromTo(barRef.current, { scaleX: 0 }, {
      scaleX: 1, duration: DURATION / 1000, ease: 'none', onComplete: () => go(1),
    })
    if (paused) tween.pause()
    return () => tween.kill()
  }, [index, paused, go, items.length])

  if (!items.length) return null
  const item = items[index]

  return (
    <section className="testimonials section" id="testimonials" aria-labelledby="testimonials-title">
      <div className="container">
        <SectionHeading id="testimonials-title" eyebrow={testimonials.eyebrow} title={testimonials.title} text={testimonials.text} />

        <div
          className="quote-stage"
          data-reveal="up"
          onPointerEnter={() => setPaused(true)}
          onPointerLeave={() => setPaused(false)}
          onFocus={() => setPaused(true)}
          onBlur={() => setPaused(false)}
        >
          <span className="quote-stage__mark" aria-hidden="true">“</span>
          <figure className="quote-stage__figure" ref={quoteRef} aria-live="polite">
            {item.rating > 0 && (
              <div className="testimonial__stars" role="img" aria-label={t('Rated {rating} out of 5', { rating: item.rating })}>
                {Array.from({ length: item.rating }, (_, i) => <Star key={i} />)}
              </div>
            )}
            <blockquote className="quote-stage__quote"><p>{item.quote}</p></blockquote>
            <figcaption className="quote-stage__author">
              {item.avatar && <img src={item.avatar} alt="" width="56" height="56" loading="lazy" />}
              <span>
                <span className="quote-stage__name">{item.name}</span>
                <span className="quote-stage__role">{item.role}</span>
              </span>
            </figcaption>
          </figure>

          {items.length > 1 && (
            <div className="quote-stage__controls">
              <div className="quote-stage__dots" role="tablist" aria-label={t('Testimonials')}>
                {items.map((q, i) => (
                  <button
                    key={q.name}
                    type="button"
                    role="tab"
                    aria-selected={i === index}
                    className={`quote-stage__dot${i === index ? ' is-active' : ''}`}
                    onClick={() => setIndex(i)}
                  >
                    <span className="visually-hidden">{q.name}</span>
                    {i === index && <span className="quote-stage__bar" ref={barRef} />}
                  </button>
                ))}
              </div>
              <div className="quote-stage__arrows">
                <button type="button" className="quote-stage__arrow is-prev" onClick={() => go(-1)}>
                  <ArrowRightLong /><span className="visually-hidden">{t('Previous quote')}</span>
                </button>
                <button type="button" className="quote-stage__arrow" onClick={() => go(1)}>
                  <ArrowRightLong /><span className="visually-hidden">{t('Next quote')}</span>
                </button>
              </div>
            </div>
          )}
        </div>
      </div>
    </section>
  )
}
