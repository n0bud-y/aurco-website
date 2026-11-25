import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import '@fontsource/manrope/600.css'
import '@fontsource/manrope/700.css'
import '@fontsource/dm-sans/300.css'
import '@fontsource/dm-sans/400.css'
import '@fontsource/dm-sans/500.css'
import '@fontsource/dm-sans/600.css'
import '@fontsource/dm-sans/700.css'
import 'lenis/dist/lenis.css'
import './styles/global.css'
import './motion/motion.css'
import { initSmoothScroll } from './motion/smoothScroll'
import SiteLayout from './Layouts/SiteLayout'
import { initTranslation } from './lib/googleTranslate'

initSmoothScroll()
initTranslation()

createInertiaApp({
  title: (title) => (title ? `${title} | AURCO Contracting` : 'AURCO Contracting'),
  resolve: async (name) => {
    const page = await resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx'))

    // One persistent shell, so smooth scrolling and motion survive page changes.
    page.default.layout ??= (content) => <SiteLayout>{content}</SiteLayout>

    return page
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <StrictMode>
        <App {...props} />
      </StrictMode>,
    )
  },
  progress: { color: '#c8161e', showSpinner: false },
})
