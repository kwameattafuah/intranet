// HCOS Forms — tab switching, hybrid submission, routing, file upload, calc helpers
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── HCOS Forms tab switching ─────────────────────────────────────── */
function hrFormsTab(id, btn) {
  document.querySelectorAll('.lms-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
  document.querySelectorAll('.hrf-panel').forEach(p => { p.style.display='none'; });
  btn.classList.add('active'); btn.setAttribute('aria-selected','true');
  const panel = document.getElementById('hrf-' + id);
  if (panel) panel.style.display = 'flex';
  // Show admin-empty notice in admin tab if not admin
  if (id === 'admin') {
    const empty = document.getElementById('hrf-admin-empty');
    const adminCards = document.querySelectorAll('#hrf-admin .admin-only');
    let anyVisible = false;
    adminCards.forEach(c => { if (c.style.display !== 'none') anyVisible = true; });
    if (empty) empty.style.display = anyVisible ? 'none' : 'flex';
  }
}

function initHrForms() {
  // Ensure first panel visible
  const first = document.getElementById('hrf-personal');
  if (first && first.style.display === 'none') first.style.display = 'flex';
}

/* ─── HR form submit — hybrid approach ──────────────────────────── */

// Email routing table: form name → GACL office email
const HR_EMAIL_ROUTES = {
  'Leave Application':        'hcos@gacl.com.gh',
  'Medical Claim':            'hcos@gacl.com.gh',
  'Change of Bank Account':   'hcos@gacl.com.gh',
  'Beneficiaries Nomination': 'hcos@gacl.com.gh',
  'Skills Audit':             'ldc@gacl.com.gh',
  'Expenditure Claim':        'finance@gacl.com.gh',
  'Kilometric Claim':         'finance@gacl.com.gh',
  'Overtime Claim (Form B)':  'hcos@gacl.com.gh',
  'Accountable Imprest':      'finance@gacl.com.gh',
  'Hazard Allowance':         'hcos@gacl.com.gh',
  'Night Duty Honorarium':    'hcos@gacl.com.gh',
  'Accident Report':          'safety@gacl.com.gh',
  'GLICO Accident Claim':     'hcos@gacl.com.gh',
  'Personnel Requisition':    'hcos@gacl.com.gh',
  'Separation Accountability':'hcos@gacl.com.gh',
  'Overtime Claim (Form A)':  'hcos@gacl.com.gh',
};

// In-memory submissions log (persists for session; shown in My Workspace)
if (!window.HR_SUBMISSIONS) window.HR_SUBMISSIONS = [];

let _hrCurrentForm = null;   // form element, held for print/email
let _hrCurrentName = '';     // form name
let _hrCurrentMsg  = '';     // routing message
let _hrCurrentRef  = '';     // reference number

/**
 * Handles an HR form submission: generates a reference, records it in the
 * session submissions log, and opens the routing modal (email / print).
 * @param {Event} e         The form submit event (preventDefault is called).
 * @param {string} formName  HR form type, e.g. 'Leave Application'.
 * @param {string} msg       Routing confirmation message shown in the modal.
 * @returns {void}
 */
function hrSubmit(e, formName, msg) {
  e.preventDefault();

  // Collect form data for email body
  const data = new FormData(e.target);
  const lines = [];
  data.forEach((val, key) => { if (val && key !== '__attachments') lines.push(key + ': ' + val); });

  // Collect any attached files (from the optional attachment input in this form)
  const fileInput = e.target.querySelector('input[type="file"]');
  const attachedFiles = fileInput && fileInput.files ? Array.from(fileInput.files) : [];
  const fileNames = attachedFiles.map(f => f.name);

  // Generate reference number
  const ref = formName.replace(/\s+/g,'').slice(0,4).toUpperCase() +
              '-' + new Date().toISOString().slice(0,10).replace(/-/g,'') +
              '-' + Math.floor(1000 + Math.random() * 9000);

  _hrCurrentForm = e.target;
  _hrCurrentName = formName;
  _hrCurrentMsg  = msg;
  _hrCurrentRef  = ref;

  // Save to session submissions
  HR_SUBMISSIONS.unshift({
    ref, formName, msg,
    attachments: fileNames,
    date: new Date().toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' })
  });

  // Show modal
  const modal = document.getElementById('hr-success-modal');
  document.getElementById('hr-modal-title').textContent = formName;
  document.getElementById('hr-modal-ref').textContent   = 'Reference: ' + ref;
  document.getElementById('hr-modal-msg').textContent   = msg;

  // Show attached files list in modal
  const attachEl = document.getElementById('hr-modal-attachments');
  if (attachEl) {
    if (fileNames.length) {
      attachEl.innerHTML = '<div style="margin-top:10px;padding:10px 12px;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0">' +
        '<p style="font-size:11px;font-weight:700;color:#166534;margin-bottom:5px">📎 ' + fileNames.length + ' file' + (fileNames.length>1?'s':'') + ' attached:</p>' +
        fileNames.map(n => '<div style="font-size:11px;color:#166534">' + n + '</div>').join('') +
        '<p style="font-size:10px;color:#4ade80;margin-top:5px">Remember to attach these files when you send the email.</p>' +
        '</div>';
      attachEl.style.display = 'block';
    } else {
      attachEl.style.display = 'none';
    }
  }

  // Build email button subject/body (include file names)
  const email = HR_EMAIL_ROUTES[formName] || 'hcos@gacl.com.gh';
  const subject = encodeURIComponent('[GACL Intranet] ' + formName + ' — ' + ref);
  const attachNote = fileNames.length
    ? '\n\nATTACHMENTS:\n' + fileNames.map(n => '  • ' + n).join('\n') + '\n(Please attach these files to this email)'
    : '';
  const body = encodeURIComponent(
    'GACL INTRANET FORM SUBMISSION\n==============================\n' +
    'Form: ' + formName + '\nReference: ' + ref + '\n' +
    'Submitted: ' + new Date().toLocaleString('en-GB') + '\n' +
    'Submitted by: Kwame Attafuah (k.attafuah@gacl.com.gh)\n\n' +
    'FORM DATA:\n' + lines.join('\n') + attachNote + '\n\n' + msg
  );
  document.getElementById('hr-email-btn').onclick = () => {
    window.location.href = 'mailto:' + email + '?subject=' + subject + '&body=' + body;
  };

  modal.style.display = 'flex';

  // Reset form and totals
  e.target.reset();
  ['exp-total','km-total-dist','km-total-amt','imp-total','imp-balance']
    .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
}

function hrSendEmail() {
  // triggered by the email button — onclick set dynamically in hrSubmit
}

function hrPrint() {
  const frame = document.getElementById('hr-print-frame');
  if (!frame) return;
  frame.innerHTML =
    '<div style="font-family:Arial,sans-serif;max-width:700px;margin:0 auto;padding:30px">' +
    '<div style="text-align:center;margin-bottom:20px">' +
    '<h2 style="color:#1a3a5c;margin:0">Ghana Airports Company Limited</h2>' +
    '<h3 style="margin:4px 0 2px">' + _hrCurrentName + '</h3>' +
    '<p style="color:#666;font-size:13px;margin:0">Reference: <strong>' + _hrCurrentRef + '</strong> &nbsp;|&nbsp; ' +
    new Date().toLocaleDateString('en-GB', {day:'numeric',month:'long',year:'numeric'}) + '</p>' +
    '<hr style="margin:16px 0;border-color:#dde3ec">' +
    '</div>' +
    '<p style="color:#333;font-size:13px;background:#f4f7fb;padding:12px;border-left:4px solid #1a3a5c">' +
    _hrCurrentMsg + '</p>' +
    '<p style="font-size:12px;color:#888;margin-top:24px;text-align:center">' +
    'Submitted via GACL Intranet · ' + new Date().toLocaleString('en-GB') +
    '</p></div>';
  frame.style.display = 'block';
  window.print();
  frame.style.display = 'none';
  frame.innerHTML = '';
}

function hrCloseModal() {
  document.getElementById('hr-success-modal').style.display = 'none';
  renderWsSubmissions();
}

function calcExpTotal() {
  let total = 0;
  document.querySelectorAll('#exp-rows input[type=number]').forEach(i => { total += parseFloat(i.value)||0; });
  const el = document.getElementById('exp-total');
  if (el) el.value = total.toFixed(2);
}

/* ─── Kilometric totals ──────────────────────────────────────────── */
function calcKmTotal() {
  let dist = 0;
  document.querySelectorAll('#km-rows input[type=number]').forEach(i => { dist += parseFloat(i.value)||0; });
  const rate = parseFloat(document.getElementById('km-rate')?.value)||0;
  const distEl = document.getElementById('km-total-dist');
  const amtEl = document.getElementById('km-total-amt');
  if (distEl) distEl.value = dist.toFixed(1);
  if (amtEl) amtEl.value = (dist * rate).toFixed(2);
}

/* ─── Imprest totals ─────────────────────────────────────────────── */
function calcImprest() {
  let total = 0;
  document.querySelectorAll('#imp-rows input[type=number]').forEach(i => { total += parseFloat(i.value)||0; });
  const received = parseFloat(document.getElementById('imp-received')?.value)||0;
  const totEl = document.getElementById('imp-total');
  const balEl = document.getElementById('imp-balance');
  if (totEl) totEl.value = total.toFixed(2);
  if (balEl) balEl.value = (received - total).toFixed(2);
}


