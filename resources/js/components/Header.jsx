import { useEffect, useState } from 'react'
import { Link, usePage } from '@inertiajs/react'
import { navLinks } from '../data/navigation'
import { useContent } from '../content/useContent'
import fallbackLogo from '../assets/images/logo-aurco.png'
import Button from './ui/Button'
import NavLink from './ui/NavLink'
import { CloseIcon, MenuIcon } from './ui/Icons'
import { getLanguage, setLanguage } from '../lib/googleTranslate'
import './Header.css'

function LanguageToggle() {
  const [lang, setLang] = useState(getLanguage)

  const choose = (next) => {
    setLang(next)
    setLanguage(next)
  }

  return (
    <div className="lang-toggle notranslate" translate="no" role="group" aria-label="Language">
      <button type="button" aria-pressed={lang === 'en'} onClick={() => choose('en')}>
        ENG<span className="visually-hidden"> English</span>
      </button>
      <button type="button" aria-pressed={lang === 'ar'} onClick={() => choose('ar')}>
        AR<span className="visually-hidden"> Arabic</span>
      </button>
    </div>
  )
}

export default function Header() {
  const [menuOpen, setMenuOpen] = useState(false)
  const { url } = usePage()
  const { company } = useContent()

  useEffect(() => setMenuOpen(false), [url])

  useEffect(() => {
    if (!menuOpen) return undefined
    const onKey = (event) => event.key === 'Escape' && setMenuOpen(false)
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [menuOpen])

  return (
    <header className="site-header">
      <Link className="site-header__logo" href="/">
        <img src={company.logo || fallbackLogo} alt={company.name} width="225" height="61" />
      </Link>

      <nav id="site-nav" className={`site-nav${menuOpen ? ' is-open' : ''}`} aria-label="Main">
        <ul className="site-nav__list">
          {navLinks.map((link) => (
            <li key={link.label}>
              <NavLink link={link} className="site-nav__link" onNavigate={() => setMenuOpen(false)} />
            </li>
          ))}
        </ul>
      </nav>

      <div className="site-header__actions">
        <LanguageToggle />
        <Button to="/contact" className="site-header__cta">Contact Us</Button>
        <button
          type="button"
          className="site-header__menu"
          aria-expanded={menuOpen}
          aria-controls="site-nav"
          onClick={() => setMenuOpen((open) => !open)}
        >
          {menuOpen ? <CloseIcon /> : <MenuIcon />}
          <span className="visually-hidden">{menuOpen ? 'Close menu' : 'Open menu'}</span>
        </button>
      </div>
    </header>
  )
}
