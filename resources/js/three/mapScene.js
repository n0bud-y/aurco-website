import * as THREE from 'three'
import { gsap } from 'gsap'

/**
 * Regional presence: the Kingdom extruded in 3D with a dotted surface, a pin per
 * location and light arcs flowing out of the head office.
 *
 * mountMapScene(container, { outline, locations, labels, reduced }) → { reveal, highlight, dispose }
 *   outline   [[lng, lat], ...]
 *   locations [{ lat, lng, hq }]  (same order as the HTML labels)
 *   labels    HTMLElement[]       positioned over each pin every frame
 */

const RED = 0xc8161e
const NAVY = 0x1b2a44

const CENTER = { lng: 45.1, lat: 24.2 }
const SCALE = 0.62
const COS = Math.cos((CENTER.lat * Math.PI) / 180)
const project = (lng, lat) => new THREE.Vector2((lng - CENTER.lng) * SCALE * COS, (lat - CENTER.lat) * SCALE)

function inside(point, polygon) {
  let hit = false
  for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
    const a = polygon[i]
    const b = polygon[j]
    if ((a.y > point.y) !== (b.y > point.y) && point.x < ((b.x - a.x) * (point.y - a.y)) / (b.y - a.y) + a.x) hit = !hit
  }
  return hit
}

