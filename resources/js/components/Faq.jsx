import { useState } from 'react'
import { useContent } from '../content/useContent'
import buildingSketch from '../assets/images/deco-building.png'
import SectionHeading from './ui/SectionHeading'
import { ArrowDownLong, ArrowRightLong, BuildingsIcon } from './ui/Icons'
import './Faq.css'

export default function Faq() {
  const { faq } = useContent()
  const [openIndex, setOpenIndex] = useState(0)

  return (
    <section className="faq section section--pink" id="faq" aria-labelledby="faq-title">
      <img className="deco faq__deco" src={buildingSketch} alt="" aria-hidden="true" data-parallax="0.14" />
      <div className="container">
        <SectionHeading id="faq-title" eyebrow={faq.eyebrow} title={faq.title} text={faq.text} />
        <div className="faq__layout">
          <div className="faq__collage" aria-hidden="true" data-reveal="left">
            {faq.images.map((src, i) => (
              <img
                key={src}
                className={`faq__img faq__img--${i + 1}`}
                src={src}
                alt=""
                loading="lazy"
                decoding="async"
                data-parallax={i % 2 === 1 ? '0.07' : undefined}
              />
            ))}
            <span className="faq__badge"><BuildingsIcon /></span>
          </div>

          <div className="faq__list" data-reveal="right">
            {faq.items.map((item, i) => {
              const open = openIndex === i
              return (
                <div className={`faq-item${open ? ' is-open' : ''}`} key={item.q}>
                  <h3>
                    <button
                      type="button"
                      className="faq-item__trigger"
                      id={`faq-trigger-${i}`}
                      aria-expanded={open}
                      aria-controls={`faq-panel-${i}`}
                      onClick={() => setOpenIndex(open ? -1 : i)}
                    >
                      <span>{item.q}</span>
                      {open ? <ArrowRightLong className="faq-item__icon" /> : <ArrowDownLong className="faq-item__icon" />}
                    </button>
                  </h3>
                  <div
                    className="faq-item__panel"
                    id={`faq-panel-${i}`}
                    role="region"
                    aria-labelledby={`faq-trigger-${i}`}
                    inert={!open}
                  >
                    <div><p>{item.a}</p></div>
                  </div>
                </div>
              )
            })}
          </div>
        </div>
      </div>
    </section>
  )
}
