import { useContent } from '../content/useContent'
import Button from './ui/Button'
import './CtaBanner.css'

export default function CtaBanner() {
  const { ctaBanner } = useContent()

  return (
    <section className="cta" aria-labelledby="cta-title">
      <div className="container">
        <div className="cta__content" data-reveal="left">
          <h2 className="heading-2 cta__title" id="cta-title">{ctaBanner.title}</h2>
          <p className="cta__text">{ctaBanner.text}</p>
          <Button to="/contact" icon className="cta__button">{ctaBanner.cta}</Button>
        </div>
      </div>
      {ctaBanner.image && <img className="cta__art" data-reveal="right" src={ctaBanner.image} alt="" aria-hidden="true" loading="lazy" />}
    </section>
  )
}
