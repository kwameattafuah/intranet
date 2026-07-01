// General Discussions — post, filter, render staff noticeboard
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Discussions ────────────────────────────────────────────────── */
let discPosts = [
  { id:1, title:'Staff ID Card Renewal Reminder', cat:'HR', body:'All staff are reminded to renew their ID cards at the HR office on the 2nd floor of the terminal building before 30 June 2026. You will need a passport photo and your staff number.', author:'HCOS Office', time:'2 hours ago', replies:3 },
  { id:2, title:'Network Maintenance — Saturday 28 Jun, 11pm–2am', cat:'ICT', body:'The ICT department will be performing scheduled maintenance on the core network. Expect brief interruptions to FIDS, internet, and intranet services between 11:00 PM and 2:00 AM. All systems will be fully restored by 2:15 AM.', author:'ICT Help Desk', time:'Yesterday', replies:7 },
  { id:3, title:'Canteen menu update — new options available', cat:'General', body:'The staff canteen has introduced three new lunch options: jollof rice with grilled chicken, vegetable fried rice, and plantain with beans. Prices remain the same. Enjoy!', author:'Office Services', time:'2 days ago', replies:12 },
  { id:4, title:'Aviation Security Annual Drill — all staff note', cat:'Safety', body:'The annual aviation security emergency drill will take place on Friday 4 July 2026 at 10:00 AM. Please do not be alarmed. All airside staff should report to their designated muster points as per the Emergency Response Plan.', author:'AVSEC Department', time:'3 days ago', replies:2 },
  { id:5, title:'Q2 2026 Finance Claims — deadline reminder', cat:'Finance', body:'All expenditure and kilometric claims for the period April–June 2026 must be submitted by COB Friday 27 June 2026. Claims submitted after this date will be processed in the next cycle. Use the online HCOS Forms to submit.', author:'Finance Department', time:'4 days ago', replies:5 },
  { id:6, title:'Congratulations to our ACI training graduates!', cat:'General', body:'We are proud to announce that 12 GACL staff have successfully completed the ACI Airport Customer Experience accreditation programme. This is a significant achievement for our service delivery commitment. Well done all!', author:'L&D Centre', time:'5 days ago', replies:18 },
];
let discNextId = 7;
let discCurrentFilter = 'All';
let discInitialized = false;

function initDiscussions() {
  if (!discInitialized) { discInitialized = true; renderDiscPosts(); }
}

function discPost() {
  const title = document.getElementById('disc-title').value.trim();
  const cat = document.getElementById('disc-cat').value;
  const body = document.getElementById('disc-body').value.trim();
  const author = document.getElementById('disc-author').value.trim() || 'Anonymous';
  if (!title || !body) { alert('Please enter a title and message.'); return; }
  discPosts.unshift({ id: discNextId++, title, cat, body, author, time: 'Just now', replies: 0 });
  document.getElementById('disc-title').value = '';
  document.getElementById('disc-body').value = '';
  document.getElementById('disc-author').value = '';
  renderDiscPosts();
}

/**
 * Filters the discussion noticeboard by category.
 * @param {string} cat       Category, or 'All'.
 * @param {HTMLElement} btn   The clicked filter chip.
 * @returns {void}
 */
function discFilter(cat, btn) {
  discCurrentFilter = cat;
  document.querySelectorAll('.disc-filter-btn').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-ghost'); });
  btn.classList.remove('btn-ghost'); btn.classList.add('btn-primary');
  renderDiscPosts();
}

const catColors = { General:'badge-neutral', Operations:'badge-warning', ICT:'badge-primary', Finance:'badge-success', HR:'badge-info', Safety:'badge-danger' };

function renderDiscPosts() {
  const container = document.getElementById('disc-posts-container');
  if (!container) return;
  const filtered = discCurrentFilter === 'All' ? discPosts : discPosts.filter(p => p.cat === discCurrentFilter);
  if (!filtered.length) { container.innerHTML = '<div class="card"><div class="card-body" style="text-align:center;padding:40px;color:var(--color-text-muted)">No notices in this category yet.</div></div>'; return; }
  container.innerHTML = filtered.map(p => `
    <div class="card"><div class="card-body">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:8px">
        <div><span class="badge ${catColors[p.cat]||'badge-neutral'}" style="font-size:10px;margin-bottom:6px;display:inline-block">${p.cat}</span><h3 style="font-size:15px;font-weight:700;color:var(--color-text-primary)">${p.title}</h3></div>
        <span style="font-size:11px;color:var(--color-text-muted);white-space:nowrap">${p.time}</span>
      </div>
      <p style="font-size:13px;color:var(--color-text-secondary);line-height:1.7;margin-bottom:12px">${p.body.length > 180 ? p.body.slice(0,180)+'…' : p.body}</p>
      <div style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--color-text-muted)">Posted by <strong>${p.author}</strong></span>
        <span style="font-size:12px;color:var(--color-text-muted)"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:3px"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>${p.replies} ${p.replies===1?'reply':'replies'}</span>
      </div>
    </div></div>
  `).join('');
}


