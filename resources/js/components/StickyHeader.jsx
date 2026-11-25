import { useEffect, useState } from 'react'
import { Link } from '@inertiajs/react'
import { navLinks } from '../data/navigation'
import { useContent } from '../content/useContent'
import fallbackLogo from '../assets/images/logo-aurco.png'
import Button from './ui/Button'
import NavLink from './ui/NavLink'
import './StickyHeader.css'

/** Compact bar that slides in once the page hero has scrolled away. */
export default function StickyHeader() {
  const [visible, setVisible] = useState(false)
  const { company } = useContent()

  useEffect(() => {
    let ticking = false
    const onScroll = () => {
      if (ticking) return
      ticking = true
      requestAnimationFrame(() => {
        setVisible(window.scrollY > 620)
        ticking = false
      })
    }
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  return (
    <div className={`sticky-header${visible ? ' is-visible' : ''}`} inert={!visible}>
      <div className="sticky-header__inner">
        <Link className="sticky-header__logo" href="/">
          <img src={company.logo || fallbackLogo} alt={company.name} width="170" height="46" />
        </Link>
        <nav aria-label="Main (sticky)">
          <ul className="site-nav__list">
            {navLinks.map((link) => (
              <li key={link.label}><NavLink link={link} className="site-nav__link" /></li>
            ))}
          </ul>
        </nav>
        <Button to="/contact">Contact Us</Button>
      </div>
    </div>
  )
}
