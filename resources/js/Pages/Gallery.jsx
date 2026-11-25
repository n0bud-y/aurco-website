import { useMemo, useState } from 'react'
import { Head } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import PageHero from '../components/PageHero'
import Lightbox from '../components/Lightbox'
import SectionHeading from '../components/ui/SectionHeading'
import FilterBar from '../components/ui/FilterBar'
import './Gallery.css'

export default function Gallery() {
  const { galleryPage } = useContent()
  const [filter, setFilter] = useState('All')
  const [openIndex, setOpenIndex] = useState(-1)

  const items = useMemo(
    () => (filter === 'All' ? galleryPage.items : galleryPage.items.filter((item) => item.category === filter)),
    [filter, galleryPage.items],
  )

  return (
    <>
      <Head title="Gallery" />
      <PageHero title={galleryPage.hero.title} text={galleryPage.hero.text} crumb="Gallery" />

      <section className="gallery section" aria-labelledby="gallery-title">
        <div className="container">
          <SectionHeading
            id="gallery-title"
            eyebrow={galleryPage.eyebrow}
            title={galleryPage.title}
            text={galleryPage.text}
          />
          <FilterBar
            options={galleryPage.filters}
            value={filter}
            onChange={(next) => { setFilter(next); setOpenIndex(-1) }}
            label="Filter photos by category"
          />
          <p className="visually-hidden" role="status">{`${items.length} photos shown`}</p>
          <ul className="gallery__grid">
            {items.map((item, i) => (
              <li className="gallery__item" key={item.src} data-reveal="up" data-reveal-delay={(i % 3) * 80}>
                <button type="button" className="gallery__tile" onClick={() => setOpenIndex(i)}>
                  <img src={item.src} alt="" loading="lazy" decoding="async" />
                  <span className="gallery__caption">{item.caption}</span>
                  <span className="visually-hidden">Open larger photo</span>
                </button>
              </li>
            ))}
          </ul>
        </div>
      </section>

      <Lightbox items={items} index={openIndex} onClose={() => setOpenIndex(-1)} onNavigate={setOpenIndex} />
    </>
  )
}
