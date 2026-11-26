import { useState } from 'react'
import { Link } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import { useT } from '../lib/i18n'
import SectionHeading from './ui/SectionHeading'
import Button from './ui/Button'
import { ArrowCircleRight } from './ui/Icons'
import './Services.css'

/** Business lines as expanding panels: hover, focus or tap opens one. */
export default function Services() {
  const t = useT()
  const { services, servicesPage } = useContent()
  const items = servicesPage?.items?.length ? servicesPage.items : services.items
  const [active, setActive] = useState(0)

  return (
    <section className="services section section--pink" id="services" aria-labelledby="services-title">
      <div className="container">
        <SectionHeading id="services-title" eyebrow={services.eyebrow} title={services.title} text={services.text} />
      </div>
      <div className="container-wide">
        <ul className="panels" data-reveal="up">
          {items.map((item, i) => {
            const open = i === active
            const chips = (item.groups ?? []).flatMap((g) => (g.title ? [g.title] : g.items)).slice(0, 4)
            return (
              <li
                key={item.id ?? item.title}
                className={`panel${open ? ' is-open' : ''}`}
                onPointerEnter={() => setActive(i)}
                onFocus={() => setActive(i)}
                onClick={() => setActive(i)}
              >
                {item.image && <img className="panel__img" src={item.image} alt="" loading="lazy" decoding="async" />}
                <div className="panel__shade" aria-hidden="true" />
                <span className="panel__num" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
                <div className="panel__body">
                  <h3 className="panel__title">
                    <Link href={`/services#${item.id ?? ''}`} className="panel__link">{item.title}</Link>
                  </h3>
                  <div className="panel__more">
                    {item.summary && <p className="panel__summary">{item.summary}</p>}
                    {chips.length > 0 && (
                      <ul className="panel__chips">{chips.map((c) => <li key={c}>{c}</li>)}</ul>
                    )}
                    <Link href={`/services#${item.id ?? ''}`} className="panel__cta" tabIndex={open ? 0 : -1}>
                      {t('Learn more')}<span className="visually-hidden">: {item.title}</span><ArrowCircleRight />
                    </Link>
                  </div>
                </div>
              </li>
            )
          })}
        </ul>
      </div>
      <div className="section-cta"><Button to="/services" icon>{services.cta}</Button></div>
    </section>
  )
}
