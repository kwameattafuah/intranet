// ICT Ticketing System — all GACL stations
// Staff helpdesk (submit + track) and ICT Admin (queue, dashboard, team)
// Stations: Accra (KIA & Head Office), Kumasi (Prempeh I Int'l), Tamale Int'l
// Storage: localStorage 'gacl_ict_tickets' (all tickets), 'gacl_ict_my' (ids raised in this browser)

var TK_KEY  = 'gacl_ict_tickets';
var TK_MY   = 'gacl_ict_my';
var TK_SEQ  = 'gacl_ict_seq';

var ICT_STATIONS = {
  Accra:  { label: "Accra — KIA & Head Office",   covers: ["Kotoka Int'l — Terminal 1", "Kotoka Int'l — Terminal 2", "Kotoka Int'l — Terminal 3", "GACL Head Office", "Ho Airport"] },
  Kumasi: { label: "Kumasi — Prempeh I Int'l",    covers: ["Prempeh I Int'l Airport (Kumasi)", "Sunyani Airport"] },
  Tamale: { label: "Tamale Int'l",                covers: ["Tamale Int'l Airport", "Wa Airport"] }
};

var ICT_TEAM = [
  { n: 'Help Desk (Accra)',   r: 'General IT Support',            ext: '1090', st: 'Accra'  },
  { n: 'Network Team (Accra)',r: 'Network & Connectivity',        ext: '1091', st: 'Accra'  },
  { n: 'K. Attafuah',         r: 'Systems Administrator',         ext: '1042', st: 'Accra'  },
  { n: 'Kumasi ICT Desk',     r: 'Station Support — Prempeh I',   ext: '3090', st: 'Kumasi' },
  { n: 'Y. Osei',             r: 'ICT Officer, Kumasi',           ext: '3092', st: 'Kumasi' },
  { n: 'Tamale ICT Desk',     r: 'Station Support — Tamale',      ext: '4090', st: 'Tamale' },
  { n: 'I. Abdulai',          r: 'ICT Officer, Tamale',           ext: '4092', st: 'Tamale' },
  { n: 'Ag. Director, ICT',   r: 'Escalations only',              ext: '2263', st: 'Accra'  }
];

var TK_CATS = ['Hardware', 'Software', 'Network', 'Email / M365', 'Access / Permissions', 'Telephony', 'FIDS / AODB', 'CUTE / Check-in Systems', 'CCTV & Security Systems', 'Printing', 'Other'];
var TK_PRIORITIES = ['Low', 'Normal', 'High', 'Critical'];
var TK_STATUSES   = ['Open', 'In Progress', 'On Hold', 'Resolved', 'Closed'];
var TK_SLA = { Critical: '2 hours', High: '4 hours', Normal: '1 business day', Low: '3 business days' };

/* ── store ── */
function tkAll()      { try { return JSON.parse(localStorage.getItem(TK_KEY)) || []; } catch (e) { return []; } }
function tkSave(list) { localStorage.setItem(TK_KEY, JSON.stringify(list)); }
function tkMy()       { try { return JSON.parse(localStorage.getItem(TK_MY)) || []; } catch (e) { return []; } }
function tkGet(id)    { return tkAll().find(function (t) { return t.id === id; }); }

function tkStationFor(location) {
  for (var k in ICT_STATIONS) { if (ICT_STATIONS[k].covers.indexOf(location) > -1) return k; }
  return 'Accra';
}

function tkNextId() {
  var seq = parseInt(localStorage.getItem(TK_SEQ) || '190', 10) + 1;
  localStorage.setItem(TK_SEQ, String(seq));
  return 'ICT-' + new Date().getFullYear() + '-' + String(seq).padStart(4, '0');
}

function tkUpdate(id, fn) {
  var list = tkAll();
  var t = list.find(function (x) { return x.id === id; });
  if (!t) return;
  fn(t);
  t.updated = new Date().toISOString();
  tkSave(list);
}

