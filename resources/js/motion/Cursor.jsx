import { useLayoutEffect, useRef } from 'react'
import { gsap } from 'gsap'
import { isFinePointer, prefersReducedMotion } from './config'

const HOVER_TARGETS = 'a, button, input, textarea, .gallery__tile, .image-card'

/** Dot + trailing ring cursor, only on devices with a real pointer. */
export default function Cursor() {
  const dot = useRef(null)
  const ring = useRef(null)

  useLayoutEffect(() => {
    if (prefersReducedMotion() || !isFinePointer()) return undefined
    document.documentElement.classList.add('has-cursor')

    const moveDotX = gsap.quickTo(dot.current, 'x', { duration: .12, ease: 'power3' })
    const moveDotY = gsap.quickTo(dot.current, 'y', { duration: .12, ease: 'power3' })
    const moveRingX = gsap.quickTo(ring.current, 'x', { duration: .5, ease: 'power3' })
    const moveRingY = gsap.quickTo(ring.current, 'y', { duration: .5, ease: 'power3' })

    const onMove = (event) => {
      moveDotX(event.clientX); moveDotY(event.clientY)
      moveRingX(event.clientX); moveRingY(event.clientY)
    }
    const grow = () => gsap.to(ring.current, { scale: 1.9, duration: .35, ease: 'power3.out' })
    const shrink = () => gsap.to(ring.current, { scale: 1, duration: .35, ease: 'power3.out' })
    const onOver = (event) => { if (event.target.closest(HOVER_TARGETS)) grow() }
    const onOut = (event) => { if (event.target.closest(HOVER_TARGETS)) shrink() }

    window.addEventListener('pointermove', onMove)
    document.addEventListener('pointerover', onOver)
    document.addEventListener('pointerout', onOut)

    return () => {
      document.documentElement.classList.remove('has-cursor')
      window.removeEventListener('pointermove', onMove)
      document.removeEventListener('pointerover', onOver)
      document.removeEventListener('pointerout', onOut)
    }
  }, [])

  return (
    <div className="cursor" aria-hidden="true">
      <span className="cursor__ring" ref={ring} />
      <span className="cursor__dot" ref={dot} />
    </div>
  )
}
