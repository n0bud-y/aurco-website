/** Lets the hero timeline wait until the intro loader has finished. */
let resolveIntro
export const introReady = new Promise((resolve) => { resolveIntro = resolve })

export function completeIntro() {
  resolveIntro?.()
  resolveIntro = null
}

export function introAlreadySeen() {
  try { return sessionStorage.getItem('aurco-intro') === '1' } catch { return false }
}

export function markIntroSeen() {
  try { sessionStorage.setItem('aurco-intro', '1') } catch { /* private mode */ }
}
