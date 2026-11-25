import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import StatsStrip from '../components/StatsStrip'
import Clients from '../components/Clients'
import CtaBanner from '../components/CtaBanner'
import SectionHeading from '../components/ui/SectionHeading'
import { MapPin } from '../components/ui/Icons'
import '../styles/inner.css'
import './About.css'
import { useT } from '../lib/i18n'

export default function About() {
  const t = useT()
  const { aboutPage: page } = useContent()

  return (
    <>
      <Head title={t("About Us")} />
      <PageHero title={page.hero.title} text={page.hero.text} crumb={t("About Us")} />

      <section className="section" id="who-we-are" aria-labelledby="intro-title">
        <div className="container split">
          <div className="split__body" data-reveal="left">
            <p className="eyebrow">{page.intro.eyebrow}</p>
            <h2 className="heading-2" id="intro-title">{page.intro.title}</h2>
            <div className="prose lead">
              {page.intro.paragraphs.map((p) => <p key={p.slice(0, 40)}>{p}</p>)}
            </div>
          </div>
          {page.intro.image && (
            <div className="split__media" data-reveal="right">
              <img src={page.intro.image} alt={t("AURCO engineers reviewing plans on an industrial site")} loading="lazy" />
            </div>
          )}
        </div>
      </section>

      <StatsStrip />

      {page.leaders.length > 0 && (
        <section className="section section--pink" id="leadership" aria-labelledby="leadership-title">
          <div className="container">
            <SectionHeading id="leadership-title" eyebrow={t("Leadership")} title={t("Messages from our leadership")} />
            <div className="leaders">
              {page.leaders.map((leader, i) => (
                <article className={`leader${i % 2 ? ' leader--reverse' : ''}`} key={leader.name} data-reveal="up">
                  {leader.image && <img className="leader__photo" src={leader.image} alt={leader.name} loading="lazy" />}
                  <div className="leader__body">
                    <div className="leader__quote">
                      {leader.message.map((p) => <p key={p.slice(0, 40)}>{p}</p>)}
                    </div>
                    <p className="leader__name">{leader.name}</p>
                    <p className="leader__role">{leader.role}</p>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </section>
      )}

      <section className="section" id="vision" aria-labelledby="vision-title">
        <div className="container">
          <h2 className="visually-hidden" id="vision-title">{t("Vision and mission")}</h2>
          <div className="vm">
            <div className="vm__card vm__card--vision" data-reveal="left">
              <p className="vm__label">{t("Our vision")}</p>
              <p className="vm__text">{page.vision}</p>
            </div>
            <div className="vm__card vm__card--mission" data-reveal="right">
              <p className="vm__label">{t("Our mission")}</p>
              <p className="vm__text">{page.mission}</p>
            </div>
          </div>

          <div className="values">
            <SectionHeading id="values-title" eyebrow={t("What drives us")} title={t("Our core values")} />
            <ul className="grid-4">
              {page.values.map((value, i) => (
                <li className="info-card value-card" key={value.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                  <span className="value-card__num">{String(i + 1).padStart(2, '0')}</span>
                  <h3 className="info-card__title">{value.title}</h3>
                  <p className="info-card__text">{value.text}</p>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      <section className="section section--pink" id="presence" aria-labelledby="presence-title">
        <div className="container split">
          <div className="split__body" data-reveal="left">
            <p className="eyebrow">{t("Across the Kingdom")}</p>
            <h2 className="heading-2" id="presence-title">{page.presence.title}</h2>
            <p className="lead">{page.presence.text}</p>
            <ul className="locations">
              {page.presence.locations.map((loc) => (
                <li key={loc.name} className={loc.note ? 'is-hq' : undefined}>
                  <MapPin />
                  <span>{loc.name}{loc.note && <small>{loc.note}</small>}</span>
                </li>
              ))}
            </ul>
          </div>
          {page.presence.image && (
            <div className="split__media" data-reveal="right">
              <img src={page.presence.image} alt={t('Map of AURCO locations: {names}', { names: page.presence.locations.map((l) => l.name).join('، ') })} loading="lazy" />
            </div>
          )}
        </div>
      </section>

      <section className="section" id="why-aurco" aria-labelledby="why-aurco-title">
        <div className="container">
          <SectionHeading id="why-aurco-title" eyebrow={t("Our difference")} title={page.why.title} text={page.why.text} />
          <ul className="grid-3">
            {page.why.points.map((point, i) => (
              <li className="info-card" key={point.title} data-reveal="up" data-reveal-delay={(i % 3) * 80}>
                <h3 className="info-card__title">{point.title}</h3>
                <p className="info-card__text">{point.text}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>

      <section className="section section--pink" id="resources" aria-labelledby="resources-title">
        <div className="container">
          <SectionHeading id="resources-title" eyebrow={t("People, fleet & capacity")} title={page.resources.title} />
          <div className="prose resources__text" data-reveal="up">
            {page.resources.paragraphs.map((p) => <p key={p.slice(0, 40)}>{p}</p>)}
          </div>
          {page.resources.image && (
            <figure className="resources__chart" data-reveal="zoom">
              <img src={page.resources.image} alt={t("Manpower and equipment breakdown: 800+ personnel and 200+ equipment units")} loading="lazy" />
            </figure>
          )}
          {page.resources.financial.length > 0 && (
            <ul className="grid-4 resources__financial">
              {page.resources.financial.map((item, i) => (
                <li className="info-card" key={item.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                  <h3 className="info-card__title">{item.title}</h3>
                  <p className="info-card__text">{item.text}</p>
                </li>
              ))}
            </ul>
          )}
        </div>
      </section>

      <Clients />
      <CtaBanner />
    </>
  )
}
