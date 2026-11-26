import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import CtaBanner from '../components/CtaBanner'
import FleetChart from '../components/FleetChart'
import SectionHeading from '../components/ui/SectionHeading'
import SectionNav from '../components/ui/SectionNav'
import Button from '../components/ui/Button'
import '../styles/inner.css'
import './Services.css'
import { useT } from '../lib/i18n'

function ServiceBlock({ item, index }) {
  const t = useT()
  const titled = item.groups.filter((g) => g.title)
  const loose = item.groups.filter((g) => !g.title).flatMap((g) => g.items)
  const reverse = index % 2 === 1

  return (
    <section className={`service-block section${reverse ? ' section--pink' : ''}`} id={item.id} aria-labelledby={`${item.id}-title`}>
      <div className={`container split${reverse ? ' split--reverse' : ''}`}>
        <div className="split__body">
          <p className="service-block__num" aria-hidden="true">{String(index + 1).padStart(2, '0')}</p>
          <h2 className="heading-2 service-block__title" id={`${item.id}-title`} data-anim="lines">{item.title}</h2>
          {item.summary && <p className="lead" data-reveal="up" data-reveal-delay="120">{item.summary}</p>}
          {loose.length > 0 && <ul className="tick-list service-block__loose">{loose.map((x) => <li key={x}>{x}</li>)}</ul>}
          <div className="service-block__cta" data-reveal="up" data-reveal-delay="200"><Button to="/contact" icon>{t("Request a Quote")}</Button></div>
        </div>
        {item.image && (
          <div className="service-block__media">
            <span className="service-block__frame" aria-hidden="true" />
            <div className="service-block__photo" data-anim="clip">
              <img src={item.image} alt="" loading="lazy" data-parallax="0.12" />
            </div>
          </div>
        )}
      </div>
      {titled.length > 0 && (
        <div className="container">
          <div className={`service-groups service-groups--${Math.min(titled.length, 3)}`}>
            {titled.map((group, i) => (
              <div className="info-card service-group" key={group.title} data-reveal="up" data-reveal-delay={(i % 3) * 80}>
                <div className="service-group__head">
                  <span className="service-group__index">{String(index + 1).padStart(2, '0')}.{i + 1}</span>
                  <h3 className="info-card__title">{group.title}</h3>
                </div>
                <ul className="service-group__chips">{group.items.map((x) => <li key={x}>{x}</li>)}</ul>
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
  const navItems = [
    ...page.items.map((item) => ({ id: item.id, label: item.title })),
    ...(page.fleet.items.length ? [{ id: 'fleet', label: page.fleet.title }] : []),
  ]

  return (
    <>
      <Head title={t("Services")} />
      <PageHero title={page.hero.title} text={page.hero.text} crumb={t("Services")} image={page.items[0]?.image} />
      <SectionNav items={navItems} label={t("Jump to a business line")} />

      <section className="section services-intro" aria-labelledby="services-intro-title">
        <div className="container">
          <SectionHeading id="services-intro-title" eyebrow={page.eyebrow} title={page.title} text={page.text} />
        </div>
      </section>

      {page.items.map((item, i) => <ServiceBlock key={item.id} item={item} index={i} />)}

      {page.fleet.items.length > 0 && (
        <section className="section fleet" id="fleet" aria-labelledby="fleet-title">
          <div className="container fleet__layout">
            <div className="fleet__intro">
              <p className="eyebrow" data-reveal="up">{t("Rental equipment")}</p>
              <h2 className="heading-2" id="fleet-title" data-anim="lines">{page.fleet.title}</h2>
              <p className="lead" data-reveal="up" data-reveal-delay="120">{page.fleet.text}</p>
            </div>
            <FleetChart items={page.fleet.items} title={t('Units by equipment type')} />
          </div>
        </section>
      )}

      <CtaBanner />
    </>
  )
}
