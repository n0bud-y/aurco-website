import { useLayoutEffect, useRef } from 'react'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useContent } from '../content/useContent'
import { prefersReducedMotion } from '../motion/config'
import { BulbIcon, GearHandIcon, ShieldHandIcon } from './ui/Icons'
import CountUp from './ui/CountUp'
import './WhyChooseUs.css'

gsap.registerPlugin(ScrollTrigger)

const icons = { bulb: BulbIcon, shield: ShieldHandIcon, gear: GearHandIcon }

function Stat({ value }) {
  const match = /^(\d[\d,]*)(.*)$/.exec(value ?? '')
  return match ? <CountUp value={Number(match[1].replace(/,/g, ''))} suffix={match[2]} /> : value
}

/** Sticky heading on one side, cards that stack on top of each other on the other. */
export default function WhyChooseUs() {
  const { whyChooseUs } = useContent()
  const listRef = useRef(null)

  // Pair each card with the photo that follows it.
  const cards = whyChooseUs.tiles.filter((tile) => tile.type === 'card')
  const images = whyChooseUs.tiles.filter((tile) => tile.type === 'image')

  // Earlier cards shrink and dim as the next one slides over them.
  useLayoutEffect(() => {
    if (prefersReducedMotion() || !listRef.current) return undefined
    const mm = gsap.matchMedia()
    mm.add('(min-width: 1024px)', () => {
      const items = gsap.utils.toArray(listRef.current.querySelectorAll('.stack-card'))
      items.forEach((card, i) => {
        const next = items[i + 1]
        if (!next) return
        gsap.to(card.querySelector('.stack-card__inner'), {
          scale: 0.92, opacity: 0.45, ease: 'none',
          scrollTrigger: { trigger: next, start: 'top 85%', end: 'top 25%', scrub: true },
        })
      })
    })
    return () => mm.revert()
  }, [cards.length])

  return (
    <section className="why section" id="expertise" aria-labelledby="why-title">
      <div className="container why__layout">
        <div className="why__intro">
          <div className="why__sticky">
            <p className="eyebrow" data-reveal="up">{whyChooseUs.eyebrow}</p>
            <h2 className="heading-2" id="why-title" data-anim="lines">{whyChooseUs.title}</h2>
            <p className="why__text" data-reveal="up" data-reveal-delay="150">{whyChooseUs.text}</p>
            <p className="why__count" aria-hidden="true">
              <span>{String(cards.length).padStart(2, '0')}</span>
            </p>
          </div>
        </div>

        <ol className="why__stack" ref={listRef}>
          {cards.map((card, i) => {
            const Icon = icons[card.icon] ?? BulbIcon
            const image = images[i]
            return (
              <li className="stack-card" key={card.title} style={{ '--i': i }}>
                <article className="stack-card__inner">
                  {image && (
                    <div className="stack-card__media">
                      <img src={image.image} alt="" loading="lazy" decoding="async" />
                      <span className="stack-card__index">{String(i + 1).padStart(2, '0')}</span>
                    </div>
                  )}
                  <div className="stack-card__body">
                    <Icon className="stack-card__icon" />
                    <h3 className="stack-card__title">{card.title}</h3>
                    <ul className="stack-card__list">
                      {card.points.map((point) => <li key={point}>{point}</li>)}
                    </ul>
                    {card.stat && (
                      <div className="stack-card__stat">
                        <span className="stack-card__number"><Stat value={card.stat} /></span>
                        <span className="stack-card__label">{card.statLabel}</span>
                      </div>
                    )}
                  </div>
                </article>
              </li>
            )
          })}
        </ol>
      </div>
    </section>
  )
}
