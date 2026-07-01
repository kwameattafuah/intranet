// GACL Intranet — Core Application
// app.js: RSS loader · auth · sidebar · DOM repair · site-admin
// Feature modules loaded separately from assets/js/modules/

// GACL Intranet — Application Logic
// Extracted from index.html for separation of concerns (CSS/JS/HTML split).
// Classic (non-module) script: all top-level function declarations remain
// global so inline event handlers in index.html continue to resolve.
//
// Domain models are documented below as JSDoc @typedefs for editor IntelliSense.
// To enforce types, add `// @ts-check` once null-safety guards are added.

/**
 * @typedef {Object} DirectoryDept
 * @property {string} dept                     Department display name
 * @property {string} icon                     Icon key (see DIR_ICONS)
 * @property {Array<[string,string]>} entries  [role, extension] pairs
 */

/**
 * @typedef {Object} HomeDept
 * @property {string} id        Page id (e.g. 'dept-ict')
 * @property {string} name      Display name
 * @property {number} sections  Number of sections
 * @property {string} grad      CSS gradient for the tile header
 * @property {string} icon      Inline SVG path markup
 */

/**
 * @typedef {Object} Course
 * @property {string}  title      Course title
 * @property {string}  cat        Category
 * @property {boolean} mandatory  Compliance-mandatory flag
 * @property {string}  meta       Short schedule/duration label
 */

/** @typedef {'Pending'|'Approved'|'Rejected'|'Processed'} SubmissionStatus */

/**
 * @typedef {Object} Submission
 * @property {string} ref                      Auto-generated reference
 * @property {string} formName                 HR form type
 * @property {string} date                     Display date
 * @property {SubmissionStatus} [status]       Processing status
 * @property {string} [submittedBy]            Staff member name
 * @property {string} [dept]                   Submitter department
 * @property {Object<string,string>} [fields]  Captured field values
 * @property {string} [msg]                    Routing confirmation message
 */

/**
 * @typedef {Object} FormRoute
 * @property {string} dept    Receiving department
 * @property {string} office  Approving office + extension
 * @property {string} email   Routing email address
 */

/* ─── GACL RSS feed ─────────────────────────────────────────────── */
(async function loadNews() {
  const RSS_URL   = 'https://www.gacl.com.gh/feed';
  const PROXY_URL = 'https://corsproxy.io/?' + encodeURIComponent(RSS_URL);
  const container = document.getElementById('news-feed');

  const accentColors = [
    'var(--color-primary)',
    'var(--color-warning)',
    'var(--color-success)',
    'var(--color-danger)',
    'var(--color-accent)',
  ];

  function formatDate(dateStr) {
    try {
      return new Date(dateStr).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    } catch { return ''; }
  }

  function stripHtml(html) {
    const d = document.createElement('div');
    d.innerHTML = html;
    return (d.textContent || '').trim();
  }

  function truncate(str, max) {
    return str.length > max ? str.slice(0, max).trimEnd() + '…' : str;
  }

  function buildCards(items) {
    if (!items.length) return fallback();
    // Dark glassmorphism news cards for the new home page
    container.innerHTML = items.slice(0,4).map((item, i) => `
      <a class="hp-news-item" href="${item.link}" target="_blank" rel="noopener noreferrer" aria-label="${item.title}">
        <div class="hp-news-badge">GACL News</div>
        <div class="hp-news-title">${truncate(item.title, 80)}</div>
        <div class="hp-news-meta">
          <span>${formatDate(item.date)}</span>
          <span style="margin-left:auto;color:rgba(240,165,0,0.8);font-weight:600">Read →</span>
        </div>
      </a>`).join('');
  }

  function fallback() {
    container.innerHTML = `
      <div class="alert alert-info" role="alert">
        Unable to load news from gacl.com.gh.
        <a href="https://www.gacl.com.gh/news" target="_blank" rel="noopener noreferrer" style="font-weight:600;margin-left:4px">Visit the website →</a>
      </div>`;
  }

  try {
    const res  = await fetch(PROXY_URL);
    const text = await res.text();
    const xml  = new DOMParser().parseFromString(text, 'text/xml');
    const items = Array.from(xml.querySelectorAll('item')).slice(0, 3).map(el => ({
      title:   (el.querySelector('title')   ? el.querySelector('title').textContent.trim()   : 'Untitled'),
      link:    (el.querySelector('link')    ? el.querySelector('link').textContent.trim()    : '#'),
      date:    (el.querySelector('pubDate') ? el.querySelector('pubDate').textContent.trim() : ''),
      excerpt: truncate(stripHtml((el.querySelector('description') ? el.querySelector('description').textContent : '')), 300),
    }));
    buildCards(items);
  } catch {
    fallback();
  }
})();


