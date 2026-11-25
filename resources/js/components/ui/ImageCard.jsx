import { Link } from '@inertiajs/react'
import { MapPin } from './Icons'

/** Photo card with a dark gradient and title, used for services, projects and the portfolio. */
export default function ImageCard({ image, title, to, href, tag, meta, revealDelay = 0 }) {
  const Wrapper = to ? Link : href ? 'a' : 'div'
  const linkProps = to ? { href: to } : href ? { href } : {}
  return (
    <Wrapper className="image-card" data-reveal="up" data-reveal-delay={revealDelay} {...linkProps}>
      {image && <img className="image-card__img" src={image} alt="" loading="lazy" decoding="async" />}
      {tag && <span className="image-card__tag">{tag}</span>}
      <div className="image-card__text">
        <h3 className="image-card__title">{title}</h3>
        {meta && <p className="image-card__meta"><MapPin />{meta}</p>}
      </div>
    </Wrapper>
  )
}
