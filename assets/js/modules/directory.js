// Internal Directory — DIRECTORY data, icons, renderDirectory, dirSearch
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Internal Directory data ───────────────────────────────────── */
/** @type {DirectoryDept[]} */
const DIRECTORY = [
  { dept: "MD's Directorate", icon: 'briefcase', entries: [
    ['Managing Director','2212'], ['Executive Assistant','2248'] ] },
  { dept: 'Human Capital & Office Services', icon: 'users', entries: [
    ['Group Executive (HC & OS)','2222'], ['Executive Assistant','2240'], ['Operations Manager','2272'],
    ['Assistant Manager, Office Services','6127'], ['Manager (L & D Centre)','2340'], ['Assistant Manager (L & D Centre)','6140'],
    ['Asst Manager — Recruitment, Reward & Benefit','2408'], ['Officer, Human Capital','2460'],
    ['Admin Officer (Office Services)','2508'], ['Secretary (L & D Centre)','6139'],
    ['Assistant Manager, Performance','2293'], ['Manager, Talent Management','2223'] ] },
  { dept: 'Reception & Conference Rooms', icon: 'phone', entries: [
    ['Reception','2568'], ['Reception (T2 Extension)','6169'], ['Conference Room (Headquarters)','2394'],
    ['Conference Room (L & D Centre)','6117'], ['Registry','6159 / 2397'], ['Executive Reception','2037'] ] },
  { dept: 'Finance', icon: 'dollar', entries: [
    ['Group Executive (Finance)','2218'], ['Executive Assistant','2330'], ['Treasurer','2221'],
    ['Financial Accountant','2607'], ['Accounts Payable','6141 / 2341 / 2019'], ['Accounts Receivable','2333'],
    ['APSC','2320'], ['Credit Control / Investment','2337'], ['Accounts Reconciliation / Final Accounts','2424'],
    ['Payroll','2450'], ['Cash Office','2605'], ['Assistant Manager, Stores','2510'],
    ['Stationery Stores','2535 / 6150 / 6151'], ['Electrical Stores','2537'], ['Fixed Assets','2403'],
    ['Forex Bureau','4317'], ['Manager, Forex Bureau','2038'] ] },
  { dept: 'ICT', icon: 'monitor', entries: [
    ['Ag. Director, ICT','2263'], ['ICT Secretariat','6109'], ['Main Office','2321'],
    ['Manager, IT Security','2548'], ['Manager, IT Operations (Kamel)','6106'], ['Manager, IT Applications (Aaron)','6107'],
    ['Manager, IT Applications (Kwame)','6108'], ['Data Centre (Main Office)','2630'], ['Software Officer (Zack)','6110'],
    ['Hardware Officer (Emma)','6125'], ['Data Centre (Monitoring Room)','6101 / 6103 / 6104 / 6105'],
    ['Data Centre (cont.)','2346 / 2362 / 2627 / 2629'] ] },
  { dept: 'Procurement', icon: 'cart', entries: [
    ['Group Executive (Procurement)','6178'], ['Executive Assistant','2632'], ['Manager, Goods & Services (Kwamena)','2016'],
    ['Manager, Services (Prince)','2405'], ['Assistant Manager, Works (Daniel)','6119'], ['Assistant Manager, Services (Wisdom)','6118'],
    ['Assistant Manager, Goods (Eric)','6126'], ['Procurement (Goods)','6154 / 6155'], ['Procurement (Goods & Works)','6144'] ] },
  { dept: 'Airport Planning & Projects', icon: 'building', entries: [
    ['Director','2298'], ['Personal Assistant','2017'], ['Project Manager, Structure (G. Apenkwah)','2431'],
    ['Project Manager, Civil (E. Fynn)','2207'], ['Project Manager, Civil (Kwasi B.)','2034'], ['Architect (B. Koko)','2032'],
    ['Project Manager, Architecture (Rexford)','6132'], ['Project Manager, Quantities (Isaac Quarm)','6136'],
    ['Project Manager, Quantities (Mankata)','6133'], ['Assistant Manager, Project Quantities (Daniel)','6135'],
    ['Civil Engineers','2033 / 6134'], ['Draughtsman','6138'], ['Projects Manager (Gifty)','2355'] ] },
  { dept: 'Facilities & Infrastructure Management', icon: 'building', entries: [
    ['Director','2211'], ['Personal Assistant','2213'], ['Facilities Mgt Call Centre','3401 / 3402'] ] },
  { dept: 'Buildings, Pavements & Grounds', icon: 'building', entries: [
    ['Manager','2035'], ['Secretariat','2036'], ['Housekeeping','6148'], ['Assistant Manager (Housekeeping)','5035'],
    ['Assistant Manager (Assets)','6166'], ['Carpentry','2534'], ['Painters','2369'], ['Plumbers','2315 / 0303970495'],
    ['Masonry','2530'], ['Building Section','6181'], ['Pavement','6149'] ] },
  { dept: 'Electrical', icon: 'bolt', entries: [
    ['Manager','2437'], ['Main Office','2503'] ] },
  { dept: 'Electromechanical', icon: 'bolt', entries: [
    ['Manager, Electromechanical','2432'], ['Assistant Engineers (HVAC)','5075'], ['Assistant Managers, X-Ray & STP','2569'],
    ['Assistant Manager (STP)','5069'], ['Assistant Manager (HVAC)','3172 / 3312'], ['Air Condition Services (Gen. Office)','2465'],
    ['BHS (Engineers Office)','5032 / 2323'], ['Sewage Treatment Plant (Gen. Office)','2500 / 2325'],
    ['BHS Asst. Manager Office (T3)','3164'], ['BHS Maintenance Office (T3)','3154'],
    ['BHS Manual Encoding Stations (T3)','3194'], ['General Plumbers','2315'] ] },
  { dept: 'Transport', icon: 'truck', entries: [
    ['Manager','2570'], ['Main Office','2375'] ] },
  { dept: 'Commercial Services', icon: 'cart', entries: [
    ['Director','2409'], ['Personal Assistant','2242'], ['Commercial & Retail Manager','2456'], ['Personal Assistant','2477'],
    ['Assistant Manager (Adverts)','6147'], ['Assistant Manager (Retail)','2299'], ['Assistant Manager (Car Park)','6146'],
    ['Properties Manager','2328'], ['Properties Main Office','2436'], ['Asst. Manager (Kwame / Paul)','6115'],
    ['Asst. Manager Real Estate (V. Baryeh)','6145'], ['Asst. Manager Properties (Edem)','6176'],
    ['Cargo Operations — Assistant Manager','6177'], ['Cargo Operations — Main Office','6160'] ] },
  { dept: 'Business Development', icon: 'chart', entries: [
    ['Head, Business Development','6170'], ['Assistant Managers','2385'], ['Main Office','2438'] ] },
  { dept: 'Airport Operations', icon: 'plane', entries: [
    ['Group Executive (Airport Mgt)','2476'], ['Executive Assistant','6180'], ['Head, KIA (Mr. Ahilijah)','6142'] ] },
  { dept: 'Terminal Operations', icon: 'plane', entries: [
    ['Admin Assistant','5064'], ['Assistant Airport Managers','2506 / 2507 / 2433 / 2631'], ['Supervisor (Terminal Ops)','2577'],
    ['Customer Service Office (Arrival)','2289'], ['Customer Service (Departure Lounge)','2357'], ['Customer Service (Departure Hall)','2007 / 2008'],
    ['Housekeeping Office (Departure)','2329'], ['Flight Schedules Office (T3)','3520'], ["OP'S Room",'2319 / 2446'],
    ['Customer Service Manager','3383'], ['Customer Service Main Office','6116 / 6156 / 3132'], ['Customer Service Desk (Arrival)','2288'],
    ['Customer Service Desk (Airport Square)','2800'], ['Customer Service (Info)','2283 / 2358 / 2381'], ['Info Office (T3)','3196'],
    ['OCC (T3)','3519'], ['Customer Service Desk (Domestic)','2388'] ] },
  { dept: 'Airside Operations', icon: 'plane', entries: [
    ['Manager','2468'], ['Assistant Managers','2015'], ['Airside Ops Office','2344'], ['General Office','2331'] ] },
  { dept: 'Rescue & Fire Fighting Services', icon: 'flame', entries: [
    ['Fire Station (Watch Room)','2505'], ['Watch Room (direct)','0302953429 / 0303932297'], ['GOTA','0299002165'],
    ['Chief Fire Officer','6009'], ['Personal Assistant','6002'], ['Manager, Training','6007'], ['Manager, Operations','6004'] ] },
  { dept: 'Aviation Security (AVSEC)', icon: 'shield', entries: [
    ['Director','2295'], ['Personal Assistant','2316'], ['AVSEC Administration Officer','2576'], ['AVSEC Operations Manager','2334'],
    ['Quality Control (Main Office)','5071 / 5072'], ['MAVSEC Secretariat','2281'], ['AVSEC Quality Control Manager','2317'],
    ['Manager, Intel / Investigation','2504'], ['Intel / Investigation (Main Office)','5074'], ['Assistant AVSEC Manager','2269'],
    ['AVSEC Training Manager','2412'], ['AVSEC Training Office','2434'], ['ID Card Printing Room','2327'], ['Glass Office','2345'],
    ['Glass Office (T3)','3318'], ['Central Screening','2291'], ['VVIP Lounge','2285'], ['CCTV Room','2292'] ] },
  { dept: 'Safety & Environment', icon: 'shield', entries: [
    ['Safety Manager','6161 / 0299002206'], ['Safety Main Office','6183'], ['Safety Office (T3)','3390'] ] },
  { dept: 'Internal Audit, Compliance & Risk', icon: 'check', entries: [
    ['Director','2294'], ['Manager','2482'], ['Main Office','2528 / 2378 / 3222'] ] },
  { dept: 'Legal & Company Secretariat', icon: 'shield', entries: [
    ['Director','2297'], ['Manager','2478'], ['Assistant Manager','6171'], ['Main Office','2488'] ] },
  { dept: 'Strategy & Corporate Performance', icon: 'chart', entries: [
    ['Manager','2326'], ['Research Analysis Manager','6192'], ['Main Office','6114 / 2226'] ] },
  { dept: 'Corporate Communication & PR', icon: 'phone', entries: [
    ['Head','6111'], ['Main Office','2501 / 6162'] ] },
  { dept: 'Tamale Airport', icon: 'plane', entries: [
    ['Airport Lines','7301 / 7302 / 7312'], ['Administration','7311 / 7375 / 7376'], ['Finance','7315 / 7337 / 7310 / 7323'],
    ['ICT','7364 / 7316'], ['RFFS','7309'] ] },
];

