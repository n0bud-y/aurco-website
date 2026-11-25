import { useContent } from '../content/useContent'
import Button from './ui/Button'
import { CheckCircle, PhoneRing } from './ui/Icons'
import './About.css'

export default function About() {
  const { about } = useContent()

  return (
    <section className="about section" id="about" aria-labelledby="about-title">
      <div className="container about__grid">
        <div className="about__media" data-anim="clip">
          {about.image && <img src={about.image} alt={about.imageAlt} width="405" height="471" loading="lazy" />}
        </div>
        <div className="about__body" data-reveal="right">
          <p className="eyebrow">{about.eyebrow}</p>
          <h2 className="heading-2 about__title" id="about-title">{about.title}</h2>
          <p className="about__text">{about.text}</p>
          <ul className="about__features">
            {about.features.map((feature) => (
              <li key={feature}><CheckCircle className="about__check" />{feature}</li>
            ))}
          </ul>
          <div className="about__actions">
            <Button to="/contact" icon>Get Free Quote</Button>
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
