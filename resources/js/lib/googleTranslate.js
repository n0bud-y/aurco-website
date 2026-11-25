import './googleTranslate.css'

/**
 * Machine-translates the English site into Arabic with the Google Translate
 * website widget. The chosen language lives in Google's own `googtrans`
 * cookie, so it survives reloads and the widget re-applies it on load.
 */

const TARGET = 'ar'
const CONTAINER_ID = 'google_translate_element'

let widgetPromise = null

export function getLanguage() {
  return /(?:^|;\s*)googtrans=\/en\/ar(?:;|$)/.test(document.cookie) ? 'ar' : 'en'
}

/** Google writes the cookie on the bare host and the parent domain, so clear/set every variant. */
function writeCookie(value) {
  const host = window.location.hostname
  const parts = host.split('.')
  const domains = ['', host, `.${host}`]
  if (parts.length > 2) domains.push(`.${parts.slice(-2).join('.')}`)

  const suffix = value ? '; max-age=31536000' : '; expires=Thu, 01 Jan 1970 00:00:00 GMT'
  domains.forEach((domain) => {
    document.cookie = `googtrans=${value}; path=/${domain ? `; domain=${domain}` : ''}${suffix}`
  })
}

function loadWidget() {
  if (widgetPromise) return widgetPromise

  widgetPromise = new Promise((resolve) => {
    if (!document.getElementById(CONTAINER_ID)) {
      const container = document.createElement('div')
      container.id = CONTAINER_ID
      document.body.appendChild(container)
    }

    window.googleTranslateElementInit = () => {
      new window.google.translate.TranslateElement(
        { pageLanguage: 'en', includedLanguages: TARGET, autoDisplay: false },
        CONTAINER_ID,
      )
      resolve()
    }

    const script = document.createElement('script')
    script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'
    script.async = true
    document.head.appendChild(script)
  })

  return widgetPromise
}

export function setLanguage(lang) {
  if (lang === getLanguage()) return

  if (lang === 'en') {
    // Restoring the original text is only reliable with a fresh page load.
    writeCookie('')
    window.location.reload()
    return
  }

  writeCookie(`/en/${TARGET}`)
  const combo = document.querySelector('.goog-te-combo')
  if (combo) {
    combo.value = TARGET
    combo.dispatchEvent(new Event('change'))
  } else {
    // With the cookie set, the widget translates the page as soon as it loads.
    loadWidget()
  }
}

/**
 * Google Translate swaps text nodes for <font> wrappers, which makes React throw
 * "removeChild/insertBefore: not a child of this node" on the next re-render or
 * Inertia visit. Let those calls fail softly instead of crashing the app.
 */
function patchDomForTranslation() {
  const { removeChild, insertBefore } = Node.prototype

  Node.prototype.removeChild = function (child) {
    if (child.parentNode !== this) return child
    return removeChild.call(this, child)
  }

  Node.prototype.insertBefore = function (node, reference) {
    if (reference && reference.parentNode !== this) return node
    return insertBefore.call(this, node, reference)
  }
}

export function initTranslation() {
  patchDomForTranslation()
  if (getLanguage() === TARGET) loadWidget()
}
