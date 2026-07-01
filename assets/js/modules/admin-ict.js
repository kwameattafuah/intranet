// ICT Admin — ticket management actions
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── ICT Admin ─────────────────────────────────────────────────── */
function ictAdminTab(id, btn) {
  const page = document.getElementById('page-ict-admin');
  page.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  page.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('ict-admin-' + id).hidden = false;
}

/**
 * Builds the status badge markup for an ICT ticket.
 * @param {string} status  Ticket status.
 * @returns {string}  Badge HTML.
 */
function ictStatusBadge(status) {
  const map = { Critical:'badge-danger', 'In Progress':'badge-warning', Open:'badge-primary', Resolved:'badge-success', Closed:'badge-neutral' };
  return '<span class="badge ' + (map[status] || 'badge-neutral') + '">' + status + '</span>';
}

/**
 * Marks an ICT ticket Resolved and swaps its row actions.
 * @param {HTMLElement} btn  The clicked Resolve button.
 * @returns {void}
 */
function ictResolve(btn) {
  const row = btn.closest('tr');
  row.querySelector('td:nth-child(8)').innerHTML = ictStatusBadge('Resolved');
  row.setAttribute('data-status', 'Resolved');
  const actions = row.querySelector('td:last-child');
  actions.innerHTML = '<button class="btn btn-sm btn-ghost" style="height:34px" onclick="ictClose(this)">Close</button>';
}

/**
 * Escalates an ICT ticket to the Ag. Director and flags it Critical.
 * @param {HTMLElement} btn  The clicked Escalate button.
 * @returns {void}
 */
function ictEscalate(btn) {
  const row = btn.closest('tr');
  const assignSel = row.querySelector('select');
  if (assignSel) assignSel.value = 'Ag. Director, ICT';
  row.querySelector('td:nth-child(8)').innerHTML = ictStatusBadge('Critical');
  row.setAttribute('data-status', 'Critical');
  const orig = btn.innerHTML;
  btn.textContent = '✓ Escalated';
  btn.disabled = true;
  btn.style.color = 'var(--color-danger)';
  setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; btn.style.color = ''; }, 2000);
}

/**
 * Closes an ICT ticket, setting its status to Closed.
 * @param {HTMLElement} btn  The clicked Close button.
 * @returns {void}
 */
function ictClose(btn) {
  const row = btn.closest('tr');
  row.querySelector('td:nth-child(8)').innerHTML = ictStatusBadge('Closed');
  row.setAttribute('data-status', 'Closed');
  btn.textContent = '✓ Closed';
  btn.disabled = true;
}

/**
 * Reassigns an ICT ticket; auto-advances Open tickets to In Progress.
 * @param {HTMLSelectElement} sel  The assignee select element.
 * @returns {void}
 */
function ictAssign(sel) {
  const row = sel.closest('tr');
  const statusBadge = row.querySelector('td:nth-child(8) .badge');
  if (statusBadge && statusBadge.textContent === 'Open') {
    statusBadge.textContent = 'In Progress';
    statusBadge.className = 'badge badge-warning';
    row.setAttribute('data-status', 'In Progress');
  }
  const orig = sel.style.borderColor;
  sel.style.borderColor = 'var(--color-success)';
  setTimeout(() => sel.style.borderColor = orig, 1200);
}

/**
 * Filters the ICT ticket table by status.
 * @param {string} val  Status to filter by, or empty for all.
 * @returns {void}
 */
function ictFilterStatus(val) {
  document.querySelectorAll('#ict-tickets-table tbody tr').forEach(row => {
    row.style.display = (!val || row.dataset.status === val) ? '' : 'none';
  });
}


