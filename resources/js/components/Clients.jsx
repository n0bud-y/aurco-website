import { useContent } from '../content/useContent'
import SectionHeading from './ui/SectionHeading'
import './Clients.css'

/** Client logo wall. */
export default function Clients() {
  const { clients } = useContent()
  if (!clients?.image) return null

  return (
    <section className="clients section" id="clients" aria-labelledby="clients-title">
      <div className="container">
        <SectionHeading id="clients-title" eyebrow={clients.eyebrow} title={clients.title} text={clients.text} />
        <figure className="clients__wall" data-reveal="zoom">
          <img
            src={clients.image}
            alt={clients.names.length ? `Client logos: ${clients.names.join(', ')}` : 'Client logos'}
            loading="lazy"
            decoding="async"
          />
        </figure>
      </div>
    </section>
  )
}
