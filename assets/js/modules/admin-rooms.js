// Room Booking Admin — requisition approvals, room inventory, block management
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Room Booking Admin ────────────────────────────────────────── */
function roomAdminTab(id, btn) {
  const page = document.getElementById('page-rooms-admin');
  page.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  page.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('rooms-admin-' + id).hidden = false;
}

/**
 * Approves a room requisition and removes its row.
 * @param {HTMLElement} btn  The clicked Approve button.
 * @returns {void}
 */
function roomApprove(btn) {
  const row = btn.closest('tr');
  btn.textContent = '✓ Approved';
  btn.style.background = 'var(--color-success)';
  if (btn.nextElementSibling) btn.nextElementSibling.style.display = 'none';
  setTimeout(() => row && row.remove(), 600);
}

/**
 * Declines a room requisition and removes its row.
 * @param {HTMLElement} btn  The clicked Decline button.
 * @returns {void}
 */
function roomReject(btn) {
  const row = btn.closest('tr');
  btn.textContent = '✗ Declined';
  btn.disabled = true;
  setTimeout(() => row && row.remove(), 600);
}

// Set a room's status badge by room name
/**
 * Sets a room inventory row status badge by room name.
 * @param {string} name   Room name.
 * @param {string} label  Status label.
 * @param {string} cls    Badge CSS class.
 * @returns {void}
 */
function roomSetStatus(name, label, cls) {
  document.querySelectorAll('#rooms-admin-rooms table tbody tr').forEach(tr => {
    const cell = tr.querySelector('td strong');
    if (cell && cell.textContent.trim() === name) {
      const badge = tr.querySelector('.badge');
      if (badge) { badge.textContent = label; badge.className = 'badge ' + cls; }
    }
  });
}

// Per-row "Block" → prefill block form with this room and jump to it
/**
 * Pre-fills the block form with the chosen room and scrolls to it.
 * @param {HTMLElement} btn  The clicked Block button.
 * @returns {void}
 */
function roomBlock(btn) {
  const name = btn.closest('tr').querySelector('td strong').textContent.trim();
  const sel = document.getElementById('bk-room');
  sel.value = name;
  document.getElementById('bk-reason').focus();
  document.getElementById('bk-room').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Formats a datetime-local value as a short readable string.
 * @param {string} v  ISO datetime-local value.
 * @returns {string|null}  Formatted date, or null if empty.
 */
function fmtBlockDate(v) {
  if (!v) return null;
  try {
    return new Date(v).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
  } catch (e) { return v; }
}

/**
 * Handles the block-a-room form submission.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function roomSubmitBlock(e) {
  e.preventDefault();
  const room   = document.getElementById('bk-room').value;
  const reason = document.getElementById('bk-reason').value;
  const from   = fmtBlockDate(document.getElementById('bk-from').value);
  const to     = fmtBlockDate(document.getElementById('bk-to').value);
  if (!room || !reason || !from) return;

  const period = to ? (from + ' → ' + to) : (from + ' → Indefinite');

  const item = document.createElement('div');
  item.className = 'bk-item';
  item.innerHTML =
    '<div style="flex:1">' +
      '<p style="font-size:var(--font-size-sm);font-weight:700;color:var(--color-text-primary)">' + room + '</p>' +
      '<p style="font-size:12px;color:var(--color-text-muted);margin-top:2px">' + reason + ' · ' + period + '</p>' +
    '</div>' +
    '<button class="btn btn-sm btn-ghost" onclick="roomLiftBlock(this,\'' + room.replace(/'/g, "\\'") + '\')">Lift</button>';
  document.getElementById('bk-list').appendChild(item);

  roomSetStatus(room, 'Blocked', 'badge-neutral');
  updateBlockCount();

  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '✓ Room Blocked';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Block Room';
    btn.style.background = 'var(--color-warning)';
    btn.disabled = false;
    e.target.reset();
  }, 1800);
}

/**
 * Lifts a room block (with confirm) and restores availability.
 * @param {HTMLElement} btn   The clicked Lift button.
 * @param {string} room       Room name to restore.
 * @returns {void}
 */
function roomLiftBlock(btn, room) {
  if (!confirm('Lift the block on "' + room + '" and make it available again?')) return;
  btn.closest('.bk-item').remove();
  roomSetStatus(room, 'Available', 'badge-success');
  updateBlockCount();
}

function updateBlockCount() {
  const n = document.querySelectorAll('#bk-list .bk-item').length;
  document.getElementById('bk-count').textContent = n;
  document.getElementById('bk-empty').style.display = n ? 'none' : 'block';
}

/**
 * Loads a room row into the edit form.
 * @param {HTMLElement} btn  The clicked Edit button.
 * @returns {void}
 */
function roomEdit(btn) {
  const cells = btn.closest('tr').querySelectorAll('td');
  document.getElementById('ar-name').value = cells[0].textContent.trim();
  document.getElementById('ar-type').value = cells[1].textContent.trim();
  document.getElementById('ar-cap').value  = cells[2].textContent.trim();
  document.getElementById('ar-loc').value  = cells[3].textContent.trim();
  document.getElementById('ar-mgr').value  = cells[4].textContent.trim();
  document.getElementById('ar-form-title').textContent = 'Edit Room — ' + cells[0].textContent.trim();
  const saveBtn = document.querySelector('#page-rooms-admin form [type=submit]');
  if (saveBtn) saveBtn.textContent = 'Update Room';
  document.getElementById('ar-name').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Deletes a room from inventory (with confirm).
 * @param {HTMLElement} btn  The clicked Delete button.
 * @returns {void}
 */
function roomDelete(btn) {
  const row = btn.closest('tr');
  const name = row.querySelector('td strong').textContent.trim();
  if (!confirm('Permanently delete "' + name + '" from the room inventory?\n\nThis cannot be undone. Any existing bookings for this room must be reassigned first.')) return;
  row.style.transition = 'opacity .3s ease';
  row.style.opacity = '0';
  setTimeout(() => row.remove(), 320);
}

/**
 * Handles the add/update room form submission.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function roomSaveRoom(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  const isEdit = btn.textContent.trim() === 'Update Room';
  btn.textContent = isEdit ? '✓ Room Updated' : '✓ Room Added';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  setTimeout(() => {
    btn.textContent = 'Add Room';
    btn.style.background = '';
    btn.disabled = false;
    e.target.reset();
    document.getElementById('ar-form-title').textContent = 'Add New Room';
  }, 2200);
}


