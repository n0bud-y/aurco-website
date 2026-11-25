import { useEffect } from 'react'
import { usePage } from '@inertiajs/react'
import { scrollToElement, scrollToTop } from '../motion/smoothScroll'

/** Scrolls to the top on page changes, or to the hash target when there is one. */
export default function ScrollManager() {
  const { url } = usePage()

  useEffect(() => {
    const hash = url.includes('#') ? url.split('#')[1] : ''

    if (hash) {
      const timer = setTimeout(() => {
        const target = document.getElementById(decodeURIComponent(hash))
        if (target) scrollToElement(target)
      }, 80)
      return () => clearTimeout(timer)
    }

    scrollToTop({ instant: true })
    return undefined
  }, [url])

  return null
}
