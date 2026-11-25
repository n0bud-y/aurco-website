import { Link } from '@inertiajs/react'

/** Inertia link for internal paths, a plain anchor for external, mailto: and tel: links. */
export default function SmartLink({ href = '#', children, ...rest }) {
  if (href.startsWith('/') && !href.startsWith('//')) {
    return <Link href={href} {...rest}>{children}</Link>
  }

  const external = /^https?:\/\//.test(href)
  return (
    <a href={href} {...(external ? { target: '_blank', rel: 'noopener noreferrer' } : {})} {...rest}>
      {children}
    </a>
  )
}
