// Media Room — gallery category filter
// Extracted from app.js — GACL Intranet
// @ts-check

/* ─── Media Room filter ──────────────────────────────────────────── */
function mediaFilter(cat, btn) {
  document.querySelectorAll('.media-cat-btn').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-ghost'); });
  btn.classList.remove('btn-ghost'); btn.classList.add('btn-primary');
  document.querySelectorAll('.media-card').forEach(c => {
    c.style.display = (cat === 'All' || c.dataset.cat === cat) ? '' : 'none';
  });
}

