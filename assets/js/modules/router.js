// SPA Router — pageMap, navIds, titles, showPage (load LAST)
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Page navigation ───────────────────────────────────────────── */
const pageMap = ['dashboard','rooms','ldc','directory','news','discussions','media','documents','corporate','applications',
  'ldc-admin','rooms-admin','helpdesk','ict-admin','site-admin','forms-admin','form-builder',
  'announcements','orgchart','workspace','hr-forms','cleantrack','suggestions',
  'dept-md','dept-planning','dept-ops','dept-audit','dept-avsec','dept-bizdev','dept-commercial',
  'dept-comms','dept-facilities','dept-finance','dept-hcos','dept-ict','dept-legal','dept-procurement','dept-strategy',
  'ops-forms'];
const navIds  = {
  dashboard:'nav-dashboard', directory:'nav-directory', orgchart:'nav-orgchart', workspace:'nav-workspace',
  announcements:'nav-announcements', news:'nav-news', discussions:'nav-discussions', media:'nav-media',
  documents:'nav-documents', corporate:'nav-corporate', applications:'nav-applications', 'hr-forms':'nav-hr-forms', 'suggestions':'nav-suggestions',
  ldc:'nav-ldc', rooms:'nav-rooms', helpdesk:'nav-helpdesk',
  'ldc-admin':'nav-ldc-admin', 'rooms-admin':'nav-rooms-admin', 'ict-admin':'nav-ict-admin', 'site-admin':'nav-site-admin', 'forms-admin':'nav-forms-admin', 'form-builder':'nav-form-builder',
  'cleantrack':null,
  'ops-forms':'nav-ops-forms',
  'dept-md':'nav-dept-md', 'dept-planning':'nav-dept-planning', 'dept-ops':'nav-dept-ops', 'dept-audit':'nav-dept-audit',
  'dept-avsec':'nav-dept-avsec', 'dept-bizdev':'nav-dept-bizdev', 'dept-commercial':'nav-dept-commercial',
  'dept-comms':'nav-dept-comms', 'dept-facilities':'nav-dept-facilities', 'dept-finance':'nav-dept-finance',
  'dept-hcos':'nav-dept-hcos', 'dept-ict':'nav-dept-ict', 'dept-legal':'nav-dept-legal',
  'dept-procurement':'nav-dept-procurement', 'dept-strategy':'nav-dept-strategy'
};
const titles  = {
  dashboard:'GACL Staff Portal', directory:'Internal Directory', orgchart:'Organisation Chart', workspace:'My Workspace',
  announcements:'Announcements', news:'News & Information', discussions:'General Discussions', media:'Media Room',
  documents:'Documents & Policies', corporate:'Corporate Profile', applications:'Applications', 'hr-forms':'HCOS Forms & Services',
  ldc:'L&D Centre', rooms:'Room Booking', helpdesk:'ICT Help Desk',
  'ldc-admin':'L&D Administration', 'rooms-admin':'Room Booking Admin', 'ict-admin':'ICT Admin', 'site-admin':'Site Administration', 'forms-admin':'Forms Administration', 'form-builder':'Form Builder',
  'cleantrack':'CleanTrack — BPG', 'suggestions':'Staff Suggestion Box',
  'ops-forms':'Airport Management Forms',
  'dept-md':"MD's Directorate", 'dept-planning':'Airport Planning & Projects', 'dept-ops':'Airports Management',
  'dept-audit':'Audit, Compliance & Risk', 'dept-avsec':'Aviation Security', 'dept-bizdev':'Business Development',
  'dept-commercial':'Commercial Services', 'dept-comms':'Corporate Comms & PR', 'dept-facilities':'Facilities & Infrastructure',
  'dept-finance':'Finance', 'dept-hcos':'Human Capital & Office Services', 'dept-ict':'ICT',
  'dept-legal':'Legal Services & Company Secretariat', 'dept-procurement':'Procurement', 'dept-strategy':'Strategy & Corporate Performance'
};

/**
 * Central SPA router. Activates the page panel, updates the sidebar active
 * state and topbar title, and triggers any page-specific lazy initialisers.
 * Admin-only pages are redirected to the dashboard unless admin mode is on.
 * @param {string} name  Page key (see pageMap), e.g. 'dashboard' | 'dept-ict'.
 * @returns {void}
 */
function showPage(name) {
  // Guard: admin-only pages require admin mode
  if (ADMIN_PAGES.includes(name) && !isAdmin()) { name = 'dashboard'; }

  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.preview-tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(l => { l.classList.remove('active'); l.removeAttribute('aria-current'); });

  document.getElementById('page-' + name).classList.add('active');

  const tabIndex = ['dashboard','rooms','ldc','directory'].indexOf(name);
  if (tabIndex > -1) {
    const tab = document.querySelectorAll('.preview-tab')[tabIndex];
    if (tab) tab.classList.add('active');
  }

  const navEl = document.getElementById(navIds[name]);
  if (navEl) {
    const link = navEl.querySelector('.nav-link');
    if (link) { link.classList.add('active'); link.setAttribute('aria-current', 'page'); }
  }

  document.getElementById('page-title').textContent = titles[name] || name;

  if (name === 'dashboard') initHomePage();
  if (name === 'news') loadNewsPage();
  if (name === 'directory') renderDirectory('');
  if (name === 'ldc') renderRecommendations();
  if (name === 'ldc-admin') renderAdminRecommend();
  if (name === 'workspace') renderWsSubmissions();
  if (name === 'forms-admin')   renderFormsAdmin();
  if (name === 'form-builder')  renderFormBuilderPage();
  if (name === 'hr-forms')      { if (typeof initHrForms    === 'function') initHrForms();
                                  if (typeof injectCustomForms === 'function') injectCustomForms(); }
  if (name === 'discussions') initDiscussions();
  if (name === 'hr-forms') initHrForms();
}


