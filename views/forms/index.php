<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');
$dept_obj = new Departments();
$all_forms = $dept_obj->forms();

// group by category
$grouped = [];
foreach ($all_forms as $f) {
    $grouped[$f['category']][] = $f;
}
ksort($grouped);

include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<style>
.form-row{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:6px;background:#f8f9fa;margin-bottom:6px;transition:.15s}
.form-row:hover{background:#f0f0f0}
.form-icon{width:34px;height:34px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;font-weight:700;color:#fff}
.form-icon.pdf{background:#c0392b}.form-icon.doc,.form-icon.docx{background:#2980b9}.form-icon.xls,.form-icon.xlsx{background:#27ae60}
.cat-header{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin:20px 0 10px;padding-bottom:8px;border-bottom:2px solid var(--primary)}
.filter-chip{display:inline-block;padding:5px 14px;border-radius:20px;background:#f0f0f0;color:#555;font-size:12px;font-weight:600;cursor:pointer;margin:3px;transition:.15s;border:1px solid transparent}
.filter-chip.active,.filter-chip:hover{background:var(--primary);color:#fff}
</style>

<main>
<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">assignment</i>Company Forms</h5>
  <span style="font-size:13px;opacity:.8">Download and print official GACL forms</span>
</div>

<div style="margin-bottom:20px">
  <div style="position:relative;margin-bottom:12px">
    <i class="material-icons" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#bbb">search</i>
    <input type="text" id="form-search" placeholder="Search forms..." style="padding:10px 12px 10px 38px;border:1px solid #ddd;border-radius:6px;width:100%;box-sizing:border-box;font-size:13px">
  </div>
  <div id="filter-chips">
    <span class="filter-chip active" data-cat="">All Categories</span>
    <?php foreach (array_keys($grouped) as $cat): ?>
    <span class="filter-chip" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></span>
    <?php endforeach; ?>
  </div>
</div>

<?php if (count($grouped)): ?>
  <?php foreach ($grouped as $cat => $forms): ?>
  <div class="cat-block" data-category="<?= htmlspecialchars($cat) ?>">
    <div class="cat-header"><?= htmlspecialchars($cat) ?> <span style="font-weight:400;color:#aaa">(<?= count($forms) ?>)</span></div>
    <?php foreach ($forms as $f):
      $ext = strtolower(pathinfo($f['filename'], PATHINFO_EXTENSION));
      $icon_class = in_array($ext, ['pdf','doc','docx','xls','xlsx']) ? $ext : 'pdf';
    ?>
    <div class="form-row form-item">
      <div class="form-icon <?= $icon_class ?>"><?= strtoupper($ext ?: 'PDF') ?></div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px;color:#333"><?= htmlspecialchars($f['title']) ?></div>
        <div style="font-size:11px;color:#aaa;margin-top:2px">
          Version <?= htmlspecialchars($f['version']) ?>
          <?php if ($f['description']): ?> &bull; <?= htmlspecialchars($f['description']) ?><?php endif; ?>
          <?php if ($f['downloads']): ?> &bull; <?= $f['downloads'] ?> downloads<?php endif; ?>
          <?php if (!$f['dept_id']): ?> <span style="background:#e8f5e9;color:#27ae60;padding:1px 6px;border-radius:8px;margin-left:4px">General</span><?php endif; ?>
        </div>
      </div>
      <a href="<?= __url__ ?>/actions/departments.actions.php?action=download&table=dept_forms&id=<?= $f['id'] ?>"
         class="btn-flat waves-effect" style="color:#27ae60">
        <i class="material-icons">download</i>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
<?php else: ?>
<div style="text-align:center;padding:60px 20px;color:#bbb">
  <i class="material-icons" style="font-size:54px;display:block;margin-bottom:10px">assignment</i>
  <span style="font-size:14px">No forms have been uploaded yet</span><br>
  <span style="font-size:12px">Contact your department administrator to add forms</span>
</div>
<?php endif; ?>

</main>

<script>
var searchInput = document.getElementById('form-search');
var chips = document.querySelectorAll('.filter-chip');
var activeCategory = '';

function filterForms() {
  var q = searchInput.value.toLowerCase().trim();
  document.querySelectorAll('.cat-block').forEach(function(block) {
    var cat = block.dataset.category;
    var catMatch = !activeCategory || cat === activeCategory;
    var anyVisible = false;
    block.querySelectorAll('.form-item').forEach(function(row) {
      var text = row.innerText.toLowerCase();
      var match = catMatch && (!q || text.includes(q));
      row.style.display = match ? '' : 'none';
      if (match) anyVisible = true;
    });
    block.style.display = catMatch && anyVisible ? '' : 'none';
  });
}

chips.forEach(function(chip) {
  chip.addEventListener('click', function() {
    chips.forEach(c => c.classList.remove('active'));
    this.classList.add('active');
    activeCategory = this.dataset.cat;
    filterForms();
  });
});

searchInput.addEventListener('input', filterForms);
</script>

<?php include(__ROOT__ . 'layout/foot.php'); ?>
