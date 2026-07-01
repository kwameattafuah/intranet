// Home page — department tiles, stat counters, clock, greeting
// Extracted from app.js — GACL Intranet
// @ts-check

/* ═══════════════════════════════════════════════════════
   HOME PAGE — Interactive Engine
   Aurora · 3D Tilt · Animated Counters · Clock · Greeting
   ═══════════════════════════════════════════════════════ */

/* ── Department data (15 depts, each with gradient + icon + sections) ── */
/** @type {HomeDept[]} */
const HP_DEPTS = [
  { id:'dept-md',          name:"MD's Directorate",          sections:1,  grad:'linear-gradient(135deg,#1a3a5c,#0a1e3c)', icon:'<circle cx="12" cy="8" r="4"/><path d="M3 21v-2a9 9 0 0 1 18 0v2"/>' },
  { id:'dept-planning',    name:'Airport Planning & Projects', sections:4,  grad:'linear-gradient(135deg,#0369a1,#01497c)', icon:'<path d="M2 20h20M4 20V10l8-6 8 6v10"/>' },
  { id:'dept-ops',         name:'Airports Management',         sections:6,  grad:'linear-gradient(135deg,#92400e,#78350f)', icon:'<path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>' },
  { id:'dept-audit',       name:'Audit, Compliance & Risk',    sections:2,  grad:'linear-gradient(135deg,#5b21b6,#3b0764)', icon:'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>' },
  { id:'dept-avsec',       name:'Aviation Security',            sections:6,  grad:'linear-gradient(135deg,#9d174d,#7f1d1d)', icon:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' },
  { id:'dept-bizdev',      name:'Business Development',         sections:2,  grad:'linear-gradient(135deg,#065f46,#064e3b)', icon:'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' },
  { id:'dept-commercial',  name:'Commercial Services',           sections:4,  grad:'linear-gradient(135deg,#b45309,#92400e)', icon:'<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>' },
  { id:'dept-comms',       name:'Corporate Comms & PR',         sections:2,  grad:'linear-gradient(135deg,#1e40af,#1e3a8a)', icon:'<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>' },
  { id:'dept-facilities',  name:'Facilities & Infrastructure',  sections:5,  grad:'linear-gradient(135deg,#374151,#1f2937)', icon:'<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>' },
  { id:'dept-finance',     name:'Finance',                       sections:6,  grad:'linear-gradient(135deg,#166534,#14532d)', icon:'<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>' },
  { id:'dept-hcos',        name:'Human Capital & OS',            sections:4,  grad:'linear-gradient(135deg,#9d174d,#831843)', icon:'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>' },
  { id:'dept-ict',         name:'ICT',                           sections:5,  grad:'linear-gradient(135deg,#4c1d95,#1e40af)', icon:'<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>' },
  { id:'dept-legal',       name:'Legal Services & Co. Sec.',     sections:4,  grad:'linear-gradient(135deg,#78350f,#451a03)', icon:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="8" y1="10" x2="8" y2="10.01"/><line x1="16" y1="10" x2="16" y2="10.01"/><line x1="12" y1="14" x2="12" y2="14.01"/>' },
  { id:'dept-procurement', name:'Procurement',                    sections:4,  grad:'linear-gradient(135deg,#065f46,#1e3a8a)', icon:'<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>' },
  { id:'dept-strategy',    name:'Strategy & Corp. Performance', sections:3,  grad:'linear-gradient(135deg,#1e3a8a,#4c1d95)', icon:'<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>' },
];

/* ── Render department cards ── */
/**
 * Renders the 15 home-page department tiles from {@link HP_DEPTS} and wires
 * the cursor-tracking 3D tilt effect (skipped when prefers-reduced-motion).
 * @returns {void}
 */
function renderHpDepts() {
  const grid = document.getElementById('hp-depts-grid');
  if (!grid) return;
  grid.innerHTML = HP_DEPTS.map(d => `
    <div class="hp-dept-card" role="button" tabindex="0"
         onclick="showPage('${d.id}')"
         onkeydown="if(event.key==='Enter'||event.key===' '){showPage('${d.id}');}"
         aria-label="Go to ${d.name} department page">
      <div class="hp-dept-glow" aria-hidden="true"></div>
      <div class="hp-dept-header" style="background:${d.grad}">
        <div class="hp-dept-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">${d.icon}</svg>
        </div>
      </div>
      <div class="hp-dept-body">
        <div class="hp-dept-name">${d.name}</div>
        <div class="hp-dept-sections">${d.sections} section${d.sections!==1?'s':''}</div>
        <div class="hp-dept-arrow" aria-hidden="true">→</div>
      </div>
    </div>`).join('');

  /* 3D tilt effect */
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!prefersReduced) {
    grid.querySelectorAll('.hp-dept-card').forEach(card => {
      const glow = card.querySelector('.hp-dept-glow');
      card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = (e.clientX - r.left) / r.width  - 0.5;
        const y = (e.clientY - r.top)  / r.height - 0.5;
        card.style.transform = `perspective(700px) rotateY(${x*14}deg) rotateX(${-y*14}deg) scale(1.05) translateZ(8px)`;
        card.style.transition = 'none';
        card.style.boxShadow = `0 20px 48px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.12)`;
        if (glow) { glow.style.setProperty('--gx', ((e.clientX - r.left) / r.width * 100)+'%'); glow.style.setProperty('--gy', ((e.clientY - r.top) / r.height * 100)+'%'); }
      });
      card.addEventListener('mouseleave', () => {
        card.style.transition = 'transform 0.5s cubic-bezier(0.23,1,0.32,1), box-shadow 0.5s ease';
        card.style.transform = '';
        card.style.boxShadow = '';
      });
    });
  }
}

