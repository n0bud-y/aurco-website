import { useContent } from '../content/useContent'
import SmartLink from './ui/SmartLink'
import './TopBar.css'
import { useT } from '../lib/i18n'

export default function TopBar() {
  const t = useT()
  const { footer } = useContent()
  const links = footer.topLinks ?? []
  if (links.length === 0) return null

  return (
    <div className="topbar">
      <nav aria-label={t("AURCO group")}>
        <ul className="topbar__list">
          {links.map((link) => (
            <li key={link.label}><SmartLink className="topbar__link" href={link.href}>{link.label}</SmartLink></li>
          ))}
        </ul>
      </nav>
    </div>
  )
}