/* ── seed ── */
function tkSeed() {
  if (tkAll().length) return;
  var d = function (daysAgo, h) { var x = new Date(); x.setDate(x.getDate() - daysAgo); x.setHours(h || 9, 15, 0, 0); return x.toISOString(); };
  tkSave([
    { id: 'ICT-2026-0184', title: 'Badge reader offline — Gate 4', desc: 'Access control badge reader at Gate 4 not powering on. Guards using manual log.', cat: 'CCTV & Security Systems', priority: 'Critical', status: 'In Progress', location: "Kotoka Int'l — Terminal 3", station: 'Accra', dept: 'Airports Management', requester: 'Emmanuel Owusu', contact: 'Ext. 2210', assignee: 'K. Attafuah', created: d(14, 8), updated: d(13),
      history: [{ ts: d(14, 8), note: 'Ticket created' }, { ts: d(13), note: 'Assigned to K. Attafuah — replacement reader ordered' }] },
    { id: 'ICT-2026-0185', title: 'Cannot access payroll module', desc: 'Payroll module rejects login since Monday. Error: account locked.', cat: 'Access / Permissions', priority: 'Critical', status: 'Open', location: 'GACL Head Office', station: 'Accra', dept: 'Human Capital & OS', requester: 'Abena Mensah', contact: 'Ext. 2015', assignee: '', created: d(13, 10), updated: d(13, 10),
      history: [{ ts: d(13, 10), note: 'Ticket created' }] },
    { id: 'ICT-2026-0186', title: 'Projector not working — Training Room A', desc: 'HDMI input shows no signal from any laptop.', cat: 'Hardware', priority: 'Normal', status: 'In Progress', location: 'GACL Head Office', station: 'Accra', dept: 'L&D Centre', requester: 'Ama Boateng', contact: 'Ext. 2130', assignee: 'Help Desk (Accra)', created: d(12), updated: d(11),
      history: [{ ts: d(12), note: 'Ticket created' }, { ts: d(11), note: 'Cable suspected faulty; replacement being tested' }] },
    { id: 'ICT-2026-0187', title: 'Email not syncing on mobile', desc: 'Outlook mobile stopped syncing after password change.', cat: 'Email / M365', priority: 'Normal', status: 'Resolved', location: 'GACL Head Office', station: 'Accra', dept: 'ICT', requester: 'K. Attafuah', contact: 'Ext. 1042', assignee: 'Network Team (Accra)', created: d(11), updated: d(9),
      history: [{ ts: d(11), note: 'Ticket created' }, { ts: d(9), note: 'Re-authenticated account on device — resolved' }] },
    { id: 'ICT-2026-0188', title: 'VPN access request — new laptop', desc: 'New Finance laptop needs VPN profile for remote month-end work.', cat: 'Access / Permissions', priority: 'Normal', status: 'Open', location: 'GACL Head Office', station: 'Accra', dept: 'Finance', requester: 'Kofi Asante', contact: 'Ext. 2440', assignee: '', created: d(9), updated: d(9),
      history: [{ ts: d(9), note: 'Ticket created' }] },
    { id: 'ICT-2026-0189', title: 'FIDS screen frozen — Departures hall', desc: 'Main departures FIDS display frozen on 05:40 schedule. Passengers relying on gate agents.', cat: 'FIDS / AODB', priority: 'High', status: 'In Progress', location: "Prempeh I Int'l Airport (Kumasi)", station: 'Kumasi', dept: 'Airports Management', requester: 'Akosua Frimpong', contact: 'Ext. 3210', assignee: 'Kumasi ICT Desk', created: d(6, 6), updated: d(6, 8),
      history: [{ ts: d(6, 6), note: 'Ticket created' }, { ts: d(6, 8), note: 'Display controller rebooted; monitoring for recurrence' }] },
    { id: 'ICT-2026-0190', title: 'Check-in counter 3 printer jamming', desc: 'Boarding pass printer at counter 3 jams every few passes.', cat: 'CUTE / Check-in Systems', priority: 'High', status: 'Open', location: "Tamale Int'l Airport", station: 'Tamale', dept: 'Airports Management', requester: 'Mohammed Iddrisu', contact: 'Ext. 4210', assignee: 'Tamale ICT Desk', created: d(2, 7), updated: d(2, 7),
      history: [{ ts: d(2, 7), note: 'Ticket created' }] },
    { id: 'ICT-2026-0183', title: 'Printer offline — Block B', desc: 'Shared printer unreachable from all Block B machines.', cat: 'Printing', priority: 'Low', status: 'Closed', location: 'GACL Head Office', station: 'Accra', dept: 'ICT', requester: 'K. Attafuah', contact: 'Ext. 1042', assignee: 'Help Desk (Accra)', created: d(16), updated: d(14),
      history: [{ ts: d(16), note: 'Ticket created' }, { ts: d(14), note: 'Print spooler restarted; confirmed working — closed' }] }
  ]);
}

