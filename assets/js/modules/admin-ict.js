// ICT Admin — tab switching (ticket logic lives in modules/tickets.js)
// @ts-check

/* ─── ICT Admin ─────────────────────────────────────────────────── */
function ictAdminTab(id, btn) {
  const page = document.getElementById('page-ict-admin');
  page.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  page.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('ict-admin-' + id).hidden = false;
}
