// L&D Centre LMS — tab switching, enrolment, filters, recommendation engine
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── LMS interactions ──────────────────────────────────────────── */
/**
 * Switches the active tab/panel within the L&D Centre (LMS) page.
 * @param {string} id        Panel suffix, e.g. 'catalog' → #lms-catalog.
 * @param {HTMLElement} btn   The clicked tab button (gets the active state).
 * @returns {void}
 */
function lmsTab(id, btn) {
  document.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  document.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('lms-' + id).hidden = false;
}

/**
 * Marks a course enrolled and disables the button.
 * @param {HTMLElement} btn   The clicked Enrol button.
 * @param {string} label      Confirmation label, e.g. 'Enrolled'.
 * @returns {void}
 */
function lmsEnroll(btn, label) {
  btn.textContent = label === 'Continue' ? 'Continue Learning' : '✓ ' + label;
  btn.disabled = true;
  btn.style.background = 'var(--color-success)';
  btn.style.opacity = '1';
  btn.style.cursor = 'default';
}

/**
 * Simulates a file download with transient button feedback.
 * @param {HTMLElement} btn  The clicked download button.
 * @returns {void}
 */
function lmsDownload(btn) {
  const orig = btn.innerHTML;
  btn.innerHTML = '⏳ Preparing…';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '✓ Downloaded';
    btn.style.color = 'var(--color-success)';
    setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; btn.style.color = ''; }, 2500);
  }, 900);
}

/**
 * Filters the LMS catalogue by free-text search.
 * @param {string} q  Search text.
 * @returns {void}
 */
function lmsFilter(q) {
  const term = q.toLowerCase();
  let any = false;
  document.querySelectorAll('.lms-course-item').forEach(el => {
    const match = el.dataset.title.toLowerCase().includes(term) || el.dataset.cat.toLowerCase().includes(term);
    el.style.display = match ? '' : 'none';
    if (match) any = true;
  });
  document.getElementById('lms-no-results').style.display = any ? 'none' : 'block';
}

/**
 * Filters the LMS catalogue by category.
 * @param {string} cat  Category, or empty for all.
 * @returns {void}
 */
function lmsFilterCat(cat) {
  let any = false;
  document.querySelectorAll('.lms-course-item').forEach(el => {
    const match = !cat || el.dataset.cat === cat;
    el.style.display = match ? '' : 'none';
    if (match) any = true;
  });
  document.getElementById('lms-no-results').style.display = any ? 'none' : 'block';
}


/* ─── LMS recommendation engine ─────────────────────────────────── */
/** @type {Course[]} */
const LMS_COURSES = [
  { title:'Aviation Safety & Compliance',      cat:'Aviation Safety',  mandatory:true,  meta:'6 hrs · 30 Jun' },
  { title:'Customer Service Excellence',        cat:'Customer Service', mandatory:false, meta:'8 hrs · 25 Jun' },
  { title:'Leadership & Management',            cat:'Leadership',       mandatory:false, meta:'12 hrs · 7 Jul' },
  { title:'Microsoft Office 365',              cat:'ICT & Digital',    mandatory:false, meta:'6 hrs · 14 Jul' },
  { title:'Airside Safety Awareness',          cat:'Aviation Safety',  mandatory:true,  meta:'4 hrs · 7 Jul' },
  { title:'First Aid & Emergency Response',     cat:'Health & Safety',  mandatory:true,  meta:'8 hrs · 21 Jul' },
  { title:'Data Protection & Cybersecurity',    cat:'Compliance',       mandatory:false, meta:'3 hrs · 28 Jul' },
  { title:'Airport Operations Fundamentals',    cat:'Operations',       mandatory:false, meta:'10 hrs · 4 Aug' },
  { title:'Financial Management Essentials',    cat:'Finance',          mandatory:false, meta:'8 hrs · 11 Aug' },
  { title:'Anti-Corruption & Ethics',          cat:'Compliance',       mandatory:true,  meta:'4 hrs · 18 Aug' },
  { title:'Fire Safety & Prevention',          cat:'Health & Safety',  mandatory:true,  meta:'3 hrs · 25 Aug' },
  { title:'Passengers with Reduced Mobility',   cat:'Customer Service', mandatory:false, meta:'4 hrs · 1 Sep' },
  { title:'Staff Welfare & Benefits',          cat:'Health & Safety',  mandatory:false, meta:'2 hrs · Self-paced' },
  { title:'Performance Management',            cat:'Leadership',       mandatory:false, meta:'3 hrs · 21 Jul' },
];

// Current staff member's learning state (would come from the server per logged-in user)
const USER_COMPLETED  = ['Customer Service Excellence', 'Leadership Foundations'];
const USER_INPROGRESS = ['Aviation Safety & Compliance'];

// Admin-curated featured courses (persisted)
let FEATURED = (function () {
  try {
    const saved = localStorage.getItem('gacl-featured');
    if (saved) return JSON.parse(saved);
  } catch (e) {}
  return ['Microsoft Office 365', 'Staff Welfare & Benefits'];
})();

