import { useEffect, useState } from 'react'
import { useContent } from '../content/useContent'
import SectionHeading from './ui/SectionHeading'
import { useT } from '../lib/i18n'
import './Clients.css'

const ROWS = 6 // the logo wall image is laid out in six rows

/** Client logos as marquee rows, cut from the single logo-wall image managed in the CRM. */
export default function Clients() {
  const t = useT()
  const { clients } = useContent()
  const [ratio, setRatio] = useState(null) // width / height of one row of the image

  useEffect(() => {
    if (!clients?.image) return undefined
    const img = new Image()
    img.onload = () => setRatio(img.naturalWidth / (img.naturalHeight / ROWS))
    img.src = clients.image
    return () => { img.onload = null }
  }, [clients?.image])

  if (!clients?.image) return null
  const alt = clients.names.length ? t('Client logos: {names}', { names: clients.names.join('، ') }) : t('Client logos')

  return (
    <section className="clients section" id="clients" aria-labelledby="clients-title">
      <div className="container">
        <SectionHeading id="clients-title" eyebrow={clients.eyebrow} title={clients.title} text={clients.text} />
      </div>

      {ratio ? (
        <div className="clients__marquee" role="img" aria-label={alt} style={{ '--ratio': ratio }} data-reveal="up">
          {Array.from({ length: ROWS }, (_, row) => (
            <div className={`clients__row${row % 2 ? ' is-reverse' : ''}`} key={row} style={{ '--speed': `${46 + row * 5}s` }}>
              <div className="clients__track">
                {[0, 1].map((copy) => (
                  <span
                    key={copy}
                    className="clients__strip"
                    style={{
                      backgroundImage: `url("${clients.image}")`,
                      backgroundPosition: `0 ${(row / (ROWS - 1)) * 100}%`,
                    }}
                  />
                ))}
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="container">
          <figure className="clients__wall"><img src={clients.image} alt={alt} loading="lazy" decoding="async" /></figure>
        </div>
      )}
    </section>
  )
}