/* ── shared helpers ── */
function tkEsc(s) { return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }

function tkStatusBadge(status) {
  var map = { Open: 'badge-primary', 'In Progress': 'badge-warning', 'On Hold': 'badge-neutral', Resolved: 'badge-success', Closed: 'badge-neutral' };
  return '<span class="badge ' + (map[status] || 'badge-neutral') + '">' + status + '</span>';
}

function tkPrioBadge(p) {
  var map = { Critical: 'badge-danger', High: 'badge-warning', Normal: 'badge-primary', Low: 'badge-neutral' };
  return '<span class="badge ' + (map[p] || 'badge-neutral') + '">' + p + '</span>';
}

function tkFmtDate(iso) {
  if (!iso) return '—';
  var dt = new Date(iso);
  return dt.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }) + ', ' + dt.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
}

/* ══ STAFF HELPDESK PAGE ══ */
function hdLocationOptions() {
  var html = '';
  Object.keys(ICT_STATIONS).forEach(function (k) {
    html += '<optgroup label="' + tkEsc(ICT_STATIONS[k].label) + '">';
    ICT_STATIONS[k].covers.forEach(function (loc) { html += '<option>' + tkEsc(loc) + '</option>'; });
    html += '</optgroup>';
  });
  return html;
}

function hdInit() {
  tkSeed();
  var locSel = document.getElementById('hd-location');
  if (locSel && !locSel.options.length) locSel.innerHTML = '<option value="" disabled selected>Where is the issue?</option>' + hdLocationOptions();
  var catSel = document.getElementById('hd-cat');
  if (catSel && catSel.options.length <= 1) catSel.innerHTML = '<option value="" disabled selected>Select category</option>' + TK_CATS.map(function (c) { return '<option>' + c + '</option>'; }).join('');
  hdRenderMy();
}

function hdRenderMy() {
  var wrap = document.getElementById('hd-my-tickets');
  if (!wrap) return;
  var my = tkMy();
  var mine = tkAll().filter(function (t) { return my.indexOf(t.id) > -1; })
    .sort(function (a, b) { return b.created.localeCompare(a.created); });
  if (!mine.length) {
    wrap.innerHTML = '<p style="font-size:13px;color:var(--color-text-muted);padding:20px 0;text-align:center">No tickets raised from this device yet. Log one below — you will be able to track its status here.</p>';
    return;
  }
  wrap.innerHTML = '<div class="table-wrap"><table aria-label="My tickets"><thead><tr><th>Ticket #</th><th>Issue</th><th>Location</th><th>Priority</th><th>Assigned To</th><th>Status</th></tr></thead><tbody>'
    + mine.map(function (t) {
        return '<tr><td style="font-weight:700;color:var(--color-primary);white-space:nowrap">#' + t.id + '</td>'
          + '<td>' + tkEsc(t.title) + '</td>'
          + '<td style="font-size:12px">' + tkEsc(t.location) + '</td>'
          + '<td>' + tkPrioBadge(t.priority) + '</td>'
          + '<td style="font-size:12px">' + (tkEsc(t.assignee) || '<span style="color:var(--color-text-muted)">Awaiting assignment</span>') + '</td>'
          + '<td>' + tkStatusBadge(t.status) + '</td></tr>';
      }).join('')
    + '</tbody></table></div>';
}

