import { useContent } from '../content/useContent'
import CountUp from './ui/CountUp'
import './StatsStrip.css'

function Figure({ value }) {
  const match = /^(\d[\d,]*)(.*)$/.exec(value ?? '')
  return match ? <CountUp value={Number(match[1].replace(/,/g, ''))} suffix={match[2]} /> : value
}

/** "AURCO at a glance" — the key figures, with a ticker of locations and industries. */
export default function StatsStrip({ dark = true }) {
  const { stats, aboutPage } = useContent()
  if (!stats?.items?.length) return null

  // Ticker words: the industries list from the figures, plus every location.
  const industries = (stats.items[2]?.detail ?? '').split(/[,،]\s*/).filter(Boolean)
  const places = (aboutPage?.presence?.locations ?? []).map((l) => l.name)
  const ticker = [...industries, ...places]

  return (
    <section className={`stats section${dark ? ' stats--dark' : ''}`} aria-labelledby="stats-title">
      <div className="stats__glow" aria-hidden="true" />
      <div className="container">
        <div className="stats__head">
          <p className="eyebrow" data-reveal="up">{stats.eyebrow}</p>
          <h2 className="heading-2 stats__title" id="stats-title" data-anim="lines">{stats.title}</h2>
        </div>
        <ul className="stats__grid">
          {stats.items.map((item, i) => (
            <li className="stats__item" key={item.label} data-reveal="up" data-reveal-delay={(i % 4) * 80}>
              <span className="stats__line" aria-hidden="true" />
              <p className="stats__value"><Figure value={item.value} /></p>
              <p className="stats__label">{item.label}</p>
              {item.detail && <p className="stats__detail">{item.detail}</p>}
            </li>
          ))}
        </ul>
      </div>
      {ticker.length > 0 && (
        <div className="stats__ticker" aria-hidden="true">
          <div className="stats__ticker-track">
            {[0, 1].map((copy) => (
              <span className="stats__ticker-group" key={copy}>
                {ticker.map((word, i) => <span key={`${copy}-${i}`}>{word}</span>)}
              </span>
            ))}
          </div>
        </div>
      )}
    </section>
  )
}
