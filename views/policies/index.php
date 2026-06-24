<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');
$dept_obj = new Departments();
$all_policies = $dept_obj->policies();

$grouped = [];
foreach ($all_policies as $p) {
    $grouped[$p['category']][] = $p;
}
ksort($grouped);

include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<style>
.policy-row{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:6px;background:#f8f9fa;margin-bottom:7px;transition:.15s;border-left:3px solid var(--primary)}
.policy-row:hover{background:#f0f0f0}
.cat-header{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin:22px 0 10px;padding-bottom:8px;border-bottom:2px solid var(--primary)}
.badge-version{background:#e3f2fd;color:#1565c0;padding:2px 8px;border-radius:10px;font-size:10px;font-weight:700}
</style>

<main>
<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">gavel</i>Policies & Procedures</h5>
  <span style="font-size:13px;opacity:.8">Official GACL policies, procedures and regulatory documents</span>
</div>

<div style="position:relative;margin-bottom:20px">
  <i class="material-icons" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#bbb">search</i>
  <input type="text" id="pol-search" placeholder="Search policies..." style="padding:10px 12px 10px 38px;border:1px solid #ddd;border-radius:6px;width:100%;box-sizing:border-box;font-size:13px">
</div>

<?php if (count($grouped)): ?>
  <?php foreach ($grouped as $cat => $policies): ?>
  <div class="cat-block">
    <div class="cat-header"><?= htmlspecialchars($cat) ?> <span style="font-weight:400;color:#aaa">(<?= count($policies) ?>)</span></div>
    <?php foreach ($policies as $p): ?>
    <div class="policy-row pol-item">
      <div style="width:38px;height:38px;background:#1a3a5c1a;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <i class="material-icons" style="color:var(--primary);font-size:18px">description</i>
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-weight:600;font-size:13px;color:#333"><?= htmlspecialchars($p['title']) ?></div>
        <div style="font-size:11px;color:#aaa;margin-top:3px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
          <span class="badge-version"><?= htmlspecialchars($p['version']) ?></span>
          <?php if ($p['effective_date']): ?>
          <span>Effective: <?= date('d M Y', strtotime($p['effective_date'])) ?></span>
          <?php endif; ?>
          <?php if ($p['review_date']): ?>
          <span>Review: <?= date('d M Y', strtotime($p['review_date'])) ?></span>
          <?php endif; ?>
          <?php if ($p['description']): ?>
          <span><?= htmlspecialchars($p['description']) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <a href="<?= __url__ ?>/actions/departments.actions.php?action=download&table=gacl_policies&id=<?= $p['id'] ?>"
         class="btn-flat waves-effect" style="color:var(--primary)">
        <i class="material-icons">download</i>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
<?php else: ?>
<div style="text-align:center;padding:60px 20px;color:#bbb">
  <i class="material-icons" style="font-size:54px;display:block;margin-bottom:10px">gavel</i>
  <span style="font-size:14px">No policies have been uploaded yet</span><br>
  <span style="font-size:12px">Contact your administrator to add policy documents</span>
</div>
<?php endif; ?>

</main>

<script>
document.getElementById('pol-search').addEventListener('input', function() {
  var q = this.value.toLowerCase().trim();
  document.querySelectorAll('.pol-item').forEach(function(row) {
    row.style.display = !q || row.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
  document.querySelectorAll('.cat-block').forEach(function(block) {
    var visible = Array.from(block.querySelectorAll('.pol-item')).some(r => r.style.display !== 'none');
    block.style.display = visible ? '' : 'none';
  });
});
</script>

<?php include(__ROOT__ . 'layout/foot.php'); ?>
