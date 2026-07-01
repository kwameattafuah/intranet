// L&D Admin — course/enrolment/certificate admin actions
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Admin interactions ────────────────────────────────────────── */
/**
 * Switches the active tab/panel within the L&D Admin page.
 * @param {string} id        Panel suffix, e.g. 'overview' → #admin-overview.
 * @param {HTMLElement} btn   The clicked tab button (gets the active state).
 * @returns {void}
 */
function adminTab(id, btn) {
  const page = document.getElementById('page-ldc-admin');
  page.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  page.querySelectorAll('.lms-panel').forEach(p => p.hidden = true);
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  document.getElementById('admin-' + id).hidden = false;
}

/**
 * Approves a pending L&D enrolment request and removes its row.
 * @param {HTMLElement} btn  The clicked Approve button.
 * @returns {void}
 */
function adminApprove(btn) {
  const row = btn.closest('tr') || btn.closest('.admin-approval-item');
  btn.textContent = '✓ Approved';
  btn.style.background = 'var(--color-success)';
  btn.nextElementSibling.style.display = 'none';
  setTimeout(() => row && row.remove(), 600);
}

/**
 * Rejects a pending L&D enrolment request (with confirm) and removes its row.
 * @param {HTMLElement} btn  The clicked Reject button.
 * @returns {void}
 */
function adminReject(btn) {
  const row = btn.closest('tr') || btn.closest('.admin-approval-item');
  btn.textContent = '✗ Rejected';
  btn.disabled = true;
  setTimeout(() => row && row.remove(), 600);
}

/**
 * Handles the new/edit course form submission.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function adminSaveCourse(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '✓ Course Published';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  setTimeout(() => { btn.textContent = 'Save & Publish'; btn.style.background = ''; btn.disabled = false; e.target.reset(); }, 2500);
}

/**
 * Publishes a draft course, flipping its status badge to Active.
 * @param {HTMLElement} btn  The clicked Publish button.
 * @returns {void}
 */
function adminPublish(btn) {
  btn.textContent = '✓ Published';
  btn.style.background = 'var(--color-success)';
  btn.disabled = true;
  const cell = btn.closest('tr').querySelector('.badge-neutral');
  if (cell) { cell.textContent = 'Active'; cell.className = 'badge badge-success'; }
}

/**
 * Archives a course (with confirm), dimming its row.
 * @param {HTMLElement} btn  The clicked Archive button.
 * @returns {void}
 */
function adminArchive(btn) {
  if (!confirm('Archive this course? It will be hidden from the catalogue.')) return;
  const row = btn.closest('tr');
  row.style.opacity = '.4';
  row.style.pointerEvents = 'none';
  btn.textContent = 'Archived';
}

/**
 * Loads a course row into the edit form and scrolls to it.
 * @param {HTMLElement} btn  The clicked Edit button.
 * @returns {void}
 */
function adminEditCourse(btn) {
  document.getElementById('admin-course-form').querySelector('h3').textContent = 'Edit Course';
  document.getElementById('ac-title').value = btn.closest('tr').querySelector('strong').textContent;
  document.getElementById('admin-course-form').scrollIntoView({ behavior: 'smooth' });
}

/**
 * Handles the training-manual upload form submission.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function adminUploadManual(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '⏳ Uploading…';
  btn.disabled = true;
  setTimeout(() => { btn.textContent = '✓ Document Uploaded'; btn.style.background = 'var(--color-success)'; setTimeout(() => { btn.textContent = 'Upload Document'; btn.style.background = ''; btn.disabled = false; e.target.reset(); document.getElementById('um-file-name').style.display = 'none'; }, 2000); }, 1200);
}

/**
 * Shows the chosen file name in the upload form.
 * @param {HTMLInputElement} input  The file input element.
 * @returns {void}
 */
function adminFileChosen(input) {
  const label = document.getElementById('um-file-name');
  if (input.files.length) { label.textContent = '📄 ' + input.files[0].name; label.style.display = 'block'; }
}

/**
 * Handles the issue-certificate form submission.
 * @param {Event} e  The form submit event.
 * @returns {void}
 */
function adminIssueCert(e) {
  e.preventDefault();
  const btn = e.target.querySelector('[type=submit]');
  btn.textContent = '⏳ Generating…';
  btn.disabled = true;
  setTimeout(() => { btn.textContent = '✓ Certificate Issued & Emailed'; btn.style.background = 'var(--color-success)'; setTimeout(() => { btn.textContent = 'Issue Certificate'; btn.style.background = ''; btn.disabled = false; e.target.reset(); }, 2500); }, 1000);
}

/**
 * Sends a training reminder, showing transient button feedback.
 * @param {HTMLElement} btn  The clicked reminder button.
 * @returns {void}
 */
function adminSendReminder(btn) {
  const orig = btn.innerHTML;
  btn.innerHTML = '⏳ Sending…';
  btn.disabled = true;
  setTimeout(() => { btn.innerHTML = '✓ Reminders Sent'; btn.style.background = 'var(--color-success)'; btn.style.color = '#fff'; setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; btn.disabled = false; }, 2500); }, 900);
}


