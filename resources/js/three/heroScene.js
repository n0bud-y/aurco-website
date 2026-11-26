import * as THREE from 'three'
import { gsap } from 'gsap'

/**
 * Home hero: a steel portal-frame warehouse, a pipe rack and a lattice tower
 * that assemble member by member, then drift with scroll and pointer.
 *
 * mountHeroScene(container, { rtl, reduced, compact }) → { build, setScroll, dispose }
 */

const RED = 0xc8161e
const STEEL = 0xd7dde5
const DARK = 0x46546b

export function mountHeroScene(container, { rtl = false, reduced = false, compact = false } = {}) {
  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' })
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, compact ? 1.5 : 1.75))
  renderer.outputColorSpace = THREE.SRGBColorSpace
  renderer.toneMapping = THREE.ACESFilmicToneMapping
  renderer.toneMappingExposure = 1.05
  renderer.domElement.setAttribute('aria-hidden', 'true')
  container.appendChild(renderer.domElement)

  const scene = new THREE.Scene()
  scene.fog = new THREE.Fog(0x0e1628, 34, 78)

  const camera = new THREE.PerspectiveCamera(36, 1, 0.1, 200)
  const target = new THREE.Vector3(1.5, 4.2, -1)

  /* ---------- Light ---------- */
  scene.add(new THREE.HemisphereLight(0xdfe8ff, 0x141c2b, 1.1))
  const sun = new THREE.DirectionalLight(0xffffff, 2.4)
  sun.position.set(14, 26, 12)
  scene.add(sun)
  const rim = new THREE.DirectionalLight(0xff6a70, 1.4)
  rim.position.set(-18, 8, -14)
  scene.add(rim)
  const glow = new THREE.PointLight(RED, 60, 34, 1.6)
  glow.position.set(0, 1.2, 0)
  scene.add(glow)

  /* ---------- Materials ---------- */
  const mats = {
    red: new THREE.MeshStandardMaterial({ color: RED, metalness: 0.45, roughness: 0.38 }),
    steel: new THREE.MeshStandardMaterial({ color: STEEL, metalness: 0.65, roughness: 0.32 }),
    dark: new THREE.MeshStandardMaterial({ color: DARK, metalness: 0.5, roughness: 0.45 }),
    pipeWhite: new THREE.MeshStandardMaterial({ color: 0xf2f4f7, metalness: 0.4, roughness: 0.3 }),
  }

  /* ---------- Geometry helpers ---------- */
  const unitBox = new THREE.BoxGeometry(1, 1, 1)
  const unitCyl = new THREE.CylinderGeometry(1, 1, 1, 14)
  const up = new THREE.Vector3(0, 1, 0)
  const members = [] // { mesh, order }
  const world = new THREE.Group()
  scene.add(world)

  /** A straight member between two points (box section by default). */
  const member = (a, b, size, mat, order, round = false) => {
    const from = new THREE.Vector3(...a)
    const to = new THREE.Vector3(...b)
    const dir = to.clone().sub(from)
    const len = dir.length()
    const mesh = new THREE.Mesh(round ? unitCyl : unitBox, mat)
    mesh.scale.set(round ? size : size, len, round ? size : size)
    mesh.position.copy(from).add(to).multiplyScalar(0.5)
    mesh.quaternion.setFromUnitVectors(up, dir.normalize())
    world.add(mesh)
    members.push({ mesh, order })
    return mesh
  }

  /* Warehouse: 5 bays of portal frames */
  const bays = compact ? 4 : 5
  const bay = 3.6
  const span = 5
  const eave = 6.4
  const ridge = 8.4
  const x0 = -((bays * bay) / 2) - 1.5
  for (let i = 0; i <= bays; i++) {
    const x = x0 + i * bay
    member([x, 0, -span], [x, eave, -span], 0.36, mats.red, i * 0.6)
    member([x, 0, span], [x, eave, span], 0.36, mats.red, i * 0.6)
    member([x, eave, -span], [x, ridge, 0], 0.3, mats.red, 3 + i * 0.6)
    member([x, eave, span], [x, ridge, 0], 0.3, mats.red, 3 + i * 0.6)
  }
  for (let i = 0; i < bays; i++) {
    const xa = x0 + i * bay
    const xb = xa + bay
    member([xa, eave, -span], [xb, eave, -span], 0.2, mats.steel, 7 + i * 0.3)
    member([xa, eave, span], [xb, eave, span], 0.2, mats.steel, 7 + i * 0.3)
    member([xa, ridge, 0], [xb, ridge, 0], 0.2, mats.steel, 7.5 + i * 0.3)
    for (const t of [0.33, 0.66]) {
      const y = eave + (ridge - eave) * t
      const z = span * (1 - t)
      member([xa, y, -z], [xb, y, -z], 0.12, mats.steel, 9 + i * 0.25)
      member([xa, y, z], [xb, y, z], 0.12, mats.steel, 9 + i * 0.25)
    }
  }
  // X-bracing in the end bays
  for (const i of [0, bays - 1]) {
    const xa = x0 + i * bay
    const xb = xa + bay
    for (const z of [-span, span]) {
      member([xa, 0.2, z], [xb, eave - 0.2, z], 0.09, mats.dark, 11)
      member([xb, 0.2, z], [xa, eave - 0.2, z], 0.09, mats.dark, 11)
    }
  }

  /* Pipe rack in front of the warehouse */
  const rackZ = -(span + 4.2) // behind the warehouse, so it never crosses the headline
  const rackFrames = compact ? 4 : 6
  for (let i = 0; i < rackFrames; i++) {
    const x = x0 + 0.5 + i * 3.4
    member([x, 0, rackZ - 1.2], [x, 4.4, rackZ - 1.2], 0.24, mats.dark, 12 + i * 0.3)
    member([x, 0, rackZ + 1.2], [x, 4.4, rackZ + 1.2], 0.24, mats.dark, 12 + i * 0.3)
    member([x, 2.6, rackZ - 1.2], [x, 2.6, rackZ + 1.2], 0.18, mats.dark, 13 + i * 0.3)
    member([x, 4.4, rackZ - 1.2], [x, 4.4, rackZ + 1.2], 0.18, mats.dark, 13 + i * 0.3)
  }
  const rackLen = (rackFrames - 1) * 3.4
  const pipeColors = [mats.red, mats.pipeWhite, mats.steel, mats.red]
  pipeColors.forEach((mat, j) => {
    const z = rackZ - 0.9 + j * 0.6
    member([x0 - 0.6, 2.95, z], [x0 + 1.1 + rackLen, 2.95, z], 0.22, mat, 15 + j * 0.25, true)
  })
  ;[mats.pipeWhite, mats.red].forEach((mat, j) => {
    const z = rackZ - 0.5 + j * 1
    member([x0 - 0.6, 4.75, z], [x0 + 1.1 + rackLen, 4.75, z], 0.3, mat, 16 + j * 0.25, true)
  })

  /* Lattice communication tower beside the building */
  const tx = x0 + bays * bay + 5.5
  const tz = -1.5
  const height = compact ? 13 : 16
  const leg = (s, y) => (1.3 * (1 - y / height) + 0.35) * s
  const levels = 8
  for (let k = 0; k < levels; k++) {
    const y1 = (height / levels) * k
    const y2 = (height / levels) * (k + 1)
    const corners = (y) => [[leg(1, y), leg(1, y)], [leg(-1, y), leg(1, y)], [leg(-1, y), leg(-1, y)], [leg(1, y), leg(-1, y)]]
    const c1 = corners(y1)
    const c2 = corners(y2)
    for (let c = 0; c < 4; c++) {
      const color = k % 2 ? mats.pipeWhite : mats.red
      member([tx + c1[c][0], y1, tz + c1[c][1]], [tx + c2[c][0], y2, tz + c2[c][1]], 0.14, color, 4 + k * 0.7)
      const n = (c + 1) % 4
      member([tx + c2[c][0], y2, tz + c2[c][1]], [tx + c2[n][0], y2, tz + c2[n][1]], 0.07, mats.steel, 4.3 + k * 0.7)
      member([tx + c1[c][0], y1, tz + c1[c][1]], [tx + c2[n][0], y2, tz + c2[n][1]], 0.05, mats.steel, 4.5 + k * 0.7)
    }
  }

  /* ---------- Ground: soft red glow + grid ---------- */
  const glowCanvas = document.createElement('canvas')
  glowCanvas.width = glowCanvas.height = 256
  const g = glowCanvas.getContext('2d')
  const grad = g.createRadialGradient(128, 128, 0, 128, 128, 128)
  grad.addColorStop(0, 'rgba(200,22,30,0.55)')
  grad.addColorStop(0.45, 'rgba(200,22,30,0.14)')
  grad.addColorStop(1, 'rgba(200,22,30,0)')
  g.fillStyle = grad
  g.fillRect(0, 0, 256, 256)
  const glowTexture = new THREE.CanvasTexture(glowCanvas)
  const ground = new THREE.Mesh(
    new THREE.PlaneGeometry(64, 64),
    new THREE.MeshBasicMaterial({ map: glowTexture, transparent: true, depthWrite: false }),
  )
  ground.rotation.x = -Math.PI / 2
  ground.position.y = 0.01
  scene.add(ground)

  const grid = new THREE.GridHelper(80, 40, 0x7a1d24, 0x24324a)
  grid.material.transparent = true
  grid.material.opacity = 0.55
  scene.add(grid)

  /* ---------- Dust ---------- */
  const dustCount = compact ? 140 : 320
  const dustPositions = new Float32Array(dustCount * 3)
  for (let i = 0; i < dustCount; i++) {
    dustPositions[i * 3] = (Math.random() - 0.5) * 56
    dustPositions[i * 3 + 1] = Math.random() * 18
    dustPositions[i * 3 + 2] = (Math.random() - 0.5) * 40
  }
  const dustGeometry = new THREE.BufferGeometry()
  dustGeometry.setAttribute('position', new THREE.BufferAttribute(dustPositions, 3))
  const dust = new THREE.Points(dustGeometry, new THREE.PointsMaterial({ color: 0xffd7d9, size: 0.07, transparent: true, opacity: 0.55, depthWrite: false }))
  scene.add(dust)

  /* ---------- Layout ---------- */
  let width = 1
  let height2 = 1
  const resize = () => {
    width = container.clientWidth || 1
    height2 = container.clientHeight || 1
    renderer.setSize(width, height2, false)
    camera.aspect = width / height2
    // Desktop: push the structure to the side opposite the text.
    const shift = compact ? 0 : width * 0.2 * (rtl ? 1 : -1)
    camera.setViewOffset(width, height2, shift, compact ? -height2 * 0.12 : 0, width, height2)
    camera.updateProjectionMatrix()
    render()
  }

  /* ---------- Camera motion ---------- */
  const state = { scroll: 0, orbit: 0, px: 0, py: 0, tx: 0, ty: 0 }
  const placeCamera = () => {
    const angle = 0.55 + state.scroll * 0.85 + state.px * 0.12
    const radius = (compact ? 54 : 47) - state.scroll * 8
    const y = 17 + state.py * 2.2 - state.scroll * 5
    camera.position.set(Math.sin(angle) * radius, y, Math.cos(angle) * radius)
    camera.lookAt(target)
  }

  const onPointer = (event) => {
    state.tx = (event.clientX / window.innerWidth) * 2 - 1
    state.ty = (event.clientY / window.innerHeight) * 2 - 1
  }

  /* ---------- Build animation ---------- */
  const finals = members.map(({ mesh }) => ({
    position: mesh.position.clone(),
    quaternion: mesh.quaternion.clone(),
    scale: mesh.scale.clone(),
  }))

  const hideAll = () => {
    members.forEach(({ mesh }) => {
      mesh.position.y += 14 + Math.random() * 10
      mesh.position.x += (Math.random() - 0.5) * 10
      mesh.position.z += (Math.random() - 0.5) * 10
      mesh.rotation.x += (Math.random() - 0.5) * 2
      mesh.rotation.z += (Math.random() - 0.5) * 2
      mesh.scale.multiplyScalar(0.001)
    })
  }

  let buildTimeline = null
  const build = () => {
    if (reduced) return
    const maxOrder = Math.max(...members.map((m) => m.order))
    buildTimeline = gsap.timeline()
    members.forEach(({ mesh, order }, i) => {
      const f = finals[i]
      const at = (order / maxOrder) * 2.4 + Math.random() * 0.25
      buildTimeline.to(mesh.position, { x: f.position.x, y: f.position.y, z: f.position.z, duration: 1.3, ease: 'power3.out' }, at)
      buildTimeline.to(mesh.scale, { x: f.scale.x, y: f.scale.y, z: f.scale.z, duration: 0.8, ease: 'power2.out' }, at)
      const q = { t: 0 }
      const start = mesh.quaternion.clone()
      buildTimeline.to(q, {
        t: 1, duration: 1.3, ease: 'power3.out',
        onUpdate: () => mesh.quaternion.slerpQuaternions(start, f.quaternion, q.t),
      }, at)
    })
    buildTimeline.from(grid.material, { opacity: 0, duration: 1.6 }, 0)
    buildTimeline.from(ground.material, { opacity: 0, duration: 2 }, 0.2)
  }

  if (!reduced) hideAll()

  /* ---------- Loop ---------- */
  let frame = 0
  let running = false
  const clock = new THREE.Clock()
  const render = () => {
    placeCamera()
    renderer.render(scene, camera)
  }
  const tick = () => {
    if (!running) return
    const t = clock.getElapsedTime()
    state.px += (state.tx - state.px) * 0.04
    state.py += (state.ty - state.py) * 0.04
    dust.rotation.y = t * 0.02
    dust.position.y = Math.sin(t * 0.3) * 0.4
    glow.intensity = 55 + Math.sin(t * 1.4) * 10
    render()
    frame = requestAnimationFrame(tick)
  }
  const play = () => {
    if (running || reduced) return
    running = true
    clock.start()
    frame = requestAnimationFrame(tick)
  }
  const pause = () => {
    running = false
    cancelAnimationFrame(frame)
  }

  const visibility = new IntersectionObserver(([entry]) => (entry.isIntersecting ? play() : pause()), { threshold: 0 })
  visibility.observe(container)
  const onVisibility = () => (document.hidden ? pause() : play())
  document.addEventListener('visibilitychange', onVisibility)
  const resizeObserver = new ResizeObserver(resize)
  resizeObserver.observe(container)
  window.addEventListener('pointermove', onPointer, { passive: true })
  resize()

  return {
    build,
    setScroll(progress) {
      state.scroll = progress
      if (reduced) render()
    },
    dispose() {
      pause()
      buildTimeline?.kill()
      visibility.disconnect()
      resizeObserver.disconnect()
      document.removeEventListener('visibilitychange', onVisibility)
      window.removeEventListener('pointermove', onPointer)
      unitBox.dispose()
      unitCyl.dispose()
      dustGeometry.dispose()
      glowTexture.dispose()
      ground.geometry.dispose()
      Object.values(mats).forEach((m) => m.dispose())
      renderer.dispose()
      renderer.domElement.remove()
    },
  }
}
