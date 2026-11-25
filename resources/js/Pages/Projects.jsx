import { useState } from 'react'
import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import CtaBanner from '../components/CtaBanner'
import SectionHeading from '../components/ui/SectionHeading'
import FilterBar from '../components/ui/FilterBar'
import { MapPin } from '../components/ui/Icons'
import '../styles/inner.css'
import './Projects.css'

function ProjectCard({ item, revealDelay }) {
  const facts = [
    ['Client', item.client],
    ['Year', item.year],
    ['Role', item.role],
    ['Value', item.value],
  ].filter(([, value]) => value)

  return (
    <article className="project-card" data-reveal="up" data-reveal-delay={revealDelay}>
      <div className="project-card__media">
        {item.image && <img src={item.image} alt="" loading="lazy" decoding="async" />}
        {item.category && <span className="image-card__tag">{item.category}</span>}
      </div>
      <div className="project-card__body">
        <h3 className="project-card__title">{item.title}</h3>
        {item.location && <p className="project-card__location"><MapPin />{item.location}</p>}
        {item.summary && <p className="project-card__summary">{item.summary}</p>}
        {facts.length > 0 && (
          <dl className="project-card__facts">
            {facts.map(([label, value]) => (
              <div key={label}><dt>{label}</dt><dd>{value}</dd></div>
            ))}
          </dl>
        )}
      </div>
    </article>
  )
}

function TrackRecord({ sections }) {
  const [active, setActive] = useState(sections[0]?.label)
  const section = sections.find((s) => s.label === active) ?? sections[0]
  if (!section) return null
  const showValue = section.rows.some((r) => r.value)

  return (
    <section className="section section--pink track" id="track-record" aria-labelledby="track-title">
      <div className="container">
        <SectionHeading
          id="track-title"
          eyebrow="Track record"
          title="Notable completed & ongoing work"
          text="A wider list of construction, temporary facilities, facility management and equipment rental contracts. Values are approximate."
        />
        <FilterBar options={sections.map((s) => s.label)} value={section.label} onChange={setActive} label="Choose a track record table" />
        <div className="data-table-wrap">
          <table className="data-table data-table--stack">
            <caption className="visually-hidden">{section.label}</caption>
            <thead>
              <tr>
                <th scope="col">Project</th>
                <th scope="col">Client</th>
                <th scope="col">Location</th>
                <th scope="col">Scope</th>
                <th scope="col">Year</th>
                {showValue && <th scope="col">Value (approx.)</th>}
              </tr>
            </thead>
            <tbody>
              {section.rows.map((row, i) => (
                <tr key={`${row.title}-${i}`}>
                  <td className="data-table__title">{row.title}</td>
                  <td data-label="Client">{row.client}</td>
                  <td data-label="Location">{row.location}</td>
                  <td data-label="Scope">{row.scope}</td>
                  <td data-label="Year" className="data-table__num">{row.year}</td>
                  {showValue && <td data-label="Value" className="data-table__value">{row.value ?? '—'}</td>}
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </section>
  )
}

export default function Projects() {
  const { projectsPage } = useContent()
  const [filter, setFilter] = useState('All')
  const items = filter === 'All'
    ? projectsPage.items
    : projectsPage.items.filter((item) => item.category === filter)

  return (
    <>
      <Head title="Projects" />
      <PageHero title={projectsPage.hero.title} text={projectsPage.hero.text} crumb="Projects" />

      <section className="portfolio section" aria-labelledby="portfolio-title">
        <div className="container">
          <SectionHeading
            id="portfolio-title"
            eyebrow={projectsPage.eyebrow}
            title={projectsPage.title}
            text={projectsPage.text}
          />
          <FilterBar options={projectsPage.filters} value={filter} onChange={setFilter} label="Filter projects by type" />
          <p className="visually-hidden" role="status">{`${items.length} projects shown`}</p>
          <ul className="portfolio__grid">
            {items.map((item, i) => (
              <li key={item.title}>
                <ProjectCard item={item} revealDelay={(i % 3) * 90} />
              </li>
            ))}
          </ul>
        </div>
      </section>

      {projectsPage.trackRecord?.length > 0 && <TrackRecord sections={projectsPage.trackRecord} />}

      <CtaBanner />
    </>
  )
}
