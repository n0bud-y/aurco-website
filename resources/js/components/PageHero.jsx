import { Link } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import Header from './Header'
import './PageHero.css'

/** Banner used at the top of every inner page. */
export default function PageHero({ title, text, crumb, children }) {
  const { hero } = useContent()

  return (
    <section className="hero-frame page-hero" aria-labelledby="page-title">
      <div className="hero-frame__card page-hero__card">
        {hero.image && <img className="hero-frame__bg" src={hero.image} alt="" fetchPriority="high" />}
        <Header />
        <div className="page-hero__content">
          <nav className="page-hero__crumbs" aria-label="Breadcrumb">
            <ol>
              <li><Link href="/">Home</Link></li>
              <li aria-current="page">{crumb || title}</li>
            </ol>
          </nav>
          <h1 className="page-hero__title" id="page-title">{title}</h1>
          {text && <p className="page-hero__text">{text}</p>}
          {children && <div className="page-hero__actions">{children}</div>}
        </div>
      </div>
    </section>
  )
}