function hdSubmit(e) {
  e.preventDefault();
  var f = e.target;
  var location = f.querySelector('#hd-location').value;
  var priority = f.querySelector('#hd-priority').value;
  if (!location) { alert('Please select the location of the issue.'); return; }
  var t = {
    id: tkNextId(),
    title: f.querySelector('#hd-title').value.trim(),
    desc: f.querySelector('#hd-desc').value.trim(),
    cat: f.querySelector('#hd-cat').value,
    priority: priority,
    status: 'Open',
    location: location,
    station: tkStationFor(location),
    dept: f.querySelector('#hd-dept').value,
    requester: f.querySelector('#hd-name').value.trim() || 'Anonymous',
    contact: f.querySelector('#hd-contact').value.trim(),
    assignee: '',
    created: new Date().toISOString(),
    updated: new Date().toISOString(),
    history: [{ ts: new Date().toISOString(), note: 'Ticket created' }]
  };
  var list = tkAll(); list.push(t); tkSave(list);
  var my = tkMy(); my.push(t.id); localStorage.setItem(TK_MY, JSON.stringify(my));
  f.reset();
  hdRenderMy();
  var ok = document.getElementById('hd-submit-ok');
  if (ok) {
    ok.innerHTML = 'Ticket <strong>#' + t.id + '</strong> logged — routed to the <strong>' + tkEsc(ICT_STATIONS[t.station].label) + '</strong> team. Target response: ' + TK_SLA[t.priority] + '.';
    ok.style.display = 'block';
    setTimeout(function () { ok.style.display = 'none'; }, 8000);
  }
}

/* ══ ICT ADMIN PAGE ══ */
function ictAdminRender() {
  tkSeed();
  ictRenderTable();
  ictRenderDashboard();
  ictRenderTeam();
  var open = tkAll().filter(function (t) { return t.status !== 'Resolved' && t.status !== 'Closed'; });
  var crit = open.filter(function (t) { return t.priority === 'Critical'; }).length;
  var badge = document.getElementById('ict-hdr-badge');
  if (badge) {
    if (crit) { badge.className = 'badge badge-danger'; badge.textContent = crit + ' Critical Open'; }
    else { badge.className = 'badge badge-success'; badge.textContent = open.length + ' Open Tickets'; }
    badge.style.cssText = 'font-size:12px;padding:6px 12px;align-self:center';
  }
}

function ictFiltered() {
  var q  = (document.getElementById('ictf-q')       || {}).value || '';
  var st = (document.getElementById('ictf-status')  || {}).value || '';
  var ca = (document.getElementById('ictf-cat')     || {}).value || '';
  var sn = (document.getElementById('ictf-station') || {}).value || '';
  var pr = (document.getElementById('ictf-prio')    || {}).value || '';
  q = q.toLowerCase().trim();
  return tkAll().filter(function (t) {
    if (st && t.status !== st) return false;
    if (ca && t.cat !== ca) return false;
    if (sn && t.station !== sn) return false;
    if (pr && t.priority !== pr) return false;
    if (q && (t.id + ' ' + t.title + ' ' + t.requester + ' ' + t.dept + ' ' + t.location).toLowerCase().indexOf(q) < 0) return false;
    return true;
  }).sort(function (a, b) {
    var open = function (t) { return (t.status === 'Resolved' || t.status === 'Closed') ? 1 : 0; };
    if (open(a) !== open(b)) return open(a) - open(b);
    var po = { Critical: 0, High: 1, Normal: 2, Low: 3 };
    if (po[a.priority] !== po[b.priority]) return po[a.priority] - po[b.priority];
    return b.created.localeCompare(a.created);
  });
}

function ictAssigneeSelect(t) {
  var html = '<select class="form-control" style="height:34px;font-size:12px;width:150px" onchange="ictSetAssignee(\'' + t.id + '\',this.value)">'
    + '<option value=""' + (t.assignee ? '' : ' selected') + '>Unassigned</option>';
  Object.keys(ICT_STATIONS).forEach(function (k) {
    html += '<optgroup label="' + tkEsc(ICT_STATIONS[k].label) + '">';
    ICT_TEAM.filter(function (m) { return m.st === k; }).forEach(function (m) {
      html += '<option' + (t.assignee === m.n ? ' selected' : '') + '>' + tkEsc(m.n) + '</option>';
    });
    html += '</optgroup>';
  });
  return html + '</select>';
}

function ictStatusSelect(t) {
  return '<select class="form-control" style="height:34px;font-size:12px;width:118px" onchange="ictSetStatus(\'' + t.id + '\',this.value)">'
    + TK_STATUSES.map(function (s) { return '<option' + (t.status === s ? ' selected' : '') + '>' + s + '</option>'; }).join('')
    + '</select>';
}