/* ─── News page feeds ───────────────────────────────────────────── */
let newsPageLoaded = false;

async function loadNewsPage() {
  if (newsPageLoaded) return;
  newsPageLoaded = true;

  const PROXY = 'https://corsproxy.io/?';

  function stripHtml(html) {
    const d = document.createElement('div'); d.innerHTML = html;
    return (d.textContent || '').trim();
  }

  function trunc(str, n) { return str.length > n ? str.slice(0, n).trimEnd() + '…' : str; }

  function fmtDate(str) {
    try { return new Date(str).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' }); }
    catch { return ''; }
  }

  function buildCards(items, accentColors, badgeClass, badgeLabel) {
    return items.map((item, i) => `
      <article class="news-card" style="margin-bottom:12px">
        <div class="news-card-accent" style="background:${accentColors[i % accentColors.length]}"></div>
        <div class="news-card-body">
          <h3 class="news-title" style="font-size:15px">
            <a href="${item.link}" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:none">${trunc(item.title, 90)}</a>
          </h3>
          <p style="font-size:12px;color:var(--color-text-secondary);line-height:1.6;margin:6px 0 10px">${trunc(item.excerpt, 140)}</p>
          <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px">
            <div style="display:flex;gap:6px">
              <span class="badge ${badgeClass}">${badgeLabel}</span>
              <span class="badge badge-neutral">${fmtDate(item.date)}</span>
            </div>
            <a href="${item.link}" target="_blank" rel="noopener noreferrer" style="font-size:12px;font-weight:600;color:var(--color-primary)">Read →</a>
          </div>
        </div>
      </article>`).join('');
  }

  async function fetchFeed(url, containerId, count, accentColors, badgeClass, badgeLabel) {
    const el = document.getElementById(containerId);
    try {
      const res  = await fetch(PROXY + encodeURIComponent(url));
      const text = await res.text();
      const xml  = new DOMParser().parseFromString(text, 'text/xml');
      const items = Array.from(xml.querySelectorAll('item')).slice(0, count).map(n => ({
        title:   (n.querySelector('title')   ? n.querySelector('title').textContent.trim()   : 'Untitled'),
        link:    (n.querySelector('link')    ? n.querySelector('link').textContent.trim()    : '#'),
        date:    (n.querySelector('pubDate') ? n.querySelector('pubDate').textContent.trim() : ''),
        excerpt: trunc(stripHtml((n.querySelector('description') ? n.querySelector('description').textContent : '')), 300),
      }));
      if (!items.length) throw new Error('empty');
      el.innerHTML = buildCards(items, accentColors, badgeClass, badgeLabel);
    } catch {
      el.innerHTML = `<div class="alert alert-info">Unable to load feed. <a href="${url}" target="_blank" rel="noopener noreferrer" style="font-weight:600">Visit source →</a></div>`;
    }
  }

  const gaclColors = ['var(--color-primary)', 'var(--color-accent)', 'var(--color-success)', 'var(--color-danger)', 'var(--color-primary)'];
  const avColors   = ['#0369a1', '#6d28d9', '#b45309', '#0369a1', '#1e7e44'];

  fetchFeed('https://www.gacl.com.gh/feed',       'news-page-gacl',     5, gaclColors, 'badge-primary', 'GACL');
  fetchFeed('https://simpleflying.com/feed/',      'news-page-aviation', 5, avColors,   'badge-info',    'Simple Flying');
}


/* ─── DOM repair: rehome any pages nested inside wrong parents ───── */
(function fixOrphanedPages() {
  var main = document.getElementById('main-content');
  if (!main) return;
  // Any .page element that is not a direct child of main needs to be moved
  document.querySelectorAll('.page').forEach(function(p) {
    if (p.parentElement !== main) {
      main.appendChild(p);
    }
  });
})();

/* ─── Sidebar search ────────────────────────────────────────────── */
/**
 * Filters sidebar nav items live against a query, hiding empty sections.
 * @param {string} q  Raw search text from the sidebar input.
 * @returns {void}
 */
function sidebarSearch(q) {
  const term = q.toLowerCase().trim();
  document.querySelectorAll('#sidebar-nav .nav-item').forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = (!term || text.includes(term)) ? '' : 'none';
  });
  document.querySelectorAll('#sidebar-nav .nav-section-label').forEach(label => {
    const section = label.closest('.nav-section');
    if (!section) return;
    const visible = [...section.querySelectorAll('.nav-item')].some(i => i.style.display !== 'none');
    section.style.display = visible ? '' : 'none';
  });
  document.querySelectorAll('#sidebar-nav .nav-divider').forEach(d => d.style.display = term ? 'none' : '');
}

