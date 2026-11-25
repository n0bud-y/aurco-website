import Lenis from 'lenis'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { prefersReducedMotion } from './config'

gsap.registerPlugin(ScrollTrigger)

let lenis = null

/** Momentum scrolling, driven by the GSAP ticker so ScrollTrigger stays in sync. */
export function initSmoothScroll() {
  if (lenis || prefersReducedMotion()) return lenis
  lenis = new Lenis({ duration: 1.05, lerp: 0.09, smoothWheel: true, touchMultiplier: 1.6 })
  lenis.on('scroll', ScrollTrigger.update)
  gsap.ticker.add((time) => lenis?.raf(time * 1000))
  gsap.ticker.lagSmoothing(0)
  return lenis
}

export const getLenis = () => lenis

export function stopScroll() {
  if (lenis) lenis.stop()
  else document.body.style.overflow = 'hidden'
}

export function startScroll() {
  if (lenis) lenis.start()
  else document.body.style.overflow = ''
}

export function scrollToTop({ instant = false } = {}) {
  if (lenis) lenis.scrollTo(0, { immediate: instant })
  else window.scrollTo({ top: 0, behavior: instant ? 'instant' : 'smooth' })
}

export function scrollToElement(el) {
  if (lenis) lenis.scrollTo(el, { offset: -110 })
  else el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
