import { useContent } from '../content/useContent'
import SectionHeading from './ui/SectionHeading'
import { ArrowCircleFilled } from './ui/Icons'
import SmartLink from './ui/SmartLink'
import './Blog.css'

export default function Blog() {
  const { blog } = useContent()

  return (
    <section className="blog section" id="blog" aria-labelledby="blog-title">
      <div className="container">
        <SectionHeading id="blog-title" eyebrow={blog.eyebrow} title={blog.title} text={blog.text} />
        <div className="blog__grid">
          {blog.posts.map((post, i) => (
            <article className="post-card" key={post.title} data-reveal="up" data-reveal-delay={i * 90}>
              {post.image && <img className="post-card__img" src={post.image} alt="" loading="lazy" decoding="async" />}
              <div className="post-card__body">
                <h3 className="post-card__title">{post.title}</h3>
                {post.excerpt && <p className="post-card__excerpt">{post.excerpt}</p>}
                <SmartLink className="post-card__more" href={post.href}>
                  Read more<span className="visually-hidden">: {post.title}</span>
                  <ArrowCircleFilled />
                </SmartLink>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  )
}
