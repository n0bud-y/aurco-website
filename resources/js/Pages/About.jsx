import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import StatsStrip from '../components/StatsStrip'
import PresenceMap from '../components/PresenceMap'
import Clients from '../components/Clients'
import CtaBanner from '../components/CtaBanner'
import SectionHeading from '../components/ui/SectionHeading'
import CountUp from '../components/ui/CountUp'
import { CheckCircle, EyeIcon, TargetIcon } from '../components/ui/Icons'
import '../styles/inner.css'
import './About.css'
import { useT } from '../lib/i18n'

export default function About() {
  const t = useT()
  const { aboutPage: page, stats } = useContent()
  const years = stats?.items?.[4]
  const yearsMatch = /^(\d+)(.*)$/.exec(years?.value ?? '')

  return (
    <>
      <Head title={t("About Us")} />
      <PageHero title={page.hero.title} text={page.hero.text} crumb={t("About Us")} image={page.intro.image} />

      <section className="section" id="who-we-are" aria-labelledby="intro-title">
        <div className="container split split--wide-media">
          <div className="split__body">
            <p className="eyebrow" data-reveal="up">{page.intro.eyebrow}</p>
            <h2 className="heading-2" id="intro-title" data-anim="lines">{page.intro.title}</h2>
            <div className="prose lead" data-reveal="up" data-reveal-delay="120">
              {page.intro.paragraphs.map((p) => <p key={p.slice(0, 40)}>{p}</p>)}
            </div>
          </div>
          {page.intro.image && (
            <div className="layered">
              <div className="layered__frame" aria-hidden="true" data-parallax="0.08" />
              <div className="layered__media" data-anim="clip">
                <img src={page.intro.image} alt={t("AURCO engineers reviewing plans on an industrial site")} loading="lazy" />
              </div>
              {years && (
                <div className="layered__badge" data-reveal="zoom" data-reveal-delay="250">
                  <span className="layered__badge-value">
                    {yearsMatch ? <CountUp value={Number(yearsMatch[1])} suffix={yearsMatch[2]} /> : years.value}
                  </span>
                  <span className="layered__badge-label">{years.label}</span>
                </div>
              )}
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
                <article className={`leader${i % 2 ? ' leader--reverse' : ''}`} key={leader.name}>
                  {leader.image && (
                    <div className="leader__portrait">
                      <span className="leader__frame" aria-hidden="true" />
                      <div className="leader__photo" data-anim="clip">
                        <img src={leader.image} alt={leader.name} loading="lazy" />
                      </div>
                    </div>
                  )}
                  <div className="leader__body" data-reveal={i % 2 ? 'left' : 'right'}>
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
              <span className="vm__word" aria-hidden="true">{t("Our vision")}</span>
              <EyeIcon className="vm__icon" />
              <p className="vm__label">{t("Our vision")}</p>
              <p className="vm__text">{page.vision}</p>
            </div>
            <div className="vm__card vm__card--mission" data-reveal="right">
              <span className="vm__word" aria-hidden="true">{t("Our mission")}</span>
              <TargetIcon className="vm__icon" />
              <p className="vm__label">{t("Our mission")}</p>
              <p className="vm__text">{page.mission}</p>
            </div>
          </div>

          <div className="values">
            <SectionHeading id="values-title" eyebrow={t("What drives us")} title={t("Our core values")} />
            <ul className="values__grid">
              {page.values.map((value, i) => (
                <li className="value-card" key={value.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                  <span className="value-card__num" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
                  <h3 className="value-card__title">{value.title}</h3>
                  <p className="value-card__text">{value.text}</p>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      <PresenceMap />

      <section className="section" id="why-aurco" aria-labelledby="why-aurco-title">
        <div className="container">
          <SectionHeading id="why-aurco-title" eyebrow={t("Our difference")} title={page.why.title} text={page.why.text} />
          <ol className="diff-grid">
            {page.why.points.map((point, i) => (
              <li className="diff-card" key={point.title} data-reveal="up" data-reveal-delay={(i % 3) * 80}>
                <span className="diff-card__num" aria-hidden="true">{String(i + 1).padStart(2, '0')}</span>
                <CheckCircle className="diff-card__check" />
                <h3 className="diff-card__title">{point.title}</h3>
                <p className="diff-card__text">{point.text}</p>
              </li>
            ))}
          </ol>
        </div>
      </section>

      <section className="section section--pink" id="resources" aria-labelledby="resources-title">
        <div className="container">
          <SectionHeading id="resources-title" eyebrow={t("People, fleet & capacity")} title={page.resources.title} />
          <div className="prose resources__text" data-reveal="up">
            {page.resources.paragraphs.map((p) => <p key={p.slice(0, 40)}>{p}</p>)}
          </div>
          {page.resources.image && (
            <figure className="resources__chart" data-anim="clip">
              <img src={page.resources.image} alt={t("Manpower and equipment breakdown: 800+ personnel and 200+ equipment units")} loading="lazy" />
            </figure>
          )}
          {page.resources.financial.length > 0 && (
            <ul className="grid-4 resources__financial">
              {page.resources.financial.map((item, i) => (
                <li className="info-card info-card--accent" key={item.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                  <span className="info-card__kicker">{String(i + 1).padStart(2, '0')}</span>
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
