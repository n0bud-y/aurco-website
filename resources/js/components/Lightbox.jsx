import { useEffect, useRef, useState } from 'react'
import { ArrowRightLong, CloseIcon } from './ui/Icons'
import './Lightbox.css'

// The bundled photos come from the design mockup, so they are capped at a gentle
// upscale. Raise this once you swap in the full-resolution originals.
const MAX_UPSCALE = 1.6

export default function Lightbox({ items, index, onClose, onNavigate }) {
  const dialogRef = useRef(null)
  const [size, setSize] = useState(null)
  const open = index >= 0 && index < items.length

  useEffect(() => {
    const dialog = dialogRef.current
    if (!dialog) return
    if (open && !dialog.open) dialog.showModal()
    if (!open && dialog.open) dialog.close()
  }, [open])

  const item = open ? items[index] : null
  const go = (step) => onNavigate((index + step + items.length) % items.length)

  const handleKeyDown = (event) => {
    if (!open) return
    if (event.key === 'ArrowRight') { event.preventDefault(); go(1) }
    if (event.key === 'ArrowLeft') { event.preventDefault(); go(-1) }
  }

  return (
    <dialog
      className="lightbox"
      ref={dialogRef}
      aria-label="Photo viewer"
      onClose={onClose}
      onKeyDown={handleKeyDown}
      onClick={(event) => { if (event.target === event.currentTarget) onClose() }}
    >
      <button type="button" className="lightbox__close" onClick={onClose}>
        <CloseIcon /><span className="visually-hidden">Close</span>
      </button>
      {items.length > 1 && (
        <>
          <button type="button" className="lightbox__nav lightbox__nav--prev" onClick={() => go(-1)}>
            <ArrowRightLong /><span className="visually-hidden">Previous photo</span>
          </button>
          <button type="button" className="lightbox__nav lightbox__nav--next" onClick={() => go(1)}>
            <ArrowRightLong /><span className="visually-hidden">Next photo</span>
          </button>
        </>
      )}
      {item && (
        <figure
          className="lightbox__figure"
          style={size ? { width: `min(${Math.round(size.w * MAX_UPSCALE)}px, 88vw, ${(78 * size.w / size.h).toFixed(1)}vh)` } : undefined}
        >
          <div className="lightbox__media">
            <img
              src={item.src}
              alt={item.caption}
              onLoad={(event) => setSize({ w: event.currentTarget.naturalWidth, h: event.currentTarget.naturalHeight })}
            />
          </div>
          <figcaption>
            {item.caption}
            <span>{index + 1} / {items.length}</span>
          </figcaption>
        </figure>
      )}
    </dialog>
  )
}
