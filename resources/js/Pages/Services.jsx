import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import CtaBanner from '../components/CtaBanner'
import SectionHeading from '../components/ui/SectionHeading'
import Button from '../components/ui/Button'
import '../styles/inner.css'
import './Services.css'
import { useT } from '../lib/i18n'

function ServiceBlock({ item, index }) {
  const t = useT()
  const titled = item.groups.filter((g) => g.title)
  const loose = item.groups.filter((g) => !g.title).flatMap((g) => g.items)

  return (
    <section className={`service-block section${index % 2 ? ' section--pink' : ''}`} id={item.id} aria-labelledby={`${item.id}-title`}>
      <div className={`container split${index % 2 ? ' split--reverse' : ''}`}>
        <div className="split__body" data-reveal={index % 2 ? 'right' : 'left'}>
          <p className="service-block__num">{String(index + 1).padStart(2, '0')}</p>
          <h2 className="heading-2" id={`${item.id}-title`}>{item.title}</h2>
          {item.summary && <p className="lead">{item.summary}</p>}
          {loose.length > 0 && <ul className="tick-list service-block__loose">{loose.map((t) => <li key={t}>{t}</li>)}</ul>}
          <div className="service-block__cta"><Button to="/contact" icon>{t("Request a Quote")}</Button></div>
        </div>
        {item.image && (
          <div className="split__media service-block__media" data-reveal={index % 2 ? 'left' : 'right'}>
            <img src={item.image} alt="" loading="lazy" />
          </div>
        )}
      </div>
      {titled.length > 0 && (
        <div className="container">
          <div className={`service-groups service-groups--${Math.min(titled.length, 3)}`}>
            {titled.map((group, i) => (
              <div className="info-card service-group" key={group.title} data-reveal="up" data-reveal-delay={(i % 3) * 80}>
                <h3 className="info-card__title">{group.title}</h3>
                <ul className="tick-list">{group.items.map((t) => <li key={t}>{t}</li>)}</ul>
              </div>
            ))}
          </div>
        </div>
      )}
    </section>
  )
}

export default function Services() {
  const t = useT()
  const { servicesPage: page } = useContent()

  return (
    <>
      <Head title={t("Services")} />
      <PageHero title={page.hero.title} text={page.hero.text} crumb={t("Services")} />

      <section className="section services-intro" aria-labelledby="services-intro-title">
        <div className="container">
          <SectionHeading id="services-intro-title" eyebrow={page.eyebrow} title={page.title} text={page.text} />
          <nav className="services-jump" aria-label={t("Jump to a business line")}>
            {page.items.map((item, i) => (
              <a key={item.id} href={`#${item.id}`} className="services-jump__link" data-reveal="up" data-reveal-delay={i * 70}>
                <span>{String(i + 1).padStart(2, '0')}</span>{item.title}
              </a>
            ))}
          </nav>
        </div>
      </section>

      {page.items.map((item, i) => <ServiceBlock key={item.id} item={item} index={i} />)}

      {page.fleet.items.length > 0 && (
        <section className="section fleet" id="fleet" aria-labelledby="fleet-title">
          <div className="container">
            <SectionHeading id="fleet-title" eyebrow={t("Rental equipment")} title={page.fleet.title} text={page.fleet.text} />
            <ul className="fleet__grid">
              {page.fleet.items.map((unit, i) => (
                <li className="fleet__item" key={unit.name} data-reveal="up" data-reveal-delay={(i % 5) * 50}>
                  <span className="fleet__count">{unit.count}</span>
                  <span className="fleet__name">{unit.name}</span>
                </li>
              ))}
            </ul>
          </div>
        </section>
      )}

      <CtaBanner />
    </>
  )
}
