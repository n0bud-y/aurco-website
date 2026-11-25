import { useLayoutEffect } from 'react'
import { usePage } from '@inertiajs/react'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { SplitText } from 'gsap/SplitText'
import { EASE, EASE_IN_OUT, isFinePointer, prefersReducedMotion } from './config'
import { introReady } from './intro'
import { scrollToElement } from './smoothScroll'

gsap.registerPlugin(ScrollTrigger, SplitText)

const FROM = {
  up: { y: 64, opacity: 0 },
  left: { x: -72, opacity: 0 },
  right: { x: 72, opacity: 0 },
  zoom: { scale: 0.92, opacity: 0 },
}

/**
 * Builds every scroll-driven animation on the current page.
 * Markup opts in with attributes:
 *   data-anim="lines"   masked, line-by-line headline reveal
 *   data-anim="clip"    image wipe with an inner scale
 *   data-reveal="up|left|right|zoom" (+ data-reveal-delay in ms)
 *   data-parallax="0.12"  scroll-scrubbed drift
 *   data-magnetic="0.35"  cursor attraction
 */
export default function usePageMotion() {
  const { url } = usePage()

  useLayoutEffect(() => {
    if (prefersReducedMotion()) return undefined

    let cancelled = false
    let ctx
    const splits = []
    const teardown = []

    // Hide text that is about to be split so it never flashes unstyled.
    const titles = gsap.utils.toArray('[data-anim="lines"], .hero__title, .page-hero__title')
    gsap.set(titles, { autoAlpha: 0 })

    const build = () => {
      if (cancelled) return
      gsap.set(titles, { autoAlpha: 1 })

      ctx = gsap.context(() => {
        const makeSplit = (el) => {
          const split = SplitText.create(el, { type: 'lines', mask: 'lines', linesClass: 'split-line' })
          splits.push(split)
          return split
        }
        // Reverting after the reveal puts the plain text back, so it reflows normally on resize.
        const releaseSplit = (split) => {
          split.revert()
          const index = splits.indexOf(split)
          if (index > -1) splits.splice(index, 1)
        }

        /* ---- Hero / page banner ---- */
        const card = document.querySelector('.hero__card, .page-hero__card')
        if (card) {
          const bg = card.querySelector('.hero-frame__bg')
          const title = card.querySelector('.hero__title, .page-hero__title')
          const content = card.querySelector('.hero__content, .page-hero__content')
          const tl = gsap.timeline({ paused: true, defaults: { ease: EASE } })

          if (bg) tl.from(bg, { scale: 1.32, duration: 2.6, ease: 'power2.out' }, 0)
          tl.from(card.querySelectorAll('.site-header__logo, .site-nav, .site-header__actions'),
            { y: -26, opacity: 0, duration: .9, stagger: .08 }, .25)
          tl.from(card.querySelectorAll('.hero__eyebrow, .page-hero__crumbs'),
            { y: 28, opacity: 0, duration: .9 }, .5)
          const heroSplit = title ? makeSplit(title) : null
          if (heroSplit) tl.from(heroSplit.lines, { yPercent: 118, duration: 1.15, stagger: .13, ease: 'power4.out' }, .58)
          if (heroSplit) tl.eventCallback('onComplete', () => releaseSplit(heroSplit))
          tl.from(card.querySelectorAll('.hero__text, .page-hero__text'), { y: 28, opacity: 0, duration: .9 }, .95)
          tl.from(card.querySelectorAll('.hero__actions > *, .page-hero__actions > *'),
            { y: 28, opacity: 0, duration: .8, stagger: .12 }, 1.05)

          introReady.then(() => { if (!cancelled) tl.play() })

          if (bg) {
            gsap.to(bg, {
              yPercent: 10, ease: 'none',
              scrollTrigger: { trigger: card, start: 'top top', end: 'bottom top', scrub: true },
            })
          }
          if (content) {
            gsap.to(content, {
              yPercent: 12, opacity: .25, ease: 'none',
              scrollTrigger: { trigger: card, start: 'top top', end: 'bottom top', scrub: true },
            })
          }
        }

        /* ---- Headlines: masked line reveal ---- */
        gsap.utils.toArray('[data-anim="lines"]').forEach((el) => {
          const split = makeSplit(el)
          gsap.from(split.lines, {
            yPercent: 118, duration: 1, stagger: .1, ease: 'power4.out',
            scrollTrigger: { trigger: el, start: 'top 88%', once: true },
            onComplete: () => releaseSplit(split),
          })
        })

        /* ---- Generic reveals ---- */
        gsap.utils.toArray('[data-reveal]').forEach((el) => {
          const variant = el.getAttribute('data-reveal') || 'up'
          gsap.from(el, {
            ...(FROM[variant] || FROM.up),
            duration: 1.05,
            delay: (Number(el.dataset.revealDelay) || 0) / 1000,
            ease: EASE,
            scrollTrigger: { trigger: el, start: 'top 88%', once: true },
          })
        })

        /* ---- Image wipes ---- */
        gsap.utils.toArray('[data-anim="clip"]').forEach((el) => {
          gsap.timeline({ scrollTrigger: { trigger: el, start: 'top 85%', once: true } })
            .from(el, { clipPath: 'inset(0% 0% 100% 0%)', duration: 1.2, ease: EASE_IN_OUT })
            .from(el.querySelectorAll('img'), { scale: 1.22, duration: 1.5, ease: EASE }, 0)
        })

        /* ---- Parallax ---- */
        gsap.utils.toArray('[data-parallax]').forEach((el) => {
          const amount = parseFloat(el.dataset.parallax) || 0.1
          gsap.fromTo(el, { yPercent: -amount * 50 }, {
            yPercent: amount * 50, ease: 'none',
            scrollTrigger: { trigger: el.parentElement || el, start: 'top bottom', end: 'bottom top', scrub: true },
          })
        })

        /* ---- Reading progress ---- */
        const bar = document.querySelector('.scroll-progress__bar')
        if (bar) {
          gsap.fromTo(bar, { scaleX: 0 }, {
            scaleX: 1, ease: 'none',
            scrollTrigger: { start: 0, end: 'max', scrub: .25 },
          })
        }

        /* ---- Magnetic buttons ---- */
        if (isFinePointer()) {
          gsap.utils.toArray('[data-magnetic]').forEach((el) => {
            const strength = parseFloat(el.dataset.magnetic) || 0.35
            const xTo = gsap.quickTo(el, 'x', { duration: .5, ease: 'power3' })
            const yTo = gsap.quickTo(el, 'y', { duration: .5, ease: 'power3' })
            const move = (event) => {
              const rect = el.getBoundingClientRect()
              xTo((event.clientX - (rect.left + rect.width / 2)) * strength)
              yTo((event.clientY - (rect.top + rect.height / 2)) * strength)
            }
            const reset = () => { xTo(0); yTo(0) }
            el.addEventListener('pointermove', move)
            el.addEventListener('pointerleave', reset)
            teardown.push(() => {
              el.removeEventListener('pointermove', move)
              el.removeEventListener('pointerleave', reset)
            })
          })
        }
      })

      ScrollTrigger.refresh()
    }

    // Split text only once webfonts are ready, so line breaks are final.
    const fontsReady = document.fonts?.ready ?? Promise.resolve()
    Promise.race([fontsReady, new Promise((resolve) => setTimeout(resolve, 1200))]).then(build)

    const refresh = () => ScrollTrigger.refresh()
    window.addEventListener('load', refresh)

    // Keyboard focus must stay visible while Lenis owns the scroll position.
    const onFocusIn = (event) => {
      const el = event.target
      if (!el?.getBoundingClientRect) return
      const rect = el.getBoundingClientRect()
      if (rect.top < 120 || rect.bottom > window.innerHeight - 40) scrollToElement(el)
    }
    document.addEventListener('focusin', onFocusIn)

    return () => {
      cancelled = true
      window.removeEventListener('load', refresh)
      document.removeEventListener('focusin', onFocusIn)
      teardown.forEach((fn) => fn())
      splits.forEach((split) => split.revert())
      ctx?.revert()
      gsap.set(titles, { clearProps: 'all' })
    }
  }, [url])
}
