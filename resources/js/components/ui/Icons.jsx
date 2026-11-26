// Inline SVG icons. Stroke icons inherit `currentColor`, so color them with CSS.
const stroke = {
  fill: 'none',
  stroke: 'currentColor',
  strokeWidth: 1.6,
  strokeLinecap: 'round',
  strokeLinejoin: 'round',
  'aria-hidden': true,
  focusable: 'false',
}
const solid = { 'aria-hidden': true, focusable: 'false' }

export const ArrowCircleRight = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} {...props}>
    <circle cx="12" cy="12" r="9.25" />
    <path d="M8 12h7.6M12.6 8.9 15.7 12l-3.1 3.1" />
  </svg>
)

export const ArrowCircleFilled = (props) => (
  <svg viewBox="0 0 24 24" {...solid} {...props}>
    <circle cx="12" cy="12" r="11" fill="currentColor" />
    <path d="M7.4 12h8.4M12.6 8.3 16.2 12l-3.6 3.7" fill="none" stroke="#fff" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" />
  </svg>
)

export const CheckCircle = (props) => (
  <svg viewBox="0 0 24 24" {...solid} {...props}>
    <circle cx="12" cy="12" r="12" fill="currentColor" />
    <path d="m7 12.4 3.3 3.2L17.2 8.6" fill="none" stroke="#fff" strokeWidth="2.6" strokeLinecap="round" strokeLinejoin="round" />
  </svg>
)

export const PhoneRing = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={1.3} {...props}>
    <path d="M21.2 16.4v2.8a1.9 1.9 0 0 1-2 1.9 18.7 18.7 0 0 1-8.2-2.9 18.4 18.4 0 0 1-5.7-5.7 18.7 18.7 0 0 1-2.9-8.2 1.9 1.9 0 0 1 1.9-2h2.8a1.9 1.9 0 0 1 1.9 1.6c.1.9.4 1.8.7 2.7a1.9 1.9 0 0 1-.4 2L8.1 9.8a15.2 15.2 0 0 0 5.7 5.7l1.2-1.2a1.9 1.9 0 0 1 2-.4c.9.3 1.8.6 2.7.7a1.9 1.9 0 0 1 1.6 1.9Z" />
    <path d="M14.6 5.2a4.8 4.8 0 0 1 3.8 3.8M14.6 1.8a8.3 8.3 0 0 1 7.4 7.3" />
  </svg>
)

export const MailAt = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={1.3} {...props}>
    <path d="M3 10v9.6c0 .8.6 1.4 1.4 1.4h15.2c.8 0 1.4-.6 1.4-1.4V10" />
    <path d="m3 10 9 6.6 9-6.6M3 10l2.6-2M21 10l-2.6-2" />
    <path d="M5.6 12.2V3h12.8v9.2" />
    <circle cx="12" cy="8" r="1.9" />
    <path d="M13.9 8v.7a1.3 1.3 0 0 0 2.6 0V8a4.5 4.5 0 1 0-1.8 3.6" />
  </svg>
)

export const MapPin = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={1.3} {...props}>
    <path d="M10 5.5 3.5 7.8v13l6.5-2.3 5 2 5.5-2v-6.3" />
    <path d="M10 5.5v13M15 13.5v7" />
    <path d="M17.3 12.8s-3.7-3.3-3.7-6.1a3.7 3.7 0 0 1 7.4 0c0 2.8-3.7 6.1-3.7 6.1Z" />
    <circle cx="17.3" cy="6.7" r="1.3" />
  </svg>
)

export const ArrowRightLong = (props) => (
  <svg viewBox="0 0 32 24" {...stroke} {...props}>
    <path d="M2 12h27M21.5 5 29 12l-7.5 7" />
  </svg>
)

export const ArrowDownLong = (props) => (
  <svg viewBox="0 0 24 32" {...stroke} {...props}>
    <path d="M12 2v27M5 21.5l7 7.5 7-7.5" />
  </svg>
)

export const ArrowUp = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={2} {...props}>
    <path d="M12 20V5M5 12l7-7 7 7" />
  </svg>
)

export const Star = (props) => (
  <svg viewBox="0 0 24 24" {...solid} {...props}>
    <path fill="currentColor" d="m12 1.8 3.1 6.4 7 .9-5.1 4.9 1.3 7-6.3-3.4-6.3 3.4 1.3-7-5.1-4.9 7-.9Z" />
  </svg>
)

