import { useEffect, useRef, useState } from 'react'
import { scrollToElement } from '../../motion/smoothScroll'
import './SectionNav.css'

/**
 * Sticky bar of in-page links that highlights the section being read.
 * items: [{ id, label }]
 */
export default function SectionNav({ items, label }) {
  const [active, setActive] = useState(items[0]?.id)
  const listRef = useRef(null)

  useEffect(() => {
    const sections = items.map((item) => document.getElementById(item.id)).filter(Boolean)
    if (!sections.length) return undefined
    const observer = new IntersectionObserver((entries) => {
      const visible = entries.filter((e) => e.isIntersecting).sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top)
      if (visible[0]) setActive(visible[0].target.id)
    }, { rootMargin: '-35% 0px -55% 0px' })
    sections.forEach((s) => observer.observe(s))
    return () => observer.disconnect()
  }, [items])

  // Keep the active pill in view when the bar scrolls sideways on phones.
  useEffect(() => {
    const list = listRef.current
    const link = list?.querySelector(`[data-id="${active}"]`)
    if (!list || !link || list.scrollWidth <= list.clientWidth) return
    // Scroll only the bar sideways, never the page.
    list.scrollTo({ left: link.offsetLeft - (list.clientWidth - link.offsetWidth) / 2, behavior: 'smooth' })
  }, [active])

  return (
    <nav className="section-nav" aria-label={label}>
      <ul className="section-nav__list" ref={listRef}>
        {items.map((item, i) => (
          <li key={item.id}>
            <a
              href={`#${item.id}`}
              data-id={item.id}
              className={`section-nav__link${active === item.id ? ' is-active' : ''}`}
              aria-current={active === item.id ? 'true' : undefined}
              onClick={(event) => {
                const target = document.getElementById(item.id)
                if (!target) return
                event.preventDefault()
                scrollToElement(target)
              }}
            >
              <span className="section-nav__num">{String(i + 1).padStart(2, '0')}</span>
              {item.label}
            </a>
          </li>
        ))}
      </ul>
    </nav>
  )
}
