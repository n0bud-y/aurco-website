import { useLayoutEffect, useRef, useState } from 'react'
import { gsap } from 'gsap'
import { prefersReducedMotion } from './config'
import { completeIntro, introAlreadySeen, markIntroSeen } from './intro'
import { startScroll, stopScroll } from './smoothScroll'

const logo = '/storage/seed/logo-aurco.webp'

/** First-visit intro: counter, progress line, then the panels lift away. */
export default function Preloader() {
  const root = useRef(null)
  const count = useRef(null)
  const [visible, setVisible] = useState(() => !introAlreadySeen() && !prefersReducedMotion())

  useLayoutEffect(() => {
    if (!visible) {
      completeIntro()
      return undefined
    }
    stopScroll()
    const ctx = gsap.context(() => {
      const value = { progress: 0 }
      gsap.timeline({
        onComplete: () => {
          markIntroSeen()
          completeIntro()
          startScroll()
          setVisible(false)
        },
      })
        .to(value, {
          progress: 100, duration: 1.15, ease: 'power2.inOut',
          onUpdate: () => {
            if (count.current) count.current.textContent = String(Math.round(value.progress)).padStart(3, '0')
          },
        }, 0)
        .from('.preloader__logo', { y: 26, opacity: 0, duration: .7, ease: 'power3.out' }, 0)
        .to('.preloader__bar span', { scaleX: 1, duration: 1.15, ease: 'power2.inOut' }, 0)
        .to('.preloader__content', { y: -28, opacity: 0, duration: .45, ease: 'power2.in' }, '+=0.12')
        .to('.preloader__panel', { yPercent: -101, duration: 1, ease: 'power4.inOut', stagger: .07 }, '-=0.2')
    }, root)

    return () => {
      ctx.revert()
      startScroll()
    }
  }, [visible])

  if (!visible) return null

  return (
    <div className="preloader" ref={root} aria-hidden="true">
      <div className="preloader__panels">
        {Array.from({ length: 5 }, (_, i) => <span className="preloader__panel" key={i} />)}
      </div>
      <div className="preloader__content">
        <img className="preloader__logo" src={logo} alt="" width="240" height="65" />
        <div className="preloader__bar"><span /></div>
        <p className="preloader__count" ref={count}>000</p>
      </div>
    </div>
  )
}
