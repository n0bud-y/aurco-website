import { FaWhatsapp } from 'react-icons/fa'
import { useContent } from '../content/useContent'
import './WhatsAppButton.css'

export default function WhatsAppButton() {
  const { company } = useContent()

  return (
    <a className="whatsapp" data-magnetic="0.25" href={company.whatsapp} target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
      <FaWhatsapp aria-hidden="true" />
    </a>
  )
}