function toggleDepts() {
  const list = document.getElementById('nav-depts-list');
  const chev = document.getElementById('nav-depts-chevron');
  const btn  = list.previousElementSibling;
  const open = list.style.display !== 'none';
  list.style.display = open ? 'none' : '';
  chev.style.transform = open ? 'rotate(-90deg)' : '';
  btn.setAttribute('aria-expanded', String(!open));
}

/* ─── Site Admin ─────────────────────────────────────────────────── */
function siteAdminTab(id, btn) {
  const page = document.getElementById('page-site-admin');
  page.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  page.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('site-admin-' + id).hidden = false;
  if (id === 'settings') document.getElementById('sa-admin-status').textContent = isAdmin() ? 'ON' : 'OFF';
}

/**
 * Handles the add-user form submission in Site Admin.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function siteAdminSaveUser(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '✓ Account Created';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  setTimeout(() => { btn.textContent = 'Create User Account'; btn.style.background = ''; btn.disabled = false; e.target.reset(); }, 2200);
}

/**
 * Handles the publish-announcement form submission in Site Admin.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function siteAdminPublish(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '✓ Published';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  setTimeout(() => { btn.textContent = 'Publish Announcement'; btn.style.background = ''; btn.disabled = false; e.target.reset(); }, 2200);
}

/* ─── Auth / role gating (front-end demo) ───────────────────────── */
/** @type {string[]} Page keys that require admin mode to view. */
const ADMIN_PAGES = ['ldc-admin', 'rooms-admin', 'ict-admin', 'site-admin', 'forms-admin', 'form-builder'];

function isAdmin() { return document.body.classList.contains('admin-mode'); }

/**
 * Opens/closes the topbar user dropdown.
 * @param {Event} e  The originating click event.
 * @returns {void}
 */
function toggleUserMenu(e) {
  e.stopPropagation();
  const menu = document.getElementById('user-menu');
  const btn = e.currentTarget;
  const open = menu.hidden;
  menu.hidden = !open;
  btn.setAttribute('aria-expanded', String(open));
}

document.addEventListener('click', function(e) {
  const menu = document.getElementById('user-menu');
  if (menu && !menu.hidden && !e.target.closest('.topbar-actions')) {
    menu.hidden = true;
    document.querySelector('.topbar-user').setAttribute('aria-expanded', 'false');
  }
});

/**
 * Toggles admin mode (reveals admin nav + pages), persists to localStorage,
 * updates the role badge, and bounces off any admin page when switched off.
 * @returns {void}
 */
function toggleAdmin() {
  const on = document.body.classList.toggle('admin-mode');
  try { localStorage.setItem('gacl-admin', on ? '1' : '0'); } catch (err) {}
  document.getElementById('user-role-badge').textContent = on ? 'Administrator' : 'Staff';
  // If admin mode turned off while on an admin page, bounce to dashboard
  if (!on) {
    const current = document.querySelector('.page.active');
    if (current && ADMIN_PAGES.includes(current.id.replace('page-', ''))) showPage('dashboard');
  }
}

// Restore saved role on load
(function initRole() {
  try {
    if (localStorage.getItem('gacl-admin') === '1') {
      document.body.classList.add('admin-mode');
      var badge = document.getElementById('user-role-badge');
      if (badge) badge.textContent = 'Administrator';
    }
  } catch (err) {}
})();

/* ─── Toast notification ─────────────────────────────────────────── */
/**
 * Shows a brief toast notification at the bottom-right of the screen.
 * @param {string} msg   Message to display.
 * @param {'err'|undefined} [type]  'err' for red, otherwise navy.
 * @returns {void}
 */
