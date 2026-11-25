import { useContent } from '../content/useContent'
import houseSketch from '../assets/images/deco-house.png'
import SectionHeading from './ui/SectionHeading'
import ImageCard from './ui/ImageCard'
import Button from './ui/Button'
import './Projects.css'

export default function Projects() {
  const { projects } = useContent()

  return (
    <section className="projects section section--pink" id="projects" aria-labelledby="projects-title">
      <img className="deco projects__deco" src={houseSketch} alt="" aria-hidden="true" data-parallax="0.1" />
      <div className="container">
        <SectionHeading id="projects-title" eyebrow={projects.eyebrow} title={projects.title} text={projects.text} />
      </div>
      <div className="container-wide card-grid">
        {projects.items.map((item, i) => <ImageCard key={item.title} {...item} to="/projects" revealDelay={i * 90} />)}
      </div>
      <div className="section-cta"><Button to="/projects" icon>{projects.cta}</Button></div>
    </section>
  )
}
