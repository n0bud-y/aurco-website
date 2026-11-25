import { useForm } from '@inertiajs/react'
import { useContent } from '../content/useContent'
import worldMap from '../assets/images/deco-worldmap.png'
import Button from './ui/Button'
import { MailAt, PhoneRing } from './ui/Icons'
import './Contact.css'

const fields = [
  { name: 'name', label: 'Enter Your Name', type: 'text', autoComplete: 'name' },
  { name: 'email', label: 'Enter Your Email', type: 'email', autoComplete: 'email' },
  { name: 'phone', label: 'Phone Number', type: 'tel', autoComplete: 'tel' },
  { name: 'subject', label: 'Subject', type: 'text' },
]

function ContactForm() {
  const { data, setData, post, processing, errors, wasSuccessful, reset } = useForm({
    name: '', email: '', phone: '', subject: '', message: '', website: '',
  })

  function handleSubmit(event) {
    event.preventDefault()
    post('/contact', { preserveScroll: true, onSuccess: () => reset() })
  }

  return (
    <form className="contact-form" onSubmit={handleSubmit}>
      {fields.map((field) => (
        <div className="field" key={field.name}>
          <label className="visually-hidden" htmlFor={`contact-${field.name}`}>{field.label}</label>
          <input
            id={`contact-${field.name}`}
            name={field.name}
            type={field.type}
            placeholder={field.label}
            autoComplete={field.autoComplete}
            value={data[field.name]}
            onChange={(event) => setData(field.name, event.target.value)}
            aria-invalid={Boolean(errors[field.name])}
            aria-describedby={errors[field.name] ? `${field.name}-error` : undefined}
          />
          {errors[field.name] && <span className="field__error" id={`${field.name}-error`}>{errors[field.name]}</span>}
        </div>
      ))}

      <div className="field field--full">
        <label className="visually-hidden" htmlFor="contact-message">Message</label>
        <textarea
          id="contact-message"
          name="message"
          placeholder="Message"
          value={data.message}
          onChange={(event) => setData('message', event.target.value)}
          aria-invalid={Boolean(errors.message)}
          aria-describedby={errors.message ? 'message-error' : undefined}
        />
        {errors.message && <span className="field__error" id="message-error">{errors.message}</span>}
      </div>

      {/* Hidden from people, tempting to bots. */}
      <div className="visually-hidden" aria-hidden="true">
        <label htmlFor="contact-website">Leave this field empty</label>
        <input
          id="contact-website"
          name="website"
          tabIndex={-1}
          autoComplete="off"
          value={data.website}
          onChange={(event) => setData('website', event.target.value)}
        />
      </div>

      <Button type="submit" className="contact-form__submit" disabled={processing}>
        {processing ? 'Sending…' : 'Submit'}
      </Button>
      <p className="contact-form__status" role="status">
        {wasSuccessful ? 'Message sent. Our team will reply within one business day.' : ''}
      </p>
    </form>
  )
}

export default function Contact() {
  const { company, contact } = useContent()

  return (
    <section className="contact section section--pink" id="contact" aria-labelledby="contact-title">
      <img className="deco contact__map" src={worldMap} alt="" aria-hidden="true" data-parallax="0.05" />
      <div className="container contact__grid">
        <aside className="contact__card" aria-label="Contact details" data-reveal="left">
          <PhoneRing className="contact__icon" />
          <p className="contact__label">{contact.callLabel}</p>
          <a className="contact__value" href={company.phoneHref}>{company.phone}</a>
          <MailAt className="contact__icon contact__icon--mail" />
          <p className="contact__label">{contact.writeLabel}</p>
          <a className="contact__value contact__value--email" href={`mailto:${company.email}`}>{company.email}</a>
          {contact.agentImage && <img className="contact__agent" src={contact.agentImage} alt="" loading="lazy" />}
        </aside>

        <div className="contact__panel" data-reveal="right">
          <p className="eyebrow">{contact.eyebrow}</p>
          <h2 className="heading-2 contact__title" id="contact-title">{contact.title}</h2>
          <ContactForm />
        </div>
      </div>
    </section>
  )
}
