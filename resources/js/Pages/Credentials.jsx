import { useState } from 'react'
import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import CtaBanner from '../components/CtaBanner'
import Lightbox from '../components/Lightbox'
import SectionHeading from '../components/ui/SectionHeading'
import '../styles/inner.css'
import './Credentials.css'
import { useT } from '../lib/i18n'

const statRows = [
  ['manhours', 'Man-hours worked'],
  ['fai', 'First aid injury / illness cases'],
  ['mtc', 'Medical treatment cases'],
  ['lti', 'Lost time incidents (LTI)'],
  ['nearMiss', 'Near misses'],
  ['environmental', 'Environmental incidents'],
  ['fatalities', 'On-job fatalities'],
]
const zeroIsGood = new Set(['lti', 'environmental', 'fatalities'])

function SafetyTable({ stats }) {
  const t = useT()
  if (!stats.length) return null
  return (
    <div className="data-table-wrap data-table-scroll" data-reveal="up">
      <table className="data-table data-table--center safety-table">
        <caption className="visually-hidden">{t("Annual safety statistics")}</caption>
        <thead>
          <tr>
            <th scope="col">{t("Safety statistics")}</th>
            {stats.map((s) => <th scope="col" key={s.year}>{s.year}</th>)}
          </tr>
        </thead>
        <tbody>
          {statRows.map(([key, label]) => (
            <tr key={key}>
              <th scope="row" className="data-table__title">{t(label)}</th>
              {stats.map((s) => (
                <td key={s.year} className={`data-table__num${zeroIsGood.has(key) && s[key] === '0' ? ' data-table__good' : ''}`}>{s[key] ?? '—'}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default function Credentials() {
  const t = useT()
  const { credentialsPage: page } = useContent()
  const [viewer, setViewer] = useState({ items: [], index: -1 })
  const open = (items, index) => setViewer({ items, index })

  const isoImages = page.iso.images.map((src, i) => ({ src, caption: t(i === 0 ? 'ISO 9001:2015 and Achilles sustainability certificate' : 'ISO 14001:2015 and ISO 45001:2018 certificates') }))
  const cardImages = page.credentials.filter((c) => c.image).map((c) => ({ src: c.image, caption: c.title }))

  return (
    <>
      <Head title={t("QHSE & Credentials")} />
      <PageHero title={page.hero.title} text={page.hero.text} crumb={t("QHSE & Credentials")}>
        <nav className="cred-jump" aria-label={t("On this page")}>
          {[['hse', 'HSE'], ['certifications', 'Certifications'], ['quality', 'Quality'], ['sustainability', 'Sustainability'], ['registrations', 'Registrations'], ['recognition', 'Recognition']].map(([id, label]) => (
            <a key={id} href={`#${id}`}>{t(label)}</a>
          ))}
        </nav>
      </PageHero>

      <section className="section" id="hse" aria-labelledby="hse-title">
        <div className="container">
          <SectionHeading id="hse-title" eyebrow={t("Safety first")} title={page.hse.title} text={page.hse.text} />
          <ul className="grid-4 hse-pillars">
            {page.hse.pillars.map((p, i) => (
              <li className="info-card" key={p.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                <h3 className="info-card__title">{p.title}</h3>
                <p className="info-card__text">{p.text}</p>
              </li>
            ))}
          </ul>
          <h3 className="sub-heading" data-reveal="up">{t("Annual safety statistics record")}</h3>
          <SafetyTable stats={page.hse.stats} />
        </div>
      </section>

      <section className="section section--pink" id="certifications" aria-labelledby="iso-title">
        <div className="container split">
          <div className="split__body" data-reveal="left">
            <p className="eyebrow">{t("Certified systems")}</p>
            <h2 className="heading-2" id="iso-title">{page.iso.title}</h2>
            <p className="lead">{page.iso.text}</p>
            <ul className="iso-list">
              {page.iso.items.map((item) => (
                <li key={item.title}><strong>{item.title}</strong><span>{item.text}</span></li>
              ))}
            </ul>
          </div>
          <div className="iso-images" data-reveal="right">
            {isoImages.map((img, i) => (
              <button type="button" className="frame-btn" key={img.src} onClick={() => open(isoImages, i)}>
                <img src={img.src} alt={img.caption} loading="lazy" />
                <span className="frame-btn__caption">{img.caption}</span>
              </button>
            ))}
          </div>
        </div>
      </section>

      <section className="section" id="quality" aria-labelledby="quality-title">
        <div className="container">
          <SectionHeading id="quality-title" eyebrow={t("QA / QC")} title={t("Quality assurance & quality control")} text={page.quality.text} />
          <div className="grid-3">
            {[['Quality assurance', page.quality.assurance], ['Quality control', page.quality.control], ['Our QA/QC culture', page.quality.culture]].map(([title, items], i) => (
              <div className="info-card" key={title} data-reveal="up" data-reveal-delay={i * 80}>
                <span className="info-card__kicker">{t("Key focus")}</span>
                <h3 className="info-card__title">{t(title)}</h3>
                <ul className="tick-list quality-list">{items.map((t) => <li key={t}>{t}</li>)}</ul>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="section section--pink" id="sustainability" aria-labelledby="sustainability-title">
        <div className="container">
          <SectionHeading id="sustainability-title" eyebrow={t("People, planet & Vision 2030")} title={page.sustainability.title} text={page.sustainability.text} />
          <ul className="sdgs">
            {page.sustainability.sdgs.map((goal, i) => (
              <li className={`sdg sdg--${goal.number}`} key={goal.number} data-reveal="up" data-reveal-delay={(i % 4) * 60}>
                <span className="sdg__num">{goal.number}</span>
                <span className="sdg__title">{goal.title}</span>
                <span className="sdg__text">{goal.text}</span>
              </li>
            ))}
          </ul>

          <div className="csr">
            <div className="csr__intro" data-reveal="up">
              <h3 className="sub-heading">{t("Our commitment beyond business")}</h3>
              <p className="lead">{page.sustainability.csrText}</p>
            </div>
            <ul className="grid-4">
              {page.sustainability.csr.map((p, i) => (
                <li className="info-card" key={p.title} data-reveal="up" data-reveal-delay={(i % 4) * 70}>
                  <span className="info-card__kicker">{String(i + 1).padStart(2, '0')}</span>
                  <h3 className="info-card__title">{p.title}</h3>
                  <p className="info-card__text">{p.text}</p>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      <section className="section" id="registrations" aria-labelledby="registrations-title">
        <div className="container">
          <SectionHeading id="registrations-title" eyebrow={t("Compliance")} title={t("Registrations & compliance")} />
          <ul className="grid-2 cred-cards">
            {page.credentials.map((card, i) => {
              const imageIndex = cardImages.findIndex((c) => c.src === card.image)
              return (
                <li className="info-card cred-card" key={card.title} data-reveal="up" data-reveal-delay={(i % 2) * 80}>
                  {card.image && (
                    <button type="button" className="cred-card__thumb" onClick={() => open(cardImages, imageIndex)}>
                      <img src={card.image} alt={t('{name} certificate', { name: card.title })} loading="lazy" />
                      <span className="visually-hidden">{t("Open larger image")}</span>
                    </button>
                  )}
                  <div>
                    {card.detail && <span className="info-card__kicker">{card.detail}</span>}
                    <h3 className="info-card__title">{card.title}</h3>
                    <p className="info-card__text">{card.text}</p>
                  </div>
                </li>
              )
            })}
          </ul>

          <div className="split gov" id="digital">
            <div className="split__body" data-reveal="left">
              <h3 className="sub-heading">{t("Key government credentials")}</h3>
              <ul className="tick-list">{page.government.map((g) => <li key={g}>{g}</li>)}</ul>
              <h3 className="sub-heading gov__digital">{t("Digital & technological advancement")}</h3>
              <p className="lead">{page.digital.text}</p>
              <ul className="digital-points">
                {page.digital.points.map((p) => <li key={p.title}><strong>{p.title}</strong> {p.text}</li>)}
              </ul>
            </div>
            {page.governmentImage && (
              <div data-reveal="right">
                <button type="button" className="frame-btn" onClick={() => open([{ src: page.governmentImage, caption: t('Government credentials certificates') }], 0)}>
                  <img src={page.governmentImage} alt={t("Commercial registration, Saudi Council of Engineers, GOSI, Balady and VAT certificates")} loading="lazy" />
                  <span className="frame-btn__caption">{t("Government credentials certificates")}</span>
                </button>
              </div>
            )}
          </div>
        </div>
      </section>

      {page.recognition.images.length > 0 && (
        <section className="section section--pink" id="recognition" aria-labelledby="recognition-title">
          <div className="container">
            <SectionHeading id="recognition-title" eyebrow={t("Awards & appreciation")} title={t("Recognition from our clients")} text={page.recognition.text} />
            <div className="recognition">
              {page.recognition.images.map((img, i) => (
                <button type="button" className="frame-btn" key={img.src} onClick={() => open(page.recognition.images, i)} data-reveal="up" data-reveal-delay={i * 80}>
                  <img src={img.src} alt={t('{name} certificates of appreciation', { name: img.caption })} loading="lazy" />
                  <span className="frame-btn__caption">{img.caption}</span>
                </button>
              ))}
            </div>
          </div>
        </section>
      )}

      <CtaBanner />
      <Lightbox items={viewer.items} index={viewer.index} onClose={() => setViewer((v) => ({ ...v, index: -1 }))} onNavigate={(index) => setViewer((v) => ({ ...v, index }))} />
    </>
  )
}
