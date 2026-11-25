import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import ContactSection from '../components/Contact'
import Faq from '../components/Faq'
import SectionHeading from '../components/ui/SectionHeading'
import { MailAt, MapPin, PhoneRing } from '../components/ui/Icons'
import './Contact.css'
import { useT } from '../lib/i18n'

const icons = { phone: PhoneRing, mail: MailAt, map: MapPin }

export default function Contact() {
  const t = useT()
  const { company, contactPage } = useContent()

  return (
    <>
      <Head title={t("Contact Us")} />
      <PageHero title={contactPage.hero.title} text={contactPage.hero.text} crumb={t("Contact")} />

      <section className="contact-info section" aria-label={t("How to reach us")}>
        <div className="container contact-info__grid">
          {contactPage.cards.map((card, i) => {
            const Icon = icons[card.icon]
            return (
              <article className="info-card" key={card.title} data-reveal="up" data-reveal-delay={i * 90}>
                <span className="info-card__icon"><Icon /></span>
                <h2 className="info-card__title">{card.title}</h2>
                {card.href
                  ? <a className="info-card__value" href={card.href}>{card.value}</a>
                  : <p className="info-card__value">{card.value}</p>}
                {card.note && <p className="info-card__note">{card.note}</p>}
              </article>
            )
          })}
        </div>
      </section>

      <ContactSection />

      <section className="contact-map section" aria-labelledby="map-title">
        <div className="container">
          <SectionHeading id="map-title" eyebrow={t("Find us")} title={t("Visit our office")} text={company.address} />
          <div className="contact-map__frame" data-reveal="zoom">
            <iframe
              title={t("Map showing the AURCO Contracting office in Al Khobar")}
              src={`https://www.google.com/maps?q=${encodeURIComponent(contactPage.mapQuery ?? '')}&output=embed`}
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
              allowFullScreen
            />
          </div>
        </div>
      </section>

      <Faq />
    </>
  )
}
