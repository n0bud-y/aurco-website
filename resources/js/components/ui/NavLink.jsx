import { Link, usePage } from '@inertiajs/react'
import { scrollToElement } from '../../motion/smoothScroll'

/** Inertia link, or a smooth in-page scroll when the target is on this page. */
export default function NavLink({ link, className = '', onNavigate }) {
  const { url } = usePage()
  const [path, hash] = link.to.split('#')
  const currentPath = url.split('?')[0].split('#')[0]
  const active = link.match === currentPath
  const classes = [className, active ? 'is-active' : ''].filter(Boolean).join(' ')

  if (hash && (path === '' || path === currentPath)) {
    return (
      <a
        href={`#${hash}`}
        className={classes}
        onClick={(event) => {
          event.preventDefault()
          const target = document.getElementById(hash)
          if (target) scrollToElement(target)
          onNavigate?.()
        }}
      >
        {link.label}
      </a>
    )
  }

  return (
    <Link href={link.to} className={classes} aria-current={active ? 'page' : undefined} onClick={() => onNavigate?.()}>
      {link.label}
    </Link>
  )
}
