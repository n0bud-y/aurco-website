import { useContent } from '../content/useContent'
import blueprint from '../assets/images/deco-blueprint.png'
import SectionHeading from './ui/SectionHeading'
import ImageCard from './ui/ImageCard'
import Button from './ui/Button'
import './Services.css'

export default function Services() {
  const { services } = useContent()

  return (
    <section className="services section section--pink" id="services" aria-labelledby="services-title">
      <img className="deco services__deco" src={blueprint} alt="" aria-hidden="true" data-parallax="0.12" />
      <div className="container">
        <SectionHeading id="services-title" eyebrow={services.eyebrow} title={services.title} text={services.text} />
      </div>
      <div className="container-wide card-grid">
        {services.items.map((item, i) => <ImageCard key={item.title} {...item} revealDelay={i * 90} />)}
      </div>
      <div className="section-cta"><Button to="/services" icon>{services.cta}</Button></div>
    </section>
  )
}
