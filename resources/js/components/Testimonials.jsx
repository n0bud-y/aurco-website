import { useContent } from '../content/useContent'
import SectionHeading from './ui/SectionHeading'
import { Star } from './ui/Icons'
import './Testimonials.css'

export default function Testimonials() {
  const { testimonials } = useContent()

  return (
    <section className="testimonials section" id="testimonials" aria-labelledby="testimonials-title">
      <div className="container">
        <SectionHeading id="testimonials-title" eyebrow={testimonials.eyebrow} title={testimonials.title} text={testimonials.text} />
        <div className="testimonials__grid">
          {testimonials.items.map((item, i) => (
            <figure className="testimonial" key={item.name} data-reveal="up" data-reveal-delay={i * 90}>
              {item.rating > 0 && (
                <div className="testimonial__stars" role="img" aria-label={`Rated ${item.rating} out of 5`}>
                  {Array.from({ length: item.rating }, (_, i) => <Star key={i} />)}
                </div>
              )}
              <blockquote className="testimonial__quote"><p>{item.quote}</p></blockquote>
              <figcaption className="testimonial__author">
                {item.avatar && <img src={item.avatar} alt="" width="64" height="64" loading="lazy" />}
                <span>
                  <span className="testimonial__name">{item.name}</span>
                  <span className="testimonial__role">{item.role}</span>
                </span>
              </figcaption>
            </figure>
          ))}
        </div>
      </div>
    </section>
  )
}