let _tt;
function toast(msg, type) {
  const el = document.getElementById('toast');
  if (!el) return;
  document.getElementById('t-msg').textContent = msg;
  document.getElementById('t-icon').textContent = type === 'err' ? '✕' : '✓';
  el.style.background = type === 'err' ? 'var(--color-danger)' : 'var(--color-primary)';
  el.classList.add('on');
  clearTimeout(_tt);
  _tt = setTimeout(() => el.classList.remove('on'), 3500);
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('on');
}

/* ─── Mobile navigation ─────────────────────────────────────────── */
/**
 * Opens the sidebar on mobile. Adds overlay + open class + ARIA update.
 * @returns {void}
 */
function openMobileNav() {
  const sidebar  = document.querySelector('.sidebar');
  const overlay  = document.getElementById('sidebar-overlay');
  const btn      = document.getElementById('mobile-nav-btn');
  if (!sidebar) return;
  sidebar.classList.add('mobile-open');
  if (overlay)  { overlay.classList.add('visible'); }
  if (btn)      { btn.setAttribute('aria-expanded','true'); btn.classList.add('active'); }
  document.body.style.overflow = 'hidden'; // prevent background scroll
}

/**
 * Closes the sidebar on mobile.
 * @returns {void}
 */
function closeMobileNav() {
  const sidebar  = document.querySelector('.sidebar');
  const overlay  = document.getElementById('sidebar-overlay');
  const btn      = document.getElementById('mobile-nav-btn');
  if (!sidebar) return;
  sidebar.classList.remove('mobile-open');
  if (overlay)  { overlay.classList.remove('visible'); }
  if (btn)      { btn.setAttribute('aria-expanded','false'); btn.classList.remove('active'); }
  document.body.style.overflow = '';
}

/**
 * Toggles sidebar open/closed on mobile.
 * @returns {void}
 */
function toggleMobileNav() {
  const sidebar = document.querySelector('.sidebar');
  if (!sidebar) return;
  sidebar.classList.contains('mobile-open') ? closeMobileNav() : openMobileNav();
}

// Close mobile nav whenever a nav link is clicked (user navigated — sidebar no longer needed)
document.addEventListener('click', function(e) {
  const link = e.target.closest('.nav-link');
  if (link && window.innerWidth <= 991) { closeMobileNav(); }
});


/* ─── Emergency Broadcast ──────────────────────────────────────── */
const BC_KEY = 'gacl_broadcast';

function pushBroadcast() {
  const msg  = (document.getElementById('bc-msg')?.value || '').trim();
  const type = document.getElementById('bc-type')?.value || 'emergency';
  if (!msg) { toast('Enter a broadcast message', 'error'); return; }
  const bc = { msg, type, ts: Date.now() };
  localStorage.setItem(BC_KEY, JSON.stringify(bc));
  showBroadcast(bc);
  toast('Broadcast pushed to all staff');
}

function clearBroadcast() {
  localStorage.removeItem(BC_KEY);
  const bar = document.getElementById('broadcast-bar');
  if (bar) bar.style.display = 'none';
  toast('Broadcast cleared');
}

function dismissBroadcast() {
  const bar = document.getElementById('broadcast-bar');
  if (bar) bar.style.display = 'none';
}

function showBroadcast(bc) {
  const bar = document.getElementById('broadcast-bar');
  const msgEl = document.getElementById('broadcast-msg');
  const iconEl = document.getElementById('broadcast-icon');
  if (!bar || !msgEl) return;
  const colors = { emergency: '#c0392b', warning: '#b45309', info: '#1d4ed8' };
  const icons  = { emergency: '🚨', warning: '⚠️', info: 'ℹ️' };
  bar.style.background = colors[bc.type] || '#c0392b';
  bar.style.display    = 'flex';
  iconEl.textContent   = icons[bc.type] || '⚠️';
  msgEl.textContent    = bc.msg;
}

function loadBroadcast() {
  try {
    const stored = localStorage.getItem(BC_KEY);
    if (stored) showBroadcast(JSON.parse(stored));
  } catch(e) {}
}

document.addEventListener('DOMContentLoaded', loadBroadcast);

/* ─── Suggestion Box ───────────────────────────────────────────── */
if (!window.SUGGESTIONS) window.SUGGESTIONS = [];

