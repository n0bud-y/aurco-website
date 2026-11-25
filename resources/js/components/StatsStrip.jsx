import { useContent } from '../content/useContent'
import CountUp from './ui/CountUp'
import './StatsStrip.css'

function Figure({ value }) {
  const match = /^(\d[\d,]*)(.*)$/.exec(value ?? '')
  return match ? <CountUp value={Number(match[1].replace(/,/g, ''))} suffix={match[2]} /> : value
}

/** "AURCO at a glance" — the key figures from the company profile. */
export default function StatsStrip({ dark = true }) {
  const { stats } = useContent()
  if (!stats?.items?.length) return null

  return (
    <section className={`stats section${dark ? ' stats--dark' : ''}`} aria-labelledby="stats-title">
      <div className="container">
        <div className="stats__head" data-reveal="up">
          <p className="eyebrow">{stats.eyebrow}</p>
          <h2 className="heading-2 stats__title" id="stats-title">{stats.title}</h2>
        </div>
        <ul className="stats__grid">
          {stats.items.map((item, i) => (
            <li className="stats__item" key={item.label} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
              <p className="stats__value"><Figure value={item.value} /></p>
              <p className="stats__label">{item.label}</p>
              {item.detail && <p className="stats__detail">{item.detail}</p>}
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
