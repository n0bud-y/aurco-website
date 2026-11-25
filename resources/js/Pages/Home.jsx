import { Head } from '@inertiajs/react'
import Hero from '../components/Hero'
import About from '../components/About'
import StatsStrip from '../components/StatsStrip'
import Services from '../components/Services'
import WhyChooseUs from '../components/WhyChooseUs'
import Projects from '../components/Projects'
import Clients from '../components/Clients'
import CtaBanner from '../components/CtaBanner'
import Testimonials from '../components/Testimonials'
import Faq from '../components/Faq'
import Blog from '../components/Blog'
import Contact from '../components/Contact'

export default function Home() {
  return (
    <>
      <Head title="Construction, Equipment Rental & Facility Management in Saudi Arabia" />
      <Hero />
      <About />
      <StatsStrip />
      <Services />
      <WhyChooseUs />
      <Projects />
      <Clients />
      <CtaBanner />
      <Testimonials />
      <Faq />
      <Blog />
      <Contact />
    </>
  )
}