function submitSuggestion(e) {
  e.preventDefault();
  const data = new FormData(e.target);
  const anon = document.getElementById('sug-anon')?.checked;
  const ref  = 'SUG-' + new Date().toISOString().slice(0,10).replace(/-/g,'') + '-' + Math.floor(1000+Math.random()*9000);
  const sub  = {
    ref,
    dept:     data.get('dept') || 'General',
    category: data.get('category'),
    subject:  data.get('subject'),
    body:     data.get('body'),
    name:     anon ? 'Anonymous' : (data.get('name') || 'Anonymous'),
    date:     new Date().toLocaleDateString('en-GB',{day:'numeric',month:'short',year:'numeric'})
  };
  window.SUGGESTIONS.unshift(sub);

  // Route via email to HCOS
  const emailBody = encodeURIComponent(
    'GACL STAFF SUGGESTION\n======================\n' +
    'Reference: ' + ref + '\nFrom: ' + sub.name + '\nDept: ' + sub.dept + '\nCategory: ' + sub.category +
    '\nDate: ' + sub.date + '\n\nSUBJECT: ' + sub.subject + '\n\n' + sub.body
  );
  window.location.href = 'mailto:hcos@gacl.com.gh?subject=' + encodeURIComponent('[Suggestion] ' + sub.subject + ' — ' + ref) + '&body=' + emailBody;

  e.target.reset();
  document.getElementById('sug-name-row').style.display = 'block';

  // Update history panel
  const hist = document.getElementById('sug-history');
  if (hist) {
    hist.innerHTML = window.SUGGESTIONS.map(s =>
      `<div style="padding:8px 0;border-bottom:1px solid var(--color-border)">
        <div style="font-weight:600;font-size:12px">${s.subject}</div>
        <div style="font-size:11px;color:var(--color-text-muted);margin-top:2px">${s.ref} · ${s.date} · ${s.name}</div>
      </div>`
    ).join('');
  }
  toast('Suggestion submitted ✓ — ref: ' + ref);
}

// Toggle name field based on anonymous checkbox
document.addEventListener('DOMContentLoaded', function() {
  const anonCb = document.getElementById('sug-anon');
  const nameRow = document.getElementById('sug-name-row');
  if (anonCb && nameRow) {
    anonCb.addEventListener('change', function() {
      nameRow.style.display = this.checked ? 'none' : 'block';
    });
  }
});

// ── Airport Management Forms ──────────────────────────────────────────
function opsShowTab(tab, btn) {
  document.querySelectorAll('.ops-form-panel').forEach(p => p.style.display = 'none');
  document.getElementById('ops-panel-' + tab).style.display = 'block';
  document.querySelectorAll('#ops-form-tabs .btn').forEach(b => {
    b.classList.remove('btn-primary');
    b.style.background = '';
    b.style.color = '';
    b.classList.add('btn-ghost');
  });
  btn.classList.remove('btn-ghost');
  btn.classList.add('btn-primary');
  btn.style.background = 'var(--color-primary)';
  btn.style.color = '#fff';
}

function submitOpsForm(e, type) {
  e.preventDefault();
  const form = e.target;
  const data = Object.fromEntries(new FormData(form));
  const ref = type.toUpperCase() + '-' + Date.now().toString(36).toUpperCase();
  const record = { ref, type, ...data, submitted: new Date().toLocaleString() };
  const key = 'gacl_ops_forms';
  const all = JSON.parse(localStorage.getItem(key) || '[]');
  all.unshift(record);
  localStorage.setItem(key, JSON.stringify(all.slice(0, 100)));
  renderOpsFormLog();
  form.reset();
  const banner = document.createElement('div');
  banner.style.cssText = 'background:var(--color-success);color:#fff;padding:10px 16px;border-radius:8px;margin-bottom:12px;font-size:13px;font-weight:600';
  banner.textContent = '✓ Submitted — Reference: ' + ref;
  form.parentElement.insertBefore(banner, form);
  setTimeout(() => banner.remove(), 5000);
}

function renderOpsFormLog() {
  const el = document.getElementById('ops-form-log');
  if (!el) return;
  const all = JSON.parse(localStorage.getItem('gacl_ops_forms') || '[]');
  if (!all.length) { el.textContent = 'No submissions yet.'; return; }
  el.innerHTML = all.slice(0, 10).map(r => `
    <div style="padding:8px 0;border-bottom:1px solid var(--color-border);line-height:1.5">
      <div style="font-weight:600;color:var(--color-text)">${r.ref}</div>
      <div style="color:var(--color-text-secondary)">${r.pax_name || r.item_desc || r.guest_name || '—'}</div>
      <div style="font-size:11px;color:var(--color-text-muted)">${r.submitted}</div>
    </div>`).join('');
}

