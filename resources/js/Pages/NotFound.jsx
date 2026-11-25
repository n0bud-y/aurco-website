import { Head } from '@inertiajs/react'
import PageHero from '../components/PageHero'
import Button from '../components/ui/Button'

export default function NotFound() {
  return (
    <>
      <Head title="Page not found" />
      <PageHero
        title="Page not found"
        text="That page doesn't exist or has moved. Head back to the home page or get in touch and we'll point you the right way."
        crumb="404"
      >
        <Button to="/" icon>Back to home</Button>
      </PageHero>
    </>
  )
}