export const MenuIcon = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={2} {...props}>
    <path d="M4 7h16M4 12h16M4 17h16" />
  </svg>
)

export const CloseIcon = (props) => (
  <svg viewBox="0 0 24 24" {...stroke} strokeWidth={2} {...props}>
    <path d="M6 6l12 12M18 6 6 18" />
  </svg>
)

export const EyeIcon = (props) => (
  <svg viewBox="0 0 48 48" {...stroke} strokeWidth={1.6} {...props}>
    <path d="M3 24s7.5-13 21-13 21 13 21 13-7.5 13-21 13S3 24 3 24Z" />
    <circle cx="24" cy="24" r="7.5" />
    <circle cx="24" cy="24" r="2.5" />
  </svg>
)

export const TargetIcon = (props) => (
  <svg viewBox="0 0 48 48" {...stroke} strokeWidth={1.6} {...props}>
    <circle cx="22" cy="26" r="17" />
    <circle cx="22" cy="26" r="10.5" />
    <circle cx="22" cy="26" r="4" />
    <path d="M22 26 40 8M34 8h6v6" />
  </svg>
)

/* Feature icons for the "Why choose us" cards */
const hand = (
  <>
    <path d="M4 40.5h6.6l6.8-3.2h8.2a2.4 2.4 0 0 1 0 4.8h-6.8" />
    <path d="m25.6 42 10.2-4.6a2.4 2.4 0 0 1 2.7 3.9L28.3 48H10.6L4 46.5" />
  </>
)

export const BulbIcon = (props) => (
  <svg viewBox="0 0 48 52" {...stroke} strokeWidth={1.5} {...props}>
    <path d="M24 13.5c-6.3 0-11.4 5-11.4 11.3 0 3.8 1.9 7.2 4.8 9.2 1.3.9 2 2.3 2 3.8V41h9.2v-3.2c0-1.5.8-2.9 2-3.8a11.2 11.2 0 0 0 4.8-9.2c0-6.3-5.1-11.3-11.4-11.3Z" />
    <path d="M19.4 44.5h9.2M20.8 48h6.4M24 41v-8.5M21 29.5l3 3 3-3" />
    <path d="M24 3v5M10.6 8.6l3.1 3.3M37.4 8.6l-3.1 3.3M4 24.8h4.8M39.2 24.8H44M9.8 39.5l3.4-2.6M38.2 39.5l-3.4-2.6" />
  </svg>
)

export const ShieldHandIcon = (props) => (
  <svg viewBox="0 0 48 52" {...stroke} strokeWidth={1.5} {...props}>
    <circle cx="24" cy="16" r="10" />
    <circle cx="24" cy="16" r="6.4" />
    <path d="m21.3 16.2 1.9 1.9 3.6-3.7" />
    <path d="m19.2 24.8-1.6 7 3-1.3 1.9 2.5 1-5.2M28.8 24.8l1.6 7-3-1.3-1.9 2.5-1-5.2" />
    <path d="M7.5 22V6.5M4.5 9.5l3-3 3 3M40.5 22V6.5M37.5 9.5l3-3 3 3" />
    {hand}
  </svg>
)

export const GearHandIcon = (props) => (
  <svg viewBox="0 0 48 52" {...stroke} strokeWidth={1.5} {...props}>
    <circle cx="26" cy="16" r="8.6" />
    <path d="M26 4v3.4M26 24.6V28M14 16h3.4M34.6 16H38M17.5 7.5l2.4 2.4M32.1 22.1l2.4 2.4M17.5 24.5l2.4-2.4M32.1 9.9l2.4-2.4" />
    <circle cx="26" cy="14" r="2.3" />
    <path d="M22 20.6a4.3 4.3 0 0 1 8 0" />
    {hand}
  </svg>
)

export const BuildingsIcon = (props) => (
  <svg viewBox="0 0 48 48" {...stroke} strokeWidth={1.4} {...props}>
    <path d="M5 43h38M8 43V17l9-4v30M17 43V7l12 5v31M29 43V21l10 3v19" />
    <path d="M11.5 20v19M14 19v20M21 13v26M25 14.5v24.5M33 25v14M36 26v13" />
  </svg>
)