function ictRenderTable() {
  var tbody = document.getElementById('ict-tickets-tbody');
  if (!tbody) return;
  var rows = ictFiltered();
  var cnt = document.getElementById('ictf-count');
  if (cnt) cnt.textContent = rows.length + ' ticket' + (rows.length === 1 ? '' : 's');
  if (!rows.length) {
    tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:28px;color:var(--color-text-muted);font-size:13px">No tickets match the current filters.</td></tr>';
    return;
  }
  tbody.innerHTML = rows.map(function (t) {
    return '<tr>'
      + '<td style="font-weight:700;color:var(--color-primary);white-space:nowrap;cursor:pointer" onclick="ictToggleDetail(\'' + t.id + '\')" title="View details">#' + t.id + '</td>'
      + '<td><div style="font-weight:600">' + tkEsc(t.title) + '</div><div style="font-size:11px;color:var(--color-text-muted)">' + tkEsc(t.requester) + ' · ' + tkEsc(t.dept) + '</div></td>'
      + '<td style="font-size:12px">' + tkEsc(t.location) + '</td>'
      + '<td><span class="badge badge-neutral" style="font-size:10px">' + tkEsc(t.cat) + '</span></td>'
      + '<td>' + tkPrioBadge(t.priority) + '</td>'
      + '<td style="white-space:nowrap;font-size:12px">' + tkFmtDate(t.created) + '</td>'
      + '<td>' + ictAssigneeSelect(t) + '</td>'
      + '<td>' + ictStatusSelect(t) + '</td>'
      + '<td><div style="display:flex;gap:4px">'
      +   '<button class="btn btn-sm btn-ghost" style="height:34px" onclick="ictToggleDetail(\'' + t.id + '\')">Details</button>'
      +   (t.priority !== 'Critical' && t.status !== 'Resolved' && t.status !== 'Closed'
            ? '<button class="btn btn-sm" style="background:var(--color-danger-light);color:var(--color-danger);height:34px" onclick="ictEscalateTk(\'' + t.id + '\')">Escalate</button>' : '')
      + '</div></td>'
      + '</tr>'
      + '<tr id="ict-detail-' + t.id + '" style="display:none"><td colspan="9" style="background:var(--color-surface);padding:16px 20px">'
      +   '<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">'
      +     '<div>'
      +       '<p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);margin-bottom:6px">Description</p>'
      +       '<p style="font-size:13px;line-height:1.6;margin-bottom:14px">' + tkEsc(t.desc || 'No details provided.') + '</p>'
      +       '<p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);margin-bottom:6px">Activity</p>'
      +       (t.history || []).map(function (h) { return '<div style="font-size:12px;padding:5px 0;border-bottom:1px solid var(--color-border)"><span style="color:var(--color-text-muted)">' + tkFmtDate(h.ts) + '</span> — ' + tkEsc(h.note) + '</div>'; }).join('')
      +       '<div style="display:flex;gap:8px;margin-top:10px">'
      +         '<input class="form-control" id="ict-note-' + t.id + '" type="text" placeholder="Add a work note…" style="flex:1;height:36px;font-size:12px">'
      +         '<button class="btn btn-sm btn-primary" style="height:36px" onclick="ictAddNote(\'' + t.id + '\')">Add Note</button>'
      +       '</div>'
      +     '</div>'
      +     '<div style="font-size:12px;line-height:2">'
      +       '<div><strong>Requester:</strong> ' + tkEsc(t.requester) + (t.contact ? ' (' + tkEsc(t.contact) + ')' : '') + '</div>'
      +       '<div><strong>Department:</strong> ' + tkEsc(t.dept) + '</div>'
      +       '<div><strong>Station:</strong> ' + tkEsc(ICT_STATIONS[t.station] ? ICT_STATIONS[t.station].label : t.station) + '</div>'
      +       '<div><strong>SLA target:</strong> ' + (TK_SLA[t.priority] || '—') + '</div>'
      +       '<div><strong>Last update:</strong> ' + tkFmtDate(t.updated) + '</div>'
      +     '</div>'
      +   '</div>'
      + '</td></tr>';
  }).join('');
}

function ictToggleDetail(id) {
  var row = document.getElementById('ict-detail-' + id);
  if (row) row.style.display = row.style.display === 'none' ? '' : 'none';
}

function ictSetAssignee(id, val) {
  tkUpdate(id, function (t) {
    t.assignee = val;
    if (val && t.status === 'Open') t.status = 'In Progress';
    t.history.push({ ts: new Date().toISOString(), note: val ? 'Assigned to ' + val : 'Unassigned' });
  });
  ictAdminRender();
}

