import { useEffect, useState } from 'react'
import { ArrowUp } from './ui/Icons'
import './ScrollTopButton.css'
import { useT } from '../lib/i18n'

const RADIUS = 26
const CIRCUMFERENCE = 2 * Math.PI * RADIUS

/** Back-to-top control with a ring that tracks reading progress. */
export default function ScrollTopButton() {
  const t = useT()
  const [progress, setProgress] = useState(0)
  const [visible, setVisible] = useState(false)

  useEffect(() => {
    let ticking = false
    const onScroll = () => {
      if (ticking) return
      ticking = true
      requestAnimationFrame(() => {
        const max = document.documentElement.scrollHeight - window.innerHeight
        setProgress(max > 0 ? Math.min(window.scrollY / max, 1) : 0)
        setVisible(window.scrollY > 600)
        ticking = false
      })
    }
    onScroll()
    window.addEventListener('scroll', onScroll, { passive: true })
    window.addEventListener('resize', onScroll)
    return () => {
      window.removeEventListener('scroll', onScroll)
      window.removeEventListener('resize', onScroll)
    }
  }, [])

  const backToTop = () => {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
    window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' })
  }

  return (
    <button
      type="button"
      className={`scroll-top${visible ? ' is-visible' : ''}`}
      onClick={backToTop}
      inert={!visible}
      aria-label={t("Back to top")}
    >
      <svg className="scroll-top__ring" viewBox="0 0 60 60" aria-hidden="true">
        <circle className="scroll-top__track" cx="30" cy="30" r={RADIUS} />
        <circle
          className="scroll-top__bar"
          cx="30" cy="30" r={RADIUS}
          style={{ strokeDasharray: CIRCUMFERENCE, strokeDashoffset: CIRCUMFERENCE * (1 - progress) }}
        />
      </svg>
      <ArrowUp className="scroll-top__icon" />
    </button>
  )
}