export function mountMapScene(container, { outline, locations, labels = [], reduced = false }) {
  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true })
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.75))
  renderer.outputColorSpace = THREE.SRGBColorSpace
  renderer.domElement.setAttribute('aria-hidden', 'true')
  container.appendChild(renderer.domElement)

  const scene = new THREE.Scene()
  const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 100)
  const target = new THREE.Vector3(0, 0, 0.4)

  scene.add(new THREE.HemisphereLight(0xf3f6ff, 0x1a2233, 1.3))
  const key = new THREE.DirectionalLight(0xffffff, 2)
  key.position.set(-6, 14, 10)
  scene.add(key)
  const redLight = new THREE.PointLight(RED, 18, 16, 1.5)
  scene.add(redLight)

  /* ---------- The Kingdom ---------- */
  const shapePoints = outline.map(([lng, lat]) => project(lng, lat))
  const shape = new THREE.Shape(shapePoints)
  const depth = 0.55
  const landGeometry = new THREE.ExtrudeGeometry(shape, { depth, bevelEnabled: true, bevelThickness: 0.06, bevelSize: 0.05, bevelSegments: 2 })
  const land = new THREE.Mesh(landGeometry, [
    new THREE.MeshStandardMaterial({ color: NAVY, metalness: 0.35, roughness: 0.55 }),
    new THREE.MeshStandardMaterial({ color: RED, metalness: 0.3, roughness: 0.4, emissive: RED, emissiveIntensity: 0.25 }),
  ])
  const map = new THREE.Group()
  map.rotation.x = -Math.PI / 2 // shape is drawn in X/Y; lay it on the ground
  map.add(land)
  scene.add(map)

  // Glowing coastline / border on top of the slab
  const edgePoints = shapePoints.map((p) => new THREE.Vector3(p.x, p.y, depth + 0.07))
  edgePoints.push(edgePoints[0].clone())
  const edge = new THREE.Line(new THREE.BufferGeometry().setFromPoints(edgePoints), new THREE.LineBasicMaterial({ color: 0xff8a90, transparent: true, opacity: 0.9 }))
  map.add(edge)

  // Dotted surface
  const bounds = new THREE.Box2().setFromPoints(shapePoints)
  const step = 0.32
  const dots = []
  for (let x = bounds.min.x; x <= bounds.max.x; x += step) {
    for (let y = bounds.min.y; y <= bounds.max.y; y += step) {
      const p = new THREE.Vector2(x, y)
      if (inside(p, shapePoints)) dots.push(x, y, depth + 0.075)
    }
  }
  const dotGeometry = new THREE.BufferGeometry()
  dotGeometry.setAttribute('position', new THREE.Float32BufferAttribute(dots, 3))
  const dotMaterial = new THREE.PointsMaterial({ color: 0xffffff, size: 0.045, transparent: true, opacity: 0.38, depthWrite: false })
  map.add(new THREE.Points(dotGeometry, dotMaterial))

  /* ---------- Pins ---------- */
  const top = depth + 0.08
  const pinGroup = new THREE.Group()
  map.add(pinGroup)
  const stemGeometry = new THREE.CylinderGeometry(0.035, 0.035, 1, 8)
  const headGeometry = new THREE.SphereGeometry(1, 20, 14)
  const ringGeometry = new THREE.RingGeometry(0.75, 1, 40)
  const redMat = new THREE.MeshStandardMaterial({ color: RED, emissive: RED, emissiveIntensity: 0.6, roughness: 0.3 })
  const whiteMat = new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: 0xffffff, emissiveIntensity: 0.25, roughness: 0.3 })

  const pins = locations.map((loc) => {
    const p = project(loc.lng, loc.lat)
    const height = loc.hq ? 1.5 : 1
    const pin = new THREE.Group()
    pin.position.set(p.x, p.y, top)

    const stem = new THREE.Mesh(stemGeometry, loc.hq ? redMat : whiteMat)
    stem.scale.y = height
    stem.rotation.x = Math.PI / 2
    stem.position.z = height / 2
    const head = new THREE.Mesh(headGeometry, loc.hq ? redMat : whiteMat)
    head.scale.setScalar(loc.hq ? 0.17 : 0.11)
    head.position.z = height
    const ring = new THREE.Mesh(ringGeometry, new THREE.MeshBasicMaterial({ color: loc.hq ? RED : 0xff8a90, transparent: true, opacity: 0.7, side: THREE.DoubleSide, depthWrite: false }))
    ring.scale.setScalar(0.18)
    ring.position.z = 0.01
    pin.add(stem, head, ring)
    pinGroup.add(pin)
    return { pin, head, ring, height, loc, phase: Math.random() * Math.PI * 2 }
  })

  /* ---------- Arcs from the head office ---------- */
  const hq = pins.find((p) => p.loc.hq) ?? pins[0]
  const arcs = []
  const travellerGeometry = new THREE.SphereGeometry(0.06, 10, 8)
  const travellerMat = new THREE.MeshBasicMaterial({ color: 0xffd1d3 })
  pins.forEach((p) => {
    if (!hq || p === hq) return
    const a = hq.pin.position.clone()
    const b = p.pin.position.clone()
    const mid = a.clone().add(b).multiplyScalar(0.5)
    mid.z += a.distanceTo(b) * 0.38 + 0.4
    const curve = new THREE.QuadraticBezierCurve3(a, mid, b)
    const geometry = new THREE.BufferGeometry().setFromPoints(curve.getPoints(64))
    const line = new THREE.Line(geometry, new THREE.LineBasicMaterial({ color: 0xff5a62, transparent: true, opacity: 0.85 }))
    geometry.setDrawRange(0, reduced ? 65 : 0)
    map.add(line)
    const traveller = new THREE.Mesh(travellerGeometry, travellerMat)
    traveller.visible = false
    map.add(traveller)
    arcs.push({ curve, geometry, line, traveller, offset: Math.random(), drawn: { n: reduced ? 65 : 0 } })
  })

  /* ---------- Layout and camera ---------- */
  const state = { px: 0, py: 0, tx: 0, ty: 0, highlight: -1 }
  let width = 1
  let height = 1
  const resize = () => {
    width = container.clientWidth || 1
    height = container.clientHeight || 1
    renderer.setSize(width, height, false)
    camera.aspect = width / height
    camera.updateProjectionMatrix()
    render()
  }
  // Camera focus: { x, z, zoom } eased toward the selected pin, or back to the whole map.
  const focus = { x: 0, z: 0.4, zoom: 1 }
  const pinWorld = new THREE.Vector3()
  let focusTween = null
  const focusOn = (index) => {
    const p = pins[index]
    let to = { x: 0, z: 0.4, zoom: 1 }
    if (p) {
      p.pin.getWorldPosition(pinWorld)
      to = { x: pinWorld.x, z: pinWorld.z, zoom: 0.55 }
    }
    focusTween?.kill()
    focusTween = gsap.to(focus, {
      ...to, duration: reduced ? 0 : 1.4, ease: 'power3.inOut',
      onUpdate: () => { if (reduced || !running) render() },
    })
  }

  const placeCamera = () => {
    // The Kingdom is ~12 × 10 units; keep all of it (and the tallest pins) in frame.
    const narrow = camera.aspect < 1
    const dist = (narrow ? 25 : 21) * focus.zoom
    target.set(focus.x, 0, focus.z)
    camera.position.set(focus.x + 1.2 * focus.zoom + state.px * 1.6, dist * 0.8 - state.py * 1, focus.z + dist * 0.6)
    camera.lookAt(target)
  }

  const screen = new THREE.Vector3()
  const placeLabels = () => {
    // Project each pin head to the screen; the head office keeps its spot, others move out of the way.
    const placed = pins.map((p, i) => {
      p.head.getWorldPosition(screen)
      screen.project(camera)
      const el = labels[i]
      const w = el?.firstElementChild?.offsetWidth ?? 60
      return { i, x: (screen.x * 0.5 + 0.5) * width, y: (-screen.y * 0.5 + 0.5) * height, w, dy: 0, hq: p.loc.hq }
    })
    const order = [...placed].sort((a, b) => (b.hq ? 1 : 0) - (a.hq ? 1 : 0) || a.y - b.y)
    const LABEL_H = 26
    order.forEach((a, k) => {
      for (let tries = 0; tries < 6; tries++) {
        const hit = order.slice(0, k).some((b) =>
          Math.abs(a.x - b.x) < (a.w + b.w) / 2 + 6 && Math.abs((a.y + a.dy) - (b.y + b.dy)) < LABEL_H)
        if (!hit) break
        a.dy += LABEL_H
      }
    })
    placed.forEach(({ i, x, y, dy }) => {
      const el = labels[i]
      if (!el) return
      el.style.transform = `translate3d(${x.toFixed(1)}px, ${y.toFixed(1)}px, 0)`
      el.style.setProperty('--dy', `${dy}px`)
      // Pins pushed off the map area (e.g. while zoomed in on a city) hide their label.
      const inside = x > 10 && x < width - 10 && y > 24 && y < height - 4
      el.style.opacity = inside ? '' : '0'
    })
  }

  const render = () => {
    placeCamera()
    renderer.render(scene, camera)
    placeLabels()
  }

  /* ---------- Reveal ---------- */
  let revealed = reduced
  const intro = gsap.timeline({ paused: true })
  if (!reduced) {
    map.position.y = -1.4
    land.scale.z = 0.01
    dotMaterial.opacity = 0
    edge.material.opacity = 0
    pins.forEach(({ pin }) => { pin.scale.setScalar(0.001) })
    intro
      .to(map.position, { y: 0, duration: 1.4, ease: 'power3.out' }, 0)
      .to(land.scale, { z: 1, duration: 1.2, ease: 'power3.out' }, 0.1)
      .to(edge.material, { opacity: 0.9, duration: 1 }, 0.5)
      .to(dotMaterial, { opacity: 0.38, duration: 1.2 }, 0.6)
    pins.forEach(({ pin }, i) => {
      intro.to(pin.scale, { x: 1, y: 1, z: 1, duration: 0.7, ease: 'back.out(2.4)' }, 1 + i * 0.09)
    })
    arcs.forEach((arc, i) => {
      intro.to(arc.drawn, {
        n: 65, duration: 1.1, ease: 'power2.inOut',
        onUpdate: () => arc.geometry.setDrawRange(0, Math.round(arc.drawn.n)),
      }, 1.4 + i * 0.1)
    })
    intro.eventCallback('onComplete', () => { arcs.forEach((a) => { a.traveller.visible = true }) })
  }

  /* ---------- Loop ---------- */
  const clock = new THREE.Clock()
  let frame = 0
  let running = false
  const tick = () => {
    if (!running) return
    const t = clock.getElapsedTime()
    state.px += (state.tx - state.px) * 0.05
    state.py += (state.ty - state.py) * 0.05
    map.rotation.z = Math.sin(t * 0.25) * 0.05
    redLight.position.set(hq.pin.position.x, 2.2, -hq.pin.position.y)
    pins.forEach((p, i) => {
      const k = (t * 0.6 + p.phase) % 1
      const s = 0.18 + k * (p.loc.hq ? 0.9 : 0.6)
      p.ring.scale.setScalar(s)
      p.ring.material.opacity = (1 - k) * (state.highlight === i || p.loc.hq ? 0.95 : 0.6)
      const emphasis = state.highlight === i ? 1.6 : 1
      const base = p.loc.hq ? 0.17 : 0.11
      p.head.scale.setScalar(base * emphasis)
    })
    if (revealed) {
      arcs.forEach((arc) => {
        if (!arc.traveller.visible) return
        const u = (t * 0.35 + arc.offset) % 1
        arc.traveller.position.copy(arc.curve.getPoint(u))
      })
    }
    render()
    frame = requestAnimationFrame(tick)
  }
  const play = () => {
    if (running || reduced) return
    running = true
    frame = requestAnimationFrame(tick)
  }
  const pause = () => {
    running = false
    cancelAnimationFrame(frame)
  }

  const onPointer = (event) => {
    const rect = container.getBoundingClientRect()
    state.tx = ((event.clientX - rect.left) / rect.width) * 2 - 1
    state.ty = ((event.clientY - rect.top) / rect.height) * 2 - 1
  }
  const visibility = new IntersectionObserver(([entry]) => (entry.isIntersecting ? play() : pause()))
  visibility.observe(container)
  const resizeObserver = new ResizeObserver(resize)
  resizeObserver.observe(container)
  container.addEventListener('pointermove', onPointer, { passive: true })
  resize()

  return {
    reveal() {
      if (revealed) return
      revealed = true
      intro.play()
    },
    /** Fly the camera to a location (index) or back to the whole Kingdom (-1). */
    focus(index) {
      state.highlight = index
      focusOn(index)
    },
    highlight(index) {
      state.highlight = index
      if (reduced) render()
    },
    dispose() {
      pause()
      intro.kill()
      focusTween?.kill()
      visibility.disconnect()
      resizeObserver.disconnect()
      container.removeEventListener('pointermove', onPointer)
      scene.traverse((obj) => {
        obj.geometry?.dispose?.()
        const m = obj.material
        if (Array.isArray(m)) m.forEach((x) => x.dispose())
        else m?.dispose?.()
      })
      renderer.dispose()
      renderer.domElement.remove()
    },
  }
}
