import { FaFacebookF, FaInstagram, FaLinkedinIn, FaYoutube } from 'react-icons/fa'
import { FaXTwitter } from 'react-icons/fa6'
import { useContent } from '../content/useContent'
import fallbackLogo from '../assets/images/logo-aurco.png'
import fallbackVision from '../assets/images/vision-2030.png'
import SmartLink from './ui/SmartLink'
import { ArrowCircleRight, MailAt, MapPin, PhoneRing } from './ui/Icons'
import './Footer.css'
import { useT } from '../lib/i18n'

const socialIcons = { instagram: FaInstagram, facebook: FaFacebookF, x: FaXTwitter, youtube: FaYoutube, linkedin: FaLinkedinIn }

function LinkList({ links }) {
  const t = useT()
  return (
    <ul className="footer__links">
      {links.map((link) => (
        <li key={link.label}>
          <SmartLink href={link.to ?? link.href}><ArrowCircleRight />{link.label}</SmartLink>
        </li>
      ))}
    </ul>
  )
}

export default function Footer() {
  const t = useT()
  const { company, footer } = useContent()
  const year = new Date().getFullYear()
  return (
    <footer className="footer">
      <div className="container footer__grid">
        <div className="footer__brand">
          <img className="footer__logo" src={company.logo || fallbackLogo} alt={company.name} width="225" height="61" loading="lazy" />
          {company.tagline && <p className="footer__tagline">{company.tagline}</p>}
          <p className="footer__about">{footer.about}</p>
          <img className="footer__vision" src={footer.visionImage || fallbackVision} alt={t("Saudi Vision 2030")} width="185" height="121" loading="lazy" />
        </div>
        <nav aria-labelledby="footer-services">
          <h2 className="footer__title" id="footer-services">{t("Our Services")}</h2>
          <LinkList links={footer.services} />
        </nav>
        <nav aria-labelledby="footer-company">
          <h2 className="footer__title" id="footer-company">{t("Company")}</h2>
          <LinkList links={footer.company} />
        </nav>
        <div>
          <h2 className="footer__title">{t("Contact Us")}</h2>
          <ul className="footer__contact">
            <li><PhoneRing /><a href={company.phoneHref}>{company.phone}</a></li>
            {company.fax && <li><PhoneRing /><span>{t('Fax: {fax}', { fax: '' })}<bdi dir="ltr">{company.fax}</bdi></span></li>}
            <li><MailAt /><a href={`mailto:${company.email}`}>{company.email}</a></li>
            <li><MapPin /><address>{company.address}</address></li>
          </ul>
        </div>
      </div>
      <div className="footer__bottom">
        <p>{t('Copyright {year} {name} All Rights Reserved', { year, name: company.legalName })}</p>
        <ul className="footer__social">
          {footer.social.map(({ network, label, href }) => {
            const Icon = socialIcons[network]
            if (!Icon) return null

            return (
              <li key={network}>
                <a href={href} aria-label={label} target="_blank" rel="noopener noreferrer"><Icon aria-hidden="true" /></a>
              </li>
            )
          })}
        </ul>
      </div>
    </footer>
  )
}
