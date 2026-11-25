import { useContent } from '../content/useContent'
import Button from './ui/Button'
import Header from './Header'
import './Hero.css'
import { useT } from '../lib/i18n'

export default function Hero() {
  const t = useT()
  const { hero } = useContent()

  return (
    <section className="hero-frame hero" id="home" aria-labelledby="hero-title">
      <div className="hero-frame__card hero__card">
        {hero.image && <img className="hero-frame__bg" src={hero.image} alt="" fetchPriority="high" />}
        <Header />
        <div className="hero__content">
          <p className="hero__eyebrow">{hero.eyebrow}</p>
          <h1 className="hero__title" id="hero-title">{hero.title}</h1>
          <p className="hero__text">{hero.text}</p>
          <div className="hero__actions">
            <Button to="/contact" icon>{t("Get Started")}</Button>
            <Button to="/projects" variant="outline" icon>{t("View Projects")}</Button>
          </div>
        </div>
      </div>
    </section>
  )
}
