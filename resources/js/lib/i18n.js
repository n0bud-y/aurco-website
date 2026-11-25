import { usePage } from '@inertiajs/react'
import ar from '../lang/ar'

/**
 * Fixed interface text (buttons, labels, headings written in the components).
 * Content managed in the CRM is translated on the server instead — see app/Support/Translator.php.
 */
const dictionaries = { ar }

export function useLocale() {
  return usePage().props.locale ?? 'en'
}

/** Returns t(text, vars): the translation for the current language, or the English text. */
export function useT() {
  const locale = useLocale()
  const dict = dictionaries[locale]

  return (text, vars) => {
    let out = (dict && dict[text]) || text
    if (vars) {
      Object.entries(vars).forEach(([key, value]) => {
        out = out.replaceAll(`{${key}}`, value)
      })
    }
    return out
  }
}

/** Switching language reloads the page so the server can send the other language's content. */
export function switchLocale(locale) {
  window.location.href = `/locale/${locale}`
}