document.addEventListener('DOMContentLoaded', renderOpsFormLog);

// ── Excursion Bookings Manager ────────────────────────────────────────
const EXC_KEY = 'gacl_ops_forms';

const STATUS_COLORS = {
  'Pending Confirmation': '#f59e0b',
  'Awaiting Approval':    '#3b82f6',
  'Confirmed':            '#10b981',
  'Declined':             '#ef4444',
  'Rescheduled':          '#8b5cf6'
};

function statusBadge(s) {
  const c = STATUS_COLORS[s] || '#6b7280';
  return '<span style="background:' + c + '22;color:' + c + ';border:1px solid ' + c + '44;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600;white-space:nowrap">' + (s || '—') + '</span>';
}

function getExcursions() {
  return JSON.parse(localStorage.getItem(EXC_KEY) || '[]').filter(function(r){ return r.type === 'excursion'; });
}

function saveExcursions(excRows) {
  const others = JSON.parse(localStorage.getItem(EXC_KEY) || '[]').filter(function(r){ return r.type !== 'excursion'; });
  localStorage.setItem(EXC_KEY, JSON.stringify(excRows.concat(others)));
}

var _excMgrRef = null;

function renderExcMgr() {
  var filter = (document.getElementById('excmgr-filter') || {}).value || '';
  var allExc = getExcursions();
  var rows = filter ? allExc.filter(function(r){ return r.status === filter; }) : allExc;

  // Stats
  var statsEl = document.getElementById('excmgr-stats');
  if (statsEl) {
    var confirmed = allExc.filter(function(r){ return r.status === 'Confirmed'; }).length;
    var pending   = allExc.filter(function(r){ return r.status === 'Pending Confirmation' || r.status === 'Awaiting Approval'; }).length;
    var declined  = allExc.filter(function(r){ return r.status === 'Declined'; }).length;
    statsEl.innerHTML = [
      ['Total Bookings', allExc.length, '#1a3a5c'],
      ['Confirmed',      confirmed,     '#10b981'],
      ['Pending / Awaiting', pending,   '#f59e0b'],
      ['Declined',       declined,      '#ef4444']
    ].map(function(item){
      return '<div style="background:' + item[2] + '11;border:1px solid ' + item[2] + '33;border-radius:8px;padding:12px 14px">' +
             '<div style="font-size:22px;font-weight:700;color:' + item[2] + '">' + item[1] + '</div>' +
             '<div style="font-size:11px;color:var(--color-text-muted);margin-top:2px">' + item[0] + '</div>' +
             '</div>';
    }).join('');
  }

  var tbody = document.getElementById('excmgr-tbody');
  if (!tbody) return;

  if (!rows.length) {
    tbody.innerHTML = '<tr><td colspan="8" style="padding:32px;text-align:center;color:var(--color-text-muted)">' +
      (filter ? 'No bookings match this filter.' : 'No bookings yet. Use the Excursion Booking tab to register a visit.') +
      '</td></tr>';
    return;
  }

  tbody.innerHTML = rows.map(function(r){
    return '<tr style="border-bottom:1px solid var(--color-border)">' +
      '<td style="padding:10px;font-family:monospace;font-size:11px;color:var(--color-text-muted)">' + r.ref + '</td>' +
      '<td style="padding:10px;font-weight:600">' + (r.org_name || '—') + '</td>' +
      '<td style="padding:10px;font-size:12px;color:var(--color-text-secondary)">' + (r.group_type || '—') + '</td>' +
      '<td style="padding:10px;font-size:12px"><div style="font-weight:500">' + (r.contact_name || '—') + '</div><div style="color:var(--color-text-muted)">' + (r.contact_phone || '') + '</div></td>' +
      '<td style="padding:10px;text-align:center;font-weight:600">' + (r.visitor_count || '—') + '</td>' +
      '<td style="padding:10px;font-size:12px;white-space:nowrap">' + (r.visit_date || '—') + '<br><span style="color:var(--color-text-muted)">' + (r.visit_time || '') + '</span></td>' +
      '<td style="padding:10px">' + statusBadge(r.status) + '</td>' +
      '<td style="padding:10px"><button class="btn btn-ghost btn-sm" style="font-size:11px;padding:4px 10px" onclick="excMgrOpen(\'' + r.ref + '\')">View / Edit</button></td>' +
      '</tr>';
  }).join('');
}

