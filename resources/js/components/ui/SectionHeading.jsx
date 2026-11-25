export default function SectionHeading({ eyebrow, title, text, id }) {
  return (
    <div className="section-head">
      <p className="eyebrow" data-reveal="up">{eyebrow}</p>
      <h2 className="heading-2" id={id} data-anim="lines">{title}</h2>
      {text && <p className="section-head__text" data-reveal="up" data-reveal-delay="170">{text}</p>}
    </div>
  )
}
