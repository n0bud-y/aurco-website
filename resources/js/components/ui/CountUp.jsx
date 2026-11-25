import { useEffect, useRef, useState } from 'react'

/** Counts up to `value` the first time it scrolls into view. */
export default function CountUp({ value, suffix = '', duration = 1600 }) {
  const ref = useRef(null)
  const [display, setDisplay] = useState(0)

  useEffect(() => {
    const el = ref.current
    if (!el) return undefined
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches
    if (reduced || !('IntersectionObserver' in window)) {
      setDisplay(value)
      return undefined
    }

    let frame = 0
    const observer = new IntersectionObserver(([entry]) => {
      if (!entry.isIntersecting) return
      observer.disconnect()
      const start = performance.now()
      const step = (now) => {
        const progress = Math.min((now - start) / duration, 1)
        setDisplay(Math.round(value * (1 - (1 - progress) ** 3)))
        if (progress < 1) frame = requestAnimationFrame(step)
      }
      frame = requestAnimationFrame(step)
    }, { threshold: 0.4 })

    observer.observe(el)
    return () => {
      observer.disconnect()
      cancelAnimationFrame(frame)
    }
  }, [value, duration])

  return <span ref={ref}>{display.toLocaleString()}{suffix}</span>
}
