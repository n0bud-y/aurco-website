import { useLayoutEffect, useRef } from 'react'
import { usePage } from '@inertiajs/react'
import { gsap } from 'gsap'
import { prefersReducedMotion } from './config'

/** Panels that lift away to reveal each newly loaded page. */
export default function PageCurtain() {
  const root = useRef(null)
  const firstRender = useRef(true)
  const { url } = usePage()

  useLayoutEffect(() => {
    if (firstRender.current) {
      firstRender.current = false
      return
    }
    if (prefersReducedMotion() || !root.current) return
    const panels = root.current.querySelectorAll('span')
    gsap.set(root.current, { autoAlpha: 1 })
    gsap.fromTo(panels, { yPercent: 0 }, {
      yPercent: -101, duration: 1, ease: 'power4.inOut', stagger: .06,
      onComplete: () => gsap.set(root.current, { autoAlpha: 0 }),
    })
  }, [url])

  return (
    <div className="page-curtain" ref={root} aria-hidden="true">
      {Array.from({ length: 5 }, (_, i) => <span key={i} />)}
    </div>
  )
}