/* ── Animated stat counters ── */
function animateCounters() {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.querySelectorAll('.hp-stat-val[data-target]').forEach(el => {
    const target  = parseFloat(el.dataset.target);
    const suffix  = el.dataset.suffix || '';
    const isFloat = el.dataset.target.includes('.');
    if (prefersReduced) { el.textContent = (isFloat ? target.toFixed(1) : target.toLocaleString()) + suffix; return; }
    const start = performance.now();
    const dur   = 1800;
    function update(now) {
      const t = Math.min((now - start) / dur, 1);
      const e = 1 - Math.pow(1 - t, 4); /* ease-out-quart */
      const v = e * target;
      el.textContent = (isFloat ? v.toFixed(1) : Math.round(v).toLocaleString()) + suffix;
      if (t < 1) requestAnimationFrame(update);
    }
    setTimeout(() => requestAnimationFrame(update), 300);
  });
}

/* ── Clock with split spans ── */
function runHpClock() {
  const hEl   = document.getElementById('hp-h');
  const mEl   = document.getElementById('hp-m');
  const sEl   = document.getElementById('hp-s');
  const dateEl = document.getElementById('hero-date');
  if (!hEl) return;
  function pad(n) { return String(n).padStart(2,'0'); }
  function tick() {
    const now = new Date();
    const accra = new Date(now.toLocaleString('en-US', { timeZone: 'Africa/Accra' }));
    if (hEl) hEl.textContent = pad(accra.getHours());
    if (mEl) mEl.textContent = pad(accra.getMinutes());
    if (sEl) sEl.textContent = pad(accra.getSeconds());
    if (dateEl) dateEl.textContent = accra.toLocaleDateString('en-GB', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
  }
  tick();
  setInterval(tick, 1000);
}

/* ── Time-based greeting ── */
function setHpGreeting() {
  const h = new Date(new Date().toLocaleString('en-US',{timeZone:'Africa/Accra'})).getHours();
  const greetEl = document.getElementById('hp-time-greeting');
  if (!greetEl) return;
  greetEl.textContent = h < 12 ? 'morning' : h < 17 ? 'afternoon' : 'evening';
  /* Personalise name from session */
  const nameEl = document.getElementById('hp-staff-name');
  if (nameEl && typeof USER !== 'undefined' && USER?.full_name) {
    nameEl.textContent = USER.full_name.split(' ')[0];
  }
}

/* ── Boot home page ── */
function initHomePage() {
  setHpGreeting();
  renderHpDepts();
  animateCounters();
  runHpClock();
}

/* ── Kick off on first load ── */
(function() { document.addEventListener('DOMContentLoaded', () => { if (document.getElementById('page-dashboard')?.classList.contains('active')) initHomePage(); }); })();

/* ─── Hero clock (legacy compat) ────────────────────────────────── */
(function runClock() {
  /* Handled by runHpClock above — kept for any legacy references */
})();


