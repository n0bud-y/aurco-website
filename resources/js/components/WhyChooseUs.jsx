import { useContent } from '../content/useContent'
import SectionHeading from './ui/SectionHeading'
import { BulbIcon, GearHandIcon, ShieldHandIcon } from './ui/Icons'
import CountUp from './ui/CountUp'
import './WhyChooseUs.css'

const icons = { bulb: BulbIcon, shield: ShieldHandIcon, gear: GearHandIcon }

function FeatureCard({ icon, title, points, stat, statLabel, revealDelay }) {
  const Icon = icons[icon]
  const counter = /^(\d[\d,]*)(.*)$/.exec(stat)
  return (
    <article className="why__card" data-reveal="up" data-reveal-delay={revealDelay}>
      <Icon className="why__icon" />
      <h3 className="why__title">{title}</h3>
      <ul className="why__list">
        {points.map((point) => <li key={point}>{point}</li>)}
      </ul>
      <div className="why__stat">
        <p className="why__number">
          {counter ? <CountUp value={Number(counter[1].replace(/,/g, ''))} suffix={counter[2]} /> : stat}
        </p>
        <p className="why__label">{statLabel}</p>
      </div>
    </article>
  )
}

export default function WhyChooseUs() {
  const { whyChooseUs } = useContent()

  return (
    <section className="why section" id="expertise" aria-labelledby="why-title">
      <div className="container">
        <SectionHeading id="why-title" eyebrow={whyChooseUs.eyebrow} title={whyChooseUs.title} text={whyChooseUs.text} />
        <div className="why__grid">
          {whyChooseUs.tiles.map((tile, i) =>
            tile.type === 'image' ? (
              <img
                key={i}
                className="why__image"
                src={tile.image}
                alt={tile.alt}
                loading="lazy"
                decoding="async"
                data-reveal="up"
                data-reveal-delay={(i % 3) * 90}
              />
            ) : (
              <FeatureCard key={tile.title} {...tile} revealDelay={(i % 3) * 90} />
            ),
          )}
        </div>
      </div>
    </section>
  )
}
