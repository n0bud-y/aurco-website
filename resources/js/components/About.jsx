import { useContent } from '../content/useContent'
import Button from './ui/Button'
import CountUp from './ui/CountUp'
import { CheckCircle, PhoneRing } from './ui/Icons'
import './About.css'
import { useT } from '../lib/i18n'

export default function About() {
  const t = useT()
  const { about, stats } = useContent()
  // "17+ | Years of experience" from the key figures
  const years = stats?.items?.[4]
  const match = /^(\d+)(.*)$/.exec(years?.value ?? '')

  return (
    <section className="about section" id="about" aria-labelledby="about-title">
      <div className="container about__grid">
        <div className="about__visual">
          <div className="about__frame" aria-hidden="true" data-parallax="0.08" />
          <div className="about__media" data-anim="clip">
            {about.image && <img src={about.image} alt={about.imageAlt} width="405" height="471" loading="lazy" />}
          </div>
          {years && (
            <div className="about__badge" data-reveal="zoom" data-reveal-delay="300">
              <span className="about__badge-value">
                {match ? <CountUp value={Number(match[1])} suffix={match[2]} /> : years.value}
              </span>
              <span className="about__badge-label">{years.label}</span>
            </div>
          )}
        </div>
        <div className="about__body">
          <p className="eyebrow" data-reveal="up">{about.eyebrow}</p>
          <h2 className="heading-2 about__title" id="about-title" data-anim="lines">{about.title}</h2>
          <p className="about__text" data-reveal="up" data-reveal-delay="120">{about.text}</p>
          <ul className="about__features">
            {about.features.map((feature, i) => (
              <li key={feature} data-reveal="right" data-reveal-delay={180 + i * 90}>
                <CheckCircle className="about__check" />{feature}
              </li>
            ))}
          </ul>
          <div className="about__actions" data-reveal="up" data-reveal-delay="300">
            <Button to="/contact" icon>{t("Get Free Quote")}</Button>
            <a className="about__phone" href={about.supportPhoneHref}>
              <PhoneRing className="about__phone-icon" />
              <span>
                <span className="about__phone-label">{about.supportLabel}</span>
                <span className="about__phone-number">{about.supportPhone}</span>
              </span>
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}