function ictSetStatus(id, val) {
  tkUpdate(id, function (t) {
    t.status = val;
    t.history.push({ ts: new Date().toISOString(), note: 'Status changed to ' + val });
  });
  ictAdminRender();
  hdRenderMy();
}

function ictEscalateTk(id) {
  tkUpdate(id, function (t) {
    t.priority = 'Critical';
    t.assignee = 'Ag. Director, ICT';
    t.history.push({ ts: new Date().toISOString(), note: 'Escalated to Ag. Director, ICT — priority raised to Critical' });
  });
  ictAdminRender();
}

function ictAddNote(id) {
  var inp = document.getElementById('ict-note-' + id);
  if (!inp || !inp.value.trim()) return;
  var note = inp.value.trim();
  tkUpdate(id, function (t) { t.history.push({ ts: new Date().toISOString(), note: note }); });
  ictRenderTable();
  var row = document.getElementById('ict-detail-' + id);
  if (row) row.style.display = '';
}

function ictExportCSV() {
  var rows = ictFiltered();
  var head = ['Ticket', 'Title', 'Requester', 'Contact', 'Department', 'Location', 'Station', 'Category', 'Priority', 'Status', 'Assignee', 'Created', 'Updated'];
  var csv = [head.join(',')].concat(rows.map(function (t) {
    return [t.id, t.title, t.requester, t.contact, t.dept, t.location, t.station, t.cat, t.priority, t.status, t.assignee, t.created, t.updated]
      .map(function (v) { return '"' + String(v == null ? '' : v).replace(/"/g, '""') + '"'; }).join(',');
  })).join('\n');
  var a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
  a.download = 'gacl-ict-tickets-' + new Date().toISOString().slice(0, 10) + '.csv';
  a.click();
  URL.revokeObjectURL(a.href);
}

/* ── dashboard ── */
function ictBar(label, val, max, color) {
  var pct = max ? Math.round(val / max * 100) : 0;
  return '<div class="dept-bar-row"><span class="dept-bar-label">' + tkEsc(label) + '</span><div class="dept-bar-track"><div class="dept-bar-fill" style="width:' + pct + '%;background:' + color + '"></div></div><span class="dept-bar-pct">' + val + '</span></div>';
}

function ictRenderDashboard() {
  var el = document.getElementById('ict-dash-body');
  if (!el) return;
  var all = tkAll();
  var by = function (fn) { var m = {}; all.forEach(function (t) { var k = fn(t); m[k] = (m[k] || 0) + 1; }); return m; };
  var stat = by(function (t) { return t.status; });
  var open = all.filter(function (t) { return t.status !== 'Resolved' && t.status !== 'Closed'; });
  var crit = open.filter(function (t) { return t.priority === 'Critical'; }).length;
  var resolved = (stat['Resolved'] || 0) + (stat['Closed'] || 0);
  var rate = all.length ? Math.round(resolved / all.length * 100) : 0;

  var cards = [
    { v: crit,                     l: 'Critical Open',  c: 'var(--color-danger)',  bg: 'var(--color-danger-light)' },
    { v: stat['Open'] || 0,        l: 'Open',           c: 'var(--color-primary)', bg: '#e8eef5' },
    { v: stat['In Progress'] || 0, l: 'In Progress',    c: 'var(--color-warning)', bg: 'var(--color-warning-light)' },
    { v: stat['On Hold'] || 0,     l: 'On Hold',        c: 'var(--color-text-muted)', bg: 'var(--color-surface)' },
    { v: resolved,                 l: 'Resolved / Closed', c: 'var(--color-success)', bg: 'var(--color-success-light)' },
    { v: rate + '%',               l: 'Resolution Rate', c: 'var(--color-success)', bg: 'var(--color-success-light)' }
  ];

  var catMap = by(function (t) { return t.cat; });
  var maxCat = Math.max.apply(null, Object.keys(catMap).map(function (k) { return catMap[k]; }).concat([1]));
  var stMap = by(function (t) { return t.station; });
  var deptMap = by(function (t) { return t.dept; });
  var maxDept = Math.max.apply(null, Object.keys(deptMap).map(function (k) { return deptMap[k]; }).concat([1]));

  el.innerHTML =
    '<div class="stats-grid" style="margin-bottom:24px">'
    + cards.map(function (c) {
        return '<div class="stat-card" style="border-top-color:' + c.c + '"><div class="stat-icon" style="background:' + c.bg + ';color:' + c.c + ';font-weight:800;font-size:15px;display:flex;align-items:center;justify-content:center">' + String(c.v).charAt(0) + '</div><div><div class="stat-value">' + c.v + '</div><div class="stat-label">' + c.l + '</div></div></div>';
      }).join('')
    + '</div>'
    + '<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">'
    +   '<div class="card"><div class="card-body"><h2 class="section-heading">Tickets by Category</h2><div style="display:flex;flex-direction:column;gap:12px;margin-top:4px">'
    +     Object.keys(catMap).sort(function (a, b) { return catMap[b] - catMap[a]; }).map(function (k) { return ictBar(k, catMap[k], maxCat, 'var(--color-primary)'); }).join('')
    +   '</div></div></div>'
    +   '<div>'
    +     '<div class="card" style="margin-bottom:20px"><div class="card-body"><h2 class="section-heading">Tickets by Station</h2><div style="display:flex;flex-direction:column;gap:12px;margin-top:4px">'
    +       Object.keys(ICT_STATIONS).map(function (k) { return ictBar(ICT_STATIONS[k].label, stMap[k] || 0, Math.max.apply(null, Object.keys(stMap).map(function (x) { return stMap[x]; }).concat([1])), 'var(--color-accent)'); }).join('')
    +     '</div></div></div>'
    +     '<div class="card"><div class="card-body"><h2 class="section-heading">Tickets by Department</h2><div style="display:flex;flex-direction:column;gap:12px;margin-top:4px">'
    +       Object.keys(deptMap).sort(function (a, b) { return deptMap[b] - deptMap[a]; }).slice(0, 6).map(function (k) { return ictBar(k, deptMap[k], maxDept, 'var(--color-accent)'); }).join('')
    +     '</div></div></div>'
    +   '</div>'
    + '</div>';
}

/* ── team & workload ── */
function ictRenderTeam() {
  var el = document.getElementById('ict-team-body');
  if (!el) return;
  var all = tkAll();
  var counts = function (name, status) {
    return all.filter(function (t) { return t.assignee === name && t.status === status; }).length;
  };
  var resolvedFor = function (name) {
    return all.filter(function (t) { return t.assignee === name && (t.status === 'Resolved' || t.status === 'Closed'); }).length;
  };
  el.innerHTML = Object.keys(ICT_STATIONS).map(function (st) {
    var members = ICT_TEAM.filter(function (m) { return m.st === st; });
    return '<div class="card" style="margin-bottom:20px"><div class="card-body">'
      + '<h2 class="section-heading">' + tkEsc(ICT_STATIONS[st].label) + '</h2>'
      + '<p style="font-size:12px;color:var(--color-text-muted);margin-bottom:12px">Covers: ' + ICT_STATIONS[st].covers.join(' · ') + '</p>'
      + '<div class="table-wrap"><table><thead><tr><th>Team Member</th><th>Role</th><th>Ext.</th><th>Open</th><th>In Progress</th><th>Resolved</th><th>Load</th></tr></thead><tbody>'
      + members.map(function (m) {
          var op = counts(m.n, 'Open'), ip = counts(m.n, 'In Progress'), rs = resolvedFor(m.n);
          var active = op + ip;
          var load = active >= 3 ? '<span class="badge badge-danger">Heavy</span>' : active >= 1 ? '<span class="badge badge-warning">Busy</span>' : '<span class="badge badge-success">Available</span>';
          if (m.n === 'Ag. Director, ICT') load = '<span class="badge badge-neutral">Escalations only</span>';
          return '<tr><td><strong>' + tkEsc(m.n) + '</strong></td><td>' + tkEsc(m.r) + '</td><td>' + m.ext + '</td>'
            + '<td><span class="badge badge-primary">' + op + '</span></td>'
            + '<td><span class="badge badge-warning">' + ip + '</span></td>'
            + '<td><span class="badge badge-success">' + rs + '</span></td>'
            + '<td>' + load + '</td></tr>';
        }).join('')
      + '</tbody></table></div></div></div>';
  }).join('');
}
