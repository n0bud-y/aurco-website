import { Head } from '@inertiajs/react'
import PageHero from '../components/PageHero'
import Button from '../components/ui/Button'
import { useT } from '../lib/i18n'

export default function NotFound() {
  const t = useT()
  return (
    <>
      <Head title={t("Page not found")} />
      <PageHero
        title={t("Page not found")}
        text={t("That page doesn't exist or has moved. Head back to the home page or get in touch and we'll point you the right way.")}
        crumb="404"
      >
        <Button to="/" icon>{t("Back to home")}</Button>
      </PageHero>
    </>
  )
}
