// Forms Administration — FA_ROUTES, form queue, approve/reject/export
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Forms Admin ────────────────────────────────────────────────── */

// Routing metadata — maps form type to the responsible GACL department
/** @type {Object<string, FormRoute>} */
const FA_ROUTES = {
  'Leave Application':        { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Medical Claim':            { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Change of Bank Account':   { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Beneficiaries Nomination': { dept:'HCOS',      office:'HC Records — Ext. 2222',     email:'hcos@gacl.com.gh' },
  'Skills Audit':             { dept:'L&D Centre',office:'L&D Manager — Ext. 2340',   email:'ldc@gacl.com.gh' },
  'Expenditure Claim':        { dept:'Finance',   office:'Head of Dept / Finance',     email:'finance@gacl.com.gh' },
  'Kilometric Claim':         { dept:'Finance',   office:'GE, HC & OS — Ext. 2222',   email:'finance@gacl.com.gh' },
  'Overtime Claim (Form B)':  { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Overtime Claim (Form A)':  { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Accountable Imprest':      { dept:'Finance',   office:'Internal Audit — Ext. 2528', email:'finance@gacl.com.gh' },
  'Hazard Allowance':         { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Night Duty Honorarium':    { dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
  'Accident Report':          { dept:'Safety',    office:'Safety & Env — Ext. 6161',  email:'safety@gacl.com.gh' },
  'GLICO Accident Claim':     { dept:'HCOS',      office:'Welfare — Ext. 2222',        email:'hcos@gacl.com.gh' },
  'Personnel Requisition':    { dept:'HCOS',      office:'GE, HC & OS + MD',           email:'hcos@gacl.com.gh' },
  'Separation Accountability':{ dept:'HCOS',      office:'GE, HC & OS — Ext. 2222',   email:'hcos@gacl.com.gh' },
};

// Seed some sample submissions so the admin page is never empty on first load
if (!window.FA_SEED_DONE) {
  window.FA_SEED_DONE = true;
  const seed = [
    { ref:'LEAV-20260622-1042', formName:'Leave Application',        submittedBy:'Emmanuel Owusu',   dept:'Operations', date:'22 Jun 2026', status:'Pending',   fields:{ 'Staff No':'OPS-067','Department':'Operations','No. of Days':'5','Start Date':'30 Jun 2026','End Date':'4 Jul 2026','Leave Type':'Annual' } },
    { ref:'MEDI-20260621-2301', formName:'Medical Claim',            submittedBy:'Abena Mensah',     dept:'HR',         date:'21 Jun 2026', status:'Approved',  fields:{ 'Staff No':'HR-018','Hospital':'Ridge Hospital','Amount (GHS)':'450.00','Claim For':'Self' } },
    { ref:'EXPC-20260620-7741', formName:'Expenditure Claim',        submittedBy:'Kofi Asante',      dept:'Finance',    date:'20 Jun 2026', status:'Processed', fields:{ 'Staff No':'FIN-031','Nature of Claim':'Office Supplies','Total Amount (GHS)':'320.00' } },
    { ref:'LEAV-20260620-3318', formName:'Leave Application',        submittedBy:'Ama Boateng',      dept:'L&D Centre', date:'20 Jun 2026', status:'Pending',   fields:{ 'Staff No':'LDC-055','Department':'L&D Centre','No. of Days':'3','Start Date':'25 Jun 2026','Leave Type':'Casual' } },
    { ref:'OVTB-20260619-8821', formName:'Overtime Claim (Form B)',  submittedBy:'Kwame Attafuah',   dept:'ICT',        date:'19 Jun 2026', status:'Pending',   fields:{ 'Staff No':'ICT-042','Department':'ICT','Total Hours':'12','Period':'13–15 Jun 2026' } },
    { ref:'ACCT-20260618-4492', formName:'Accident Report',          submittedBy:'James Asare',      dept:'Operations', date:'18 Jun 2026', status:'Approved',  fields:{ 'Job Title':'Ground Handler','Location':'Apron Area','Type':'Near Miss','Doctor Seen':'No' } },
    { ref:'BANK-20260617-5519', formName:'Change of Bank Account',   submittedBy:'Grace Adjei',      dept:'Finance',    date:'17 Jun 2026', status:'Processed', fields:{ 'Staff No':'FIN-029','New Bank':'GCB Bank','Account Type':'Savings' } },
    { ref:'KMTR-20260616-2204', formName:'Kilometric Claim',         submittedBy:'Richard Boadu',    dept:'Procurement',date:'16 Jun 2026', status:'Rejected',  fields:{ 'Vehicle Reg':'GR-2041-22','Total Distance':'87 km','Total Claim (GHS)':'156.60' } },
  ];
  seed.forEach(s => { if (!window.HR_SUBMISSIONS.some(e => e.ref === s.ref)) window.HR_SUBMISSIONS.push(s); });
}

let _faCurrentIdx = null; // index of submission being viewed in detail panel

function renderFormsAdmin() {
  faFilter();
  const pending = (window.HR_SUBMISSIONS||[]).filter(s => s.status === 'Pending').length;
  const badge = document.getElementById('fa-pending-badge');
  if (badge) badge.textContent = pending > 0 ? pending + ' Pending' : '';
}

function faFilter() {
  const search  = (document.getElementById('fa-search')?.value||'').toLowerCase();
  const dept    = document.getElementById('fa-dept-filter')?.value||'';
  const status  = document.getElementById('fa-status-filter')?.value||'';
  const type    = document.getElementById('fa-type-filter')?.value||'';

  const subs = window.HR_SUBMISSIONS || [];
  const filtered = subs.filter(s => {
    const matchSearch = !search || (s.formName+s.ref+(s.submittedBy||'')+(s.dept||'')).toLowerCase().includes(search);
    const route = FA_ROUTES[s.formName] || {};
    const matchDept   = !dept   || route.dept === dept;
    const matchStatus = !status || s.status === status;
    const matchType   = !type   || s.formName === type;
    return matchSearch && matchDept && matchStatus && matchType;
  });

  const tbody = document.getElementById('fa-tbody');
  if (!tbody) return;

  // Update stats
  const all = window.HR_SUBMISSIONS || [];
  const setVal = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
  setVal('fa-stat-pending',  all.filter(s=>s.status==='Pending').length);
  setVal('fa-stat-approved', all.filter(s=>s.status==='Approved').length);
  setVal('fa-stat-rejected', all.filter(s=>s.status==='Rejected').length);
  setVal('fa-stat-total',    all.length);

  if (!filtered.length) {
    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:32px;color:var(--color-text-muted)">No matching submissions.</td></tr>';
    return;
  }

  const statusBadge = s => {
    const map = { Pending:'badge-warning', Approved:'badge-success', Rejected:'badge-danger', Processed:'badge-primary' };
    return '<span class="badge '+(map[s]||'badge-neutral')+'">'+s+'</span>';
  };

  tbody.innerHTML = filtered.map((s, i) => {
    const route = FA_ROUTES[s.formName] || { office:'HCOS', email:'hcos@gacl.com.gh' };
    const realIdx = (window.HR_SUBMISSIONS||[]).indexOf(s);
    return '<tr>' +
      '<td style="font-weight:700;color:var(--color-primary);white-space:nowrap">'+s.ref+'</td>' +
      '<td><strong>'+s.formName+'</strong></td>' +
      '<td>'+(s.submittedBy||'K. Attafuah')+'</td>' +
      '<td>'+(s.dept||'ICT')+'</td>' +
      '<td style="white-space:nowrap">'+s.date+'</td>' +
      '<td style="font-size:12px">'+route.office+'</td>' +
      '<td>'+statusBadge(s.status)+'</td>' +
      '<td><div style="display:flex;gap:4px">' +
        '<button class="btn btn-sm btn-ghost" style="height:34px" onclick="faView('+realIdx+')">View</button>' +
        (s.status==='Pending' ?
          '<button class="btn btn-sm" style="background:var(--color-success);color:#fff;height:34px" onclick="faApprove('+realIdx+')">Approve</button>' +
          '<button class="btn btn-sm" style="background:var(--color-danger-light);color:var(--color-danger);height:34px" onclick="faReject('+realIdx+')">Reject</button>'
          : (s.status==='Approved' ?
            '<button class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;height:34px" onclick="faProcess('+realIdx+')">Process</button>' : '')
        ) +
      '</div></td>' +
    '</tr>';
  }).join('');
}

/**
 * Opens the Forms-Admin detail panel for a submission.
 * @param {number} idx  Index into the global HR_SUBMISSIONS array.
 * @returns {void}
 */
function faView(idx) {
  _faCurrentIdx = idx;
  const s = (window.HR_SUBMISSIONS||[])[idx];
  if (!s) return;
  const route = FA_ROUTES[s.formName] || { office:'HCOS', email:'hcos@gacl.com.gh' };
  document.getElementById('fa-detail-title').textContent = s.formName;
  document.getElementById('fa-detail-ref').textContent = 'Ref: ' + s.ref;
  document.getElementById('fa-detail-meta').innerHTML =
    '<span>👤 '+(s.submittedBy||'K. Attafuah')+'</span>' +
    '<span>🏢 '+(s.dept||'ICT')+'</span>' +
    '<span>📅 '+s.date+'</span>' +
    '<span>📍 Routes to: '+route.office+'</span>' +
    '<span>Status: <strong>'+s.status+'</strong></span>';

  const fields = s.fields || {};
  const keys = Object.keys(fields);
  document.getElementById('fa-detail-fields').innerHTML = keys.length
    ? keys.map(k =>
        '<div style="background:var(--color-surface-2);border-radius:var(--radius-md);padding:10px 12px">' +
          '<p style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-muted);margin:0 0 3px">'+k+'</p>' +
          '<p style="font-size:var(--font-size-sm);font-weight:600;color:var(--color-text-primary);margin:0">'+fields[k]+'</p>' +
        '</div>'
      ).join('')
    : '<p style="color:var(--color-text-muted);font-size:var(--font-size-sm);grid-column:1/-1">Detailed field data is captured at submission. For full form contents, use the Print action below.</p>';

  document.getElementById('fa-detail-panel').style.display = 'block';
  document.getElementById('fa-detail-panel').scrollIntoView({ behavior:'smooth', block:'start' });
}

function faApproveDetail() { if (_faCurrentIdx !== null) faApprove(_faCurrentIdx); faCloseDetail(); }
function faRejectDetail()  { if (_faCurrentIdx !== null) faReject(_faCurrentIdx);  faCloseDetail(); }
function faMarkProcessed() { if (_faCurrentIdx !== null) faProcess(_faCurrentIdx); faCloseDetail(); }
function faCloseDetail()   { document.getElementById('fa-detail-panel').style.display = 'none'; _faCurrentIdx = null; }

/**
 * Approves a submission (sets status to 'Approved') and re-renders the table.
 * @param {number} idx  Index into the global HR_SUBMISSIONS array.
 * @returns {void}
 */
function faApprove(idx) {
  const s = (window.HR_SUBMISSIONS||[])[idx];
  if (!s) return;
  s.status = 'Approved';
  faFilter();
  const orig = document.querySelector('#fa-tbody tr:nth-child('+(idx+1)+') .badge');
}

/**
 * Rejects a submission (with confirm) and re-renders the table.
 * @param {number} idx  Index into the global HR_SUBMISSIONS array.
 * @returns {void}
 */
function faReject(idx) {
  const s = (window.HR_SUBMISSIONS||[])[idx];
  if (!s) return;
  if (!confirm('Reject "' + s.formName + '" from ' + (s.submittedBy||'staff') + '?')) return;
  s.status = 'Rejected';
  faFilter();
}

/**
 * Marks a submission Processed and re-renders the table.
 * @param {number} idx  Index into the global HR_SUBMISSIONS array.
 * @returns {void}
 */
function faProcess(idx) {
  const s = (window.HR_SUBMISSIONS||[])[idx];
  if (!s) return;
  s.status = 'Processed';
  faFilter();
}

function faPrintDetail() {
  const s = (window.HR_SUBMISSIONS||[])[_faCurrentIdx];
  if (!s) return;
  const route = FA_ROUTES[s.formName] || { office:'HCOS' };
  const fields = s.fields || {};
  const fieldRows = Object.entries(fields).map(([k,v]) =>
    '<tr><td style="font-weight:600;padding:6px 10px;background:#f4f7fb;border:1px solid #dde3ec;width:40%">'+k+'</td>' +
    '<td style="padding:6px 10px;border:1px solid #dde3ec">'+v+'</td></tr>'
  ).join('');
  const frame = document.getElementById('hr-print-frame');
  if (!frame) return;
  frame.innerHTML =
    '<div style="font-family:Arial,sans-serif;max-width:720px;margin:0 auto;padding:30px">' +
    '<div style="text-align:center;border-bottom:2px solid #1a3a5c;padding-bottom:14px;margin-bottom:20px">' +
      '<h2 style="color:#1a3a5c;margin:0">Ghana Airports Company Limited</h2>' +
      '<h3 style="margin:6px 0 2px">'+s.formName+'</h3>' +
      '<p style="color:#666;font-size:13px;margin:0">Ref: <strong>'+s.ref+'</strong> &nbsp;|&nbsp; Submitted: '+s.date+'</p>' +
    '</div>' +
    '<p style="font-size:13px"><strong>Submitted by:</strong> '+(s.submittedBy||'Staff Member')+' ('+(s.dept||'')+')</p>' +
    '<p style="font-size:13px;margin-bottom:16px"><strong>Routed to:</strong> '+route.office+'</p>' +
    (fieldRows ? '<table style="width:100%;border-collapse:collapse;font-size:13px">'+fieldRows+'</table>' : '') +
    '<p style="font-size:11px;color:#aaa;margin-top:24px;text-align:center">Printed from GACL Intranet — Forms Administration · '+new Date().toLocaleString('en-GB')+'</p>' +
    '</div>';
  frame.style.display = 'block';
  window.print();
  frame.style.display = 'none';
  frame.innerHTML = '';
}

function faEmailReply() {
  const s = (window.HR_SUBMISSIONS||[])[_faCurrentIdx];
  if (!s) return;
  const route = FA_ROUTES[s.formName] || { email:'hcos@gacl.com.gh' };
  // Reply to submitter (in real system submitter's email would come from user record)
  const to = 'k.attafuah@gacl.com.gh'; // placeholder — would be s.submitterEmail
  const subject = encodeURIComponent('Re: ' + s.formName + ' — ' + s.ref);
  const body = encodeURIComponent(
    'Dear ' + (s.submittedBy||'Staff Member') + ',\n\n' +
    'We acknowledge receipt of your ' + s.formName + ' (Ref: ' + s.ref + ') submitted on ' + s.date + '.\n\n' +
    'Current status: ' + s.status + '\n\n' +
    'Please contact ' + route.office + ' if you have any queries.\n\n' +
    'Regards,\nGACL Human Capital & Office Services\nExt. 2222'
  );
  window.location.href = 'mailto:' + to + '?subject=' + subject + '&body=' + body;
}

function faExportCSV() {
  const subs = window.HR_SUBMISSIONS || [];
  if (!subs.length) { alert('No submissions to export.'); return; }
  const headers = ['Reference','Form Type','Submitted By','Department','Date','Routed To','Status'];
  const rows = subs.map(s => {
    const route = FA_ROUTES[s.formName] || { office:'HCOS' };
    return [s.ref, s.formName, s.submittedBy||'', s.dept||'', s.date, route.office, s.status]
      .map(v => '"' + String(v).replace(/"/g,'""') + '"').join(',');
  });
  const csv = [headers.join(','), ...rows].join('\n');
  const a = document.createElement('a');
  a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
  a.download = 'GACL-Form-Submissions-' + new Date().toISOString().slice(0,10) + '.csv';
  a.click();
}

function renderWsSubmissions() {
  const el = document.getElementById('ws-submissions');
  if (!el) return;
  const subs = window.HR_SUBMISSIONS || [];
  if (!subs.length) return;
  el.innerHTML = subs.map(s =>
    '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;padding:9px 0;border-bottom:1px solid var(--color-border)">' +
      '<div>' +
        '<p style="font-size:var(--font-size-sm);font-weight:600;color:var(--color-text-primary);margin:0">' + s.formName + '</p>' +
        '<p style="font-size:11px;color:var(--color-text-muted);margin:2px 0 0">' + s.date + '</p>' +
      '</div>' +
      '<span style="font-size:11px;font-weight:700;color:var(--color-primary);white-space:nowrap">' + s.ref + '</span>' +
    '</div>'
  ).join('');
}


