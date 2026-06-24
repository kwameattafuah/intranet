<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');
$dept = new Departments();
$depts = $dept->all();
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<style>
.dept-card{border-left:5px solid;margin-bottom:14px;transition:.15s;cursor:pointer}
.dept-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.12);transform:translateY(-1px)}
.search-box{position:relative;margin-bottom:20px}
.search-box input{padding-left:40px !important}
.search-box .search-icon{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#bbb}
</style>

<main>
<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">business</i>GACL Departments</h5>
  <span style="font-size:13px;opacity:.8">Select a department to access contacts, files, forms and announcements</span>
</div>

<div class="search-box">
  <i class="material-icons search-icon">search</i>
  <input type="text" id="dept-search" placeholder="Search departments..." style="border-radius:6px;border:1px solid #ddd;padding:10px 12px;width:100%;box-sizing:border-box;font-size:13px">
</div>

<div class="row" id="dept-grid">
  <?php foreach ($depts as $d): ?>
  <div class="col s12 m6 l4 dept-item" data-name="<?= htmlspecialchars(strtolower($d['name'].' '.$d['short_name'])) ?>">
    <a href="<?= __url__ ?>/departments/<?= $d['slug'] ?>/" style="display:block">
    <div class="card dept-card" style="border-left-color:<?= htmlspecialchars($d['color']) ?>;margin-bottom:0">
      <div class="card-content" style="padding:16px 18px">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:44px;height:44px;background:<?= htmlspecialchars($d['color']) ?>1a;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:<?= htmlspecialchars($d['color']) ?>;font-size:22px"><?= htmlspecialchars($d['icon']) ?></i>
          </div>
          <div style="flex:1;min-width:0">
            <div style="font-weight:700;font-size:13px;color:var(--primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($d['short_name'] ?: $d['name']) ?></div>
            <?php if ($d['short_name']): ?>
            <div style="font-size:11px;color:#aaa;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($d['name']) ?></div>
            <?php endif; ?>
            <?php if ($d['location']): ?>
            <div style="font-size:11px;color:#bbb;margin-top:2px">
              <i class="material-icons" style="font-size:12px;vertical-align:middle">place</i>
              <?= htmlspecialchars($d['location']) ?>
            </div>
            <?php endif; ?>
          </div>
          <i class="material-icons" style="color:#ddd;font-size:18px;flex-shrink:0">chevron_right</i>
        </div>
      </div>
    </div></a>
  </div>
  <?php endforeach; ?>
</div>

<div id="no-results" style="display:none;text-align:center;padding:40px;color:#bbb">
  <i class="material-icons" style="font-size:48px;display:block;margin-bottom:8px">search_off</i>
  <span>No departments match your search</span>
</div>
</main>

<script>
document.getElementById('dept-search').addEventListener('input', function() {
  var q = this.value.toLowerCase().trim();
  var items = document.querySelectorAll('.dept-item');
  var visible = 0;
  items.forEach(function(el) {
    var match = !q || el.dataset.name.includes(q);
    el.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  document.getElementById('no-results').style.display = visible ? 'none' : 'block';
});
</script>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
