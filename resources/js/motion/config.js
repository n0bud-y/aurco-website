export const EASE = 'power3.out'
export const EASE_IN_OUT = 'power4.inOut'

export const prefersReducedMotion = () =>
  typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches

export const isFinePointer = () =>
  typeof window !== 'undefined' && window.matchMedia('(pointer: fine)').matches