function saveFeatured() {
  try { localStorage.setItem('gacl-featured', JSON.stringify(FEATURED)); } catch (e) {}
}

const CAT_STYLE = {
  'Aviation Safety':  { bg:'#e8eef5', fg:'var(--color-primary)', icon:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' },
  'Customer Service': { bg:'#e8f5e9', fg:'var(--color-success)', icon:'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>' },
  'Leadership':       { bg:'#ede9fe', fg:'#6d28d9', icon:'<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>' },
  'ICT & Digital':    { bg:'#e0f2fe', fg:'#0369a1', icon:'<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>' },
  'Health & Safety':  { bg:'#fef9ec', fg:'#92400e', icon:'<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>' },
  'Compliance':       { bg:'#fce4ec', fg:'#9d174d', icon:'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>' },
  'Operations':       { bg:'#f3f4f6', fg:'#374151', icon:'<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>' },
  'Finance':          { bg:'#e8f5e9', fg:'var(--color-success)', icon:'<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>' },
};

/**
 * Returns inline SVG markup for an LMS category icon.
 * @param {string} cat  Category name.
 * @returns {string}  SVG markup.
 */
function catIcon(cat) {
  const s = CAT_STYLE[cat] || CAT_STYLE['Operations'];
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' + s.icon + '</svg>';
}

/**
 * Finds a course by title.
 * @param {string} title  Course title.
 * @returns {Course=}  The matching course, or undefined.
 */
function findCourse(title) { return LMS_COURSES.find(c => c.title === title); }

// Build each staffer's recommendations: outstanding mandatory first, then featured
function renderRecommendations() {
  const grid = document.getElementById('lms-recommend-grid');
  if (!grid) return;

  const done = new Set([].concat(USER_COMPLETED, USER_INPROGRESS));
  const rec = [];

  // 1. Outstanding mandatory (compliance-driven, automatic)
  LMS_COURSES.forEach(c => {
    if (c.mandatory && !done.has(c.title)) rec.push({ course: c, tag: 'Mandatory', tagCls: 'badge-danger' });
  });
  // 2. Admin-featured (curated), skipping any already shown or already done
  FEATURED.forEach(title => {
    const c = findCourse(title);
    if (c && !done.has(title) && !rec.some(r => r.course.title === title)) {
      rec.push({ course: c, tag: 'Featured', tagCls: 'badge-info' });
    }
  });

  const items = rec.slice(0, 6);
  if (!items.length) {
    grid.innerHTML = '<p style="grid-column:1/-1;color:var(--color-text-muted);font-size:var(--font-size-sm);padding:12px">You\'re all caught up — no outstanding recommendations.</p>';
    return;
  }

  grid.innerHTML = items.map(({ course, tag, tagCls }) => {
    const s = CAT_STYLE[course.cat] || CAT_STYLE['Operations'];
    return '<div class="recommend-card">' +
        '<div class="recommend-icon" style="background:' + s.bg + ';color:' + s.fg + '">' + catIcon(course.cat) + '</div>' +
        '<div><p class="recommend-title">' + course.title + '</p>' +
        '<p class="recommend-meta"><span class="badge ' + tagCls + '" style="font-size:10px;padding:1px 6px;margin-right:4px">' + tag + '</span>' + course.meta + '</p></div>' +
        '<button class="btn btn-primary btn-sm" onclick="lmsEnroll(this,\'Enrolled\')">Enrol</button>' +
      '</div>';
  }).join('');
}

// Admin: manage which courses are featured
function renderAdminRecommend() {
  const body = document.getElementById('admin-recommend-body');
  if (!body) return;
  body.innerHTML = LMS_COURSES.map(c => {
    const on = FEATURED.includes(c.title);
    const btn = on
      ? '<button class="btn btn-sm" style="background:var(--color-accent);color:var(--color-primary)" onclick="toggleFeatured(\'' + c.title.replace(/'/g, "\\'") + '\')">★ Featured</button>'
      : '<button class="btn btn-sm btn-ghost" onclick="toggleFeatured(\'' + c.title.replace(/'/g, "\\'") + '\')">☆ Feature</button>';
    return '<tr>' +
      '<td><strong>' + c.title + '</strong></td>' +
      '<td><span class="badge badge-neutral">' + c.cat + '</span></td>' +
      '<td>' + (c.mandatory ? '<span class="badge badge-danger">Mandatory</span>' : '<span class="badge badge-neutral">Optional</span>') + '</td>' +
      '<td>' + btn + '</td>' +
    '</tr>';
  }).join('');
}

/**
 * Toggles a course's "featured" state for the LMS recommendations, persists
 * the list, and re-renders both the admin table and staff recommendation grid.
 * @param {string} title       Course title (key into {@link LMS_COURSES}).
 * @param {HTMLElement} [btn]   Optional originating button.
 * @returns {void}
 */
function toggleFeatured(title, btn) {
  const i = FEATURED.indexOf(title);
  if (i > -1) FEATURED.splice(i, 1); else FEATURED.push(title);
  saveFeatured();
  renderAdminRecommend();
  renderRecommendations();
}

