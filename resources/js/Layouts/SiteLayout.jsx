import usePageMotion from '../motion/usePageMotion'
import Preloader from '../motion/Preloader'
import PageCurtain from '../motion/PageCurtain'
import Cursor from '../motion/Cursor'
import ScrollProgress from '../motion/ScrollProgress'
import ScrollManager from '../components/ScrollManager'
import StickyHeader from '../components/StickyHeader'
import ScrollTopButton from '../components/ScrollTopButton'
import TopBar from '../components/TopBar'
import Footer from '../components/Footer'
import WhatsAppButton from '../components/WhatsAppButton'
import { useT } from '../lib/i18n'

export default function SiteLayout({ children }) {
  const t = useT()
  usePageMotion()

  return (
    <>
      <ScrollManager />
      <Preloader />
      <PageCurtain />
      <Cursor />
      <ScrollProgress />
      <a className="skip-link" href="#main">{t("Skip to content")}</a>
      <TopBar />
      <StickyHeader />
      <main id="main">{children}</main>
      <Footer />
      <ScrollTopButton />
      <WhatsAppButton />
    </>
  )
}