const DIR_ICONS = {
  briefcase: '<path d="M20 7h-4V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>',
  users: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>',
  phone: '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.54 3.17 2 2 0 0 1 3.52 1h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 5.99 5.99l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
  dollar: '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
  monitor: '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
  cart: '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
  building: '<path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-3"/>',
  bolt: '<polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
  truck: '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
  chart: '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
  plane: '<path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
  flame: '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
  shield: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
  check: '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
};

/**
 * Returns inline SVG markup for a directory department icon.
 * @param {string} name  Icon key.
 * @returns {string}  SVG markup.
 */
function dirIcon(name) {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">' + (DIR_ICONS[name] || DIR_ICONS.users) + '</svg>';
}

/**
 * Returns the extension string (placeholder for future tel: linking).
 * @param {string} ext  Extension token(s).
 * @returns {string}
 */
function dirTelLink(ext) {
  // Make the first extension token clickable for internal dial
  return ext;
}

/**
 * Renders the internal directory grouped by department, filtered by query.
 * @param {string} filter  Search text (name/role/department/extension).
 * @returns {void}
 */
function renderDirectory(filter) {
  const grid = document.getElementById('dir-grid');
  const term = (filter || '').toLowerCase().trim();
  let html = '';
  let matchCount = 0;

  DIRECTORY.forEach(dept => {
    const deptMatch = dept.dept.toLowerCase().includes(term);
    const rows = dept.entries.filter(([role, ext]) =>
      !term || deptMatch || role.toLowerCase().includes(term) || ext.toLowerCase().includes(term)
    );
    if (!rows.length) return;
    matchCount += rows.length;

    html += '<div class="dir-dept">'
          + '<div class="dir-dept-head">'
          +   '<div class="dir-dept-head-left">' + dirIcon(dept.icon) + '<span class="dir-dept-title">' + dept.dept + '</span></div>'
          +   '<span class="dir-dept-count">' + rows.length + '</span>'
          + '</div>'
          + '<div class="dir-dept-body">';
    rows.forEach(([role, ext]) => {
      const telExt = ext.split(/[/·]/)[0].trim().replace(/\s/g, '');
      html += '<div class="dir-ext-row">'
            + '<span class="dir-role">' + role + '</span>'
            + '<span class="dir-ext"><a href="tel:' + telExt + '" title="Call ' + ext + '">' + ext + '</a></span>'
            + '</div>';
    });
    html += '</div></div>';
  });

  grid.innerHTML = html;
  document.getElementById('dir-no-results').style.display = matchCount ? 'none' : 'block';
  const totalExt = DIRECTORY.reduce((s, d) => s + d.entries.length, 0);
  document.getElementById('dir-count').textContent = term ? matchCount + ' result' + (matchCount !== 1 ? 's' : '') : totalExt + ' extensions';
}

/**
 * Internal directory search handler — re-renders filtered by query.
 * @param {string} q  Raw search text.
 * @returns {void}
 */

