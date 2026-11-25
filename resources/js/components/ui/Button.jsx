import { Link } from '@inertiajs/react'
import { ArrowCircleRight } from './Icons'

/** `to` renders a router Link, `href` an anchor, otherwise a button. */
export default function Button({ to, href, variant = 'primary', icon = false, className = '', children, type = 'button', ...rest }) {
  const classes = ['btn', `btn--${variant}`, className].filter(Boolean).join(' ')
  const content = (
    <>
      {children}
      {icon && <ArrowCircleRight className="btn__icon" />}
    </>
  )
  if (to) return <Link href={to} className={classes} data-magnetic="0.3" {...rest}>{content}</Link>
  if (href) return <a href={href} className={classes} data-magnetic="0.3" {...rest}>{content}</a>
  return <button type={type} className={classes} data-magnetic="0.3" {...rest}>{content}</button>
}