function excMgrOpen(ref) {
  var list = getExcursions();
  var r = null;
  for (var i = 0; i < list.length; i++) { if (list[i].ref === ref) { r = list[i]; break; } }
  if (!r) return;
  _excMgrRef = ref;

  document.getElementById('excmgr-modal-title').textContent = (r.org_name || ref) + '  —  ' + ref;
  document.getElementById('excmgr-modal-status').value = r.status || 'Pending Confirmation';

  var intLabels = { int_terminal:'Terminal Operations', int_airside:'Airside / Apron', int_rffs:'Fire & Rescue (RFFS)',
    int_security:'Aviation Security', int_checkin:'Check-in / Baggage', int_atc:'ATC', int_career:'Career Talk', int_general:'General Tour' };
  var interests = Object.keys(intLabels).filter(function(k){ return r[k] === 'on'; }).map(function(k){ return intLabels[k]; }).join(', ') || 'None selected';

  var fields = [
    ['Reference',       r.ref],
    ['Organisation',    r.org_name],
    ['Group Type',      r.group_type],
    ['Contact Person',  r.contact_name],
    ['Role / Title',    r.contact_role],
    ['Phone',           r.contact_phone],
    ['Email',           r.contact_email],
    ['Visit Date',      r.visit_date],
    ['Arrival Time',    r.visit_time],
    ['Duration',        r.duration],
    ['Alt. Date',       r.alt_date],
    ['No. of Visitors', r.visitor_count],
    ['Age Group',       r.age_group],
    ['Referral',        r.referral],
    ['Processed By',    r.staff_name],
    ['Submitted',       r.submitted]
  ];

  document.getElementById('excmgr-modal-body').innerHTML =
    '<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;font-size:13px">' +
    fields.map(function(f){
      return '<div><div style="font-size:11px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px">' + f[0] + '</div>' +
             '<div style="color:var(--color-text)">' + (f[1] || '—') + '</div></div>';
    }).join('') +
    '<div style="grid-column:1/-1"><div style="font-size:11px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px">Areas of Interest</div>' +
    '<div>' + interests + '</div></div>' +
    '<div style="grid-column:1/-1"><div style="font-size:11px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px">Purpose / Objectives</div>' +
    '<div style="white-space:pre-wrap">' + (r.purpose || '—') + '</div></div>' +
    '<div style="grid-column:1/-1"><div style="font-size:11px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px">Special Requirements</div>' +
    '<div style="white-space:pre-wrap">' + (r.special_reqs || '—') + '</div></div>' +
    '</div>';

  var modal = document.getElementById('excmgr-modal');
  modal.style.display = 'flex';
}

function excMgrUpdateStatus() {
  var newStatus = document.getElementById('excmgr-modal-status').value;
  var updated = getExcursions().map(function(r){ return r.ref === _excMgrRef ? Object.assign({}, r, {status: newStatus}) : r; });
  saveExcursions(updated);
  renderExcMgr();
  renderOpsFormLog();
  document.getElementById('excmgr-modal').style.display = 'none';
  toast('Status updated to: ' + newStatus);
}

function excMgrDelete() {
  if (!confirm('Delete this booking record? This cannot be undone.')) return;
  saveExcursions(getExcursions().filter(function(r){ return r.ref !== _excMgrRef; }));
  renderExcMgr();
  renderOpsFormLog();
  document.getElementById('excmgr-modal').style.display = 'none';
  toast('Booking deleted');
}

function excMgrExport() {
  var rows = getExcursions();
  if (!rows.length) { alert('No bookings to export.'); return; }
  var cols = ['ref','org_name','group_type','contact_name','contact_role','contact_phone','contact_email',
              'visitor_count','age_group','visit_date','visit_time','duration','alt_date',
              'purpose','special_reqs','referral','status','staff_name','submitted'];
  var csv = [cols.join(',')].concat(rows.map(function(r){
    return cols.map(function(c){ return '"' + ((r[c]||'').toString().replace(/"/g,'""')) + '"'; }).join(',');
  })).join('\n');
  var a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'excursion-bookings-' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
}

// Wire manager render into tab switching
var _opsShowTabOrig = opsShowTab;
opsShowTab = function(tab, btn) {
  _opsShowTabOrig(tab, btn);
  if (tab === 'excursion-mgr') renderExcMgr();
};
