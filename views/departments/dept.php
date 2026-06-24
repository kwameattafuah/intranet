<?php
require_once('../../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');

// get slug from URL: /departments/{slug}/
$slug = '';
$uri = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $uri);
// find 'departments' segment and take next
foreach ($parts as $i => $p) {
    if ($p === 'departments' && isset($parts[$i+1])) {
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($parts[$i+1]));
        break;
    }
}

$dept_obj = new Departments();
$dept = $slug ? $dept_obj->get($slug) : false;

if (!$dept) {
    header("Location: " . __url__ . "/departments/");
    exit;
}

$contacts      = $dept_obj->contacts($dept['id']);
$files         = $dept_obj->files($dept['id']);
$forms         = $dept_obj->forms($dept['id']);
$announcements = $dept_obj->announcements($dept['id']);
$sections      = $dept_obj->sections($dept['id']);
$policies      = $dept_obj->policies($dept['id']);

// group files by category
$files_grouped = [];
foreach ($files as $f) {
    $files_grouped[$f['category']][] = $f;
}

// group forms by category
$forms_grouped = [];
foreach ($forms as $f) {
    $forms_grouped[$f['category']][] = $f;
}

include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');

$priority_colors = ['normal'=>'blue','important'=>'orange','urgent'=>'red'];
$priority_icons  = ['normal'=>'info','important'=>'warning','urgent'=>'error'];
?>

<style>
.dept-hero{background:<?= htmlspecialchars($dept['color']) ?>;color:#fff;padding:28px 24px;border-radius:8px;margin-bottom:24px;position:relative;overflow:hidden}
.dept-hero::before{content:'';position:absolute;right:-20px;top:-20px;width:120px;height:120px;background:rgba(255,255,255,.08);border-radius:50%}
.dept-hero::after{content:'';position:absolute;right:40px;top:30px;width:70px;height:70px;background:rgba(255,255,255,.06);border-radius:50%}
.dept-hero h4{margin:0 0 6px;font-size:22px;font-weight:700}
.dept-hero p{margin:0;opacity:.85;font-size:13px}
.dept-section{margin-bottom:28px}
.dept-section-title{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid <?= htmlspecialchars($dept['color']) ?>}
.contact-card{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:6px;background:#f8f9fa;margin-bottom:8px;border-left:3px solid <?= htmlspecialchars($dept['color']) ?>}
.contact-card.is-head{background:<?= htmlspecialchars($dept['color']) ?>12;border-left-color:<?= htmlspecialchars($dept['color']) ?>}
.contact-avatar{width:38px;height:38px;border-radius:50%;background:<?= htmlspecialchars($dept['color']) ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.contact-avatar i{color:#fff;font-size:18px}
.contact-name{font-weight:600;font-size:13px;color:#333}
.contact-title{font-size:11px;color:#888;margin-top:1px}
.contact-ext{margin-left:auto;display:flex;align-items:center;gap:4px;font-size:12px;color:#555;white-space:nowrap}
.file-row{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:6px;background:#f8f9fa;margin-bottom:6px;transition:.15s}
.file-row:hover{background:#f0f0f0}
.file-icon{width:34px;height:34px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;font-weight:700;color:#fff}
.file-icon.pdf{background:#c0392b}.file-icon.doc,.file-icon.docx{background:#2980b9}.file-icon.xls,.file-icon.xlsx{background:#27ae60}.file-icon.ppt,.file-icon.pptx{background:#e67e22}.file-icon.zip{background:#8e44ad}.file-icon.other{background:#7f8c8d}
.file-name{font-size:13px;font-weight:600;color:#333;flex:1}
.file-meta{font-size:11px;color:#999;margin-top:2px}
.file-dl{margin-left:auto}
.section-chip{display:inline-block;padding:4px 12px;border-radius:20px;background:<?= htmlspecialchars($dept['color']) ?>18;color:<?= htmlspecialchars($dept['color']) ?>;font-size:11px;font-weight:600;margin:3px 3px 3px 0;border:1px solid <?= htmlspecialchars($dept['color']) ?>30}
.announce-card{border-left:4px solid;padding:12px 16px;border-radius:0 6px 6px 0;margin-bottom:10px;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.announce-card.normal{border-color:#1565c0}.announce-card.important{border-color:#f0a500}.announce-card.urgent{border-color:#c0392b}
.tab-content{display:none}.tab-content.active{display:block}
.dept-tabs{border-bottom:2px solid #eee;margin-bottom:20px;display:flex;gap:0;overflow-x:auto}
.dept-tab{padding:10px 18px;cursor:pointer;font-size:13px;font-weight:600;color:#888;border-bottom:3px solid transparent;margin-bottom:-2px;white-space:nowrap;transition:.15s}
.dept-tab.active{color:<?= htmlspecialchars($dept['color']) ?>;border-bottom-color:<?= htmlspecialchars($dept['color']) ?>}
.dept-tab:hover{color:<?= htmlspecialchars($dept['color']) ?>}
.empty-state{text-align:center;padding:40px 20px;color:#bbb}
.empty-state i{font-size:48px;display:block;margin-bottom:8px}
.empty-state span{font-size:13px}
</style>

<main>

<!-- Breadcrumb -->
<nav style="font-size:12px;color:#888;margin-bottom:16px">
  <a href="<?= __url__ ?>/departments/" style="color:#888">Departments</a>
  <i class="material-icons" style="font-size:14px;vertical-align:middle;margin:0 4px">chevron_right</i>
  <span style="color:var(--primary)"><?= htmlspecialchars($dept['short_name'] ?: $dept['name']) ?></span>
</nav>

<!-- Department Hero -->
<div class="dept-hero">
  <div style="display:flex;align-items:flex-start;gap:16px;position:relative;z-index:1">
    <div style="width:54px;height:54px;background:rgba(255,255,255,.18);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <i class="material-icons" style="font-size:28px;color:#fff"><?= htmlspecialchars($dept['icon']) ?></i>
    </div>
    <div style="flex:1">
      <h4><?= htmlspecialchars($dept['name']) ?></h4>
      <?php if ($dept['description']): ?>
      <p><?= htmlspecialchars($dept['description']) ?></p>
      <?php endif; ?>
      <div style="margin-top:10px;display:flex;gap:16px;flex-wrap:wrap">
        <?php if ($dept['ext']): ?>
        <span style="font-size:12px;opacity:.9"><i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">phone</i>Ext: <?= htmlspecialchars($dept['ext']) ?></span>
        <?php endif; ?>
        <?php if ($dept['location']): ?>
        <span style="font-size:12px;opacity:.9"><i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">place</i><?= htmlspecialchars($dept['location']) ?></span>
        <?php endif; ?>
        <?php if ($dept['email']): ?>
        <span style="font-size:12px;opacity:.9"><i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">email</i><?= htmlspecialchars($dept['email']) ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Announcements banner (if any urgent/important) -->
<?php foreach ($announcements as $a): if ($a['priority'] !== 'normal'): ?>
<div class="announce-card <?= $a['priority'] ?>" style="margin-bottom:10px">
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
    <i class="material-icons" style="font-size:16px;color:<?= $a['priority']==='urgent'?'#c0392b':'#f0a500' ?>"><?= $priority_icons[$a['priority']] ?></i>
    <strong style="font-size:13px"><?= htmlspecialchars($a['title']) ?></strong>
    <span style="margin-left:auto;font-size:11px;color:#bbb"><?= date('d M Y', strtotime($a['created_at'])) ?></span>
  </div>
  <p style="margin:0;font-size:12px;color:#555"><?= nl2br(htmlspecialchars($a['body'])) ?></p>
</div>
<?php endif; endforeach; ?>

<!-- Tab Navigation -->
<div class="dept-tabs">
  <div class="dept-tab active" onclick="showTab('overview')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">info</i>Overview
  </div>
  <div class="dept-tab" onclick="showTab('contacts')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">contacts</i>Directory
    <?php if (count($contacts)): ?><span class="new badge blue" data-badge-caption=""><?= count($contacts) ?></span><?php endif; ?>
  </div>
  <div class="dept-tab" onclick="showTab('files')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">folder</i>Files
    <?php if (count($files)): ?><span class="new badge" data-badge-caption=""><?= count($files) ?></span><?php endif; ?>
  </div>
  <div class="dept-tab" onclick="showTab('forms')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">assignment</i>Forms
    <?php if (count($forms)): ?><span class="new badge green" data-badge-caption=""><?= count($forms) ?></span><?php endif; ?>
  </div>
  <?php if (count($policies)): ?>
  <div class="dept-tab" onclick="showTab('policies')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">gavel</i>Policies
  </div>
  <?php endif; ?>
  <?php if (count($announcements)): ?>
  <div class="dept-tab" onclick="showTab('announcements')">
    <i class="material-icons" style="font-size:15px;vertical-align:middle;margin-right:4px">campaign</i>Announcements
  </div>
  <?php endif; ?>
</div>

<!-- Overview Tab -->
<div id="tab-overview" class="tab-content active">
  <div class="row">
    <div class="col s12 m8">
      <?php if (count($sections)): ?>
      <div class="dept-section">
        <div class="dept-section-title">Sections & Units</div>
        <div>
          <?php foreach ($sections as $s): ?>
          <span class="section-chip"><?= htmlspecialchars($s['name']) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (count($announcements)): ?>
      <div class="dept-section">
        <div class="dept-section-title">Latest Announcements</div>
        <?php foreach (array_slice($announcements, 0, 3) as $a): ?>
        <div class="announce-card <?= $a['priority'] ?>">
          <div style="display:flex;align-items:center;gap:6px;margin-bottom:4px">
            <strong style="font-size:13px"><?= htmlspecialchars($a['title']) ?></strong>
            <span style="margin-left:auto;font-size:11px;color:#bbb"><?= date('d M Y', strtotime($a['created_at'])) ?></span>
          </div>
          <p style="margin:0;font-size:12px;color:#555"><?= nl2br(htmlspecialchars($a['body'])) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="col s12 m4">
      <!-- Quick contacts (top 4) -->
      <?php if (count($contacts)): ?>
      <div class="dept-section">
        <div class="dept-section-title">Key Contacts</div>
        <?php foreach (array_slice($contacts, 0, 4) as $c): ?>
        <div class="contact-card <?= $c['is_head'] ? 'is-head' : '' ?>">
          <div class="contact-avatar"><i class="material-icons">person</i></div>
          <div style="flex:1;min-width:0">
            <div class="contact-name"><?= htmlspecialchars($c['name']) ?></div>
            <div class="contact-title"><?= htmlspecialchars($c['title'] ?? '') ?></div>
          </div>
          <?php if ($c['ext']): ?>
          <div class="contact-ext"><i class="material-icons" style="font-size:14px">phone</i><?= htmlspecialchars($c['ext']) ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php if (count($contacts) > 4): ?>
        <a onclick="showTab('contacts')" style="font-size:12px;color:<?= htmlspecialchars($dept['color']) ?>;cursor:pointer">View all <?= count($contacts) ?> contacts &rarr;</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <!-- Quick file count -->
      <?php if (count($files) || count($forms)): ?>
      <div class="dept-section">
        <div class="dept-section-title">Available Resources</div>
        <div style="display:flex;gap:10px">
          <?php if (count($files)): ?>
          <div onclick="showTab('files')" style="flex:1;text-align:center;padding:14px 8px;background:#f8f9fa;border-radius:8px;cursor:pointer">
            <i class="material-icons" style="color:<?= htmlspecialchars($dept['color']) ?>;font-size:24px;display:block">folder</i>
            <div style="font-size:18px;font-weight:700;color:var(--primary)"><?= count($files) ?></div>
            <div style="font-size:11px;color:#888">Files</div>
          </div>
          <?php endif; ?>
          <?php if (count($forms)): ?>
          <div onclick="showTab('forms')" style="flex:1;text-align:center;padding:14px 8px;background:#f8f9fa;border-radius:8px;cursor:pointer">
            <i class="material-icons" style="color:#27ae60;font-size:24px;display:block">assignment</i>
            <div style="font-size:18px;font-weight:700;color:var(--primary)"><?= count($forms) ?></div>
            <div style="font-size:11px;color:#888">Forms</div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Contacts Tab -->
<div id="tab-contacts" class="tab-content">
  <?php if (count($contacts)): ?>
  <div class="row">
    <?php foreach ($contacts as $c): ?>
    <div class="col s12 m6">
      <div class="contact-card <?= $c['is_head'] ? 'is-head' : '' ?>" style="margin-bottom:8px">
        <div class="contact-avatar"><i class="material-icons"><?= $c['is_head'] ? 'stars' : 'person' ?></i></div>
        <div style="flex:1;min-width:0">
          <div class="contact-name"><?= htmlspecialchars($c['name']) ?></div>
          <div class="contact-title"><?= htmlspecialchars($c['title'] ?? '') ?></div>
          <?php if ($c['email']): ?>
          <div style="font-size:11px;color:#999;margin-top:2px"><?= htmlspecialchars($c['email']) ?></div>
          <?php endif; ?>
        </div>
        <div style="text-align:right;flex-shrink:0">
          <?php if ($c['ext']): ?>
          <div class="contact-ext"><i class="material-icons" style="font-size:14px">phone</i><?= htmlspecialchars($c['ext']) ?></div>
          <?php endif; ?>
          <?php if ($c['phone']): ?>
          <div style="font-size:11px;color:#888;margin-top:3px"><?= htmlspecialchars($c['phone']) ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state"><i class="material-icons">contacts</i><span>No contacts listed yet</span></div>
  <?php endif; ?>
</div>

<!-- Files Tab -->
<div id="tab-files" class="tab-content">
  <?php if (count($files_grouped)): ?>
  <?php foreach ($files_grouped as $cat => $cat_files): ?>
  <div class="dept-section">
    <div class="dept-section-title"><?= htmlspecialchars($cat) ?></div>
    <?php foreach ($cat_files as $f):
      $ext = strtolower(pathinfo($f['filename'], PATHINFO_EXTENSION));
      $icon_class = in_array($ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx','zip']) ? $ext : 'other';
    ?>
    <div class="file-row">
      <div class="file-icon <?= $icon_class ?>"><?= strtoupper($ext ?: '?') ?></div>
      <div style="flex:1;min-width:0">
        <div class="file-name"><?= htmlspecialchars($f['title']) ?></div>
        <div class="file-meta">
          <?php if ($f['file_size']): ?><?= htmlspecialchars($f['file_size']) ?> &bull; <?php endif; ?>
          <?= date('d M Y', strtotime($f['created_at'])) ?>
          <?php if ($f['downloads']): ?> &bull; <?= $f['downloads'] ?> downloads<?php endif; ?>
        </div>
      </div>
      <a href="<?= __url__ ?>/actions/departments.actions.php?action=download&table=dept_files&id=<?= $f['id'] ?>"
         class="btn-flat waves-effect file-dl" style="color:<?= htmlspecialchars($dept['color']) ?>;font-size:12px">
        <i class="material-icons" style="font-size:18px">download</i>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="empty-state"><i class="material-icons">folder_open</i><span>No files uploaded for this department yet</span></div>
  <?php endif; ?>
</div>

<!-- Forms Tab -->
<div id="tab-forms" class="tab-content">
  <?php if (count($forms_grouped)): ?>
  <?php foreach ($forms_grouped as $cat => $cat_forms): ?>
  <div class="dept-section">
    <div class="dept-section-title"><?= htmlspecialchars($cat) ?></div>
    <?php foreach ($cat_forms as $f):
      $ext = strtolower(pathinfo($f['filename'], PATHINFO_EXTENSION));
      $icon_class = in_array($ext, ['pdf','doc','docx','xls','xlsx']) ? $ext : 'other';
    ?>
    <div class="file-row">
      <div class="file-icon <?= $icon_class ?>"><?= strtoupper($ext ?: '?') ?></div>
      <div style="flex:1;min-width:0">
        <div class="file-name"><?= htmlspecialchars($f['title']) ?></div>
        <div class="file-meta">
          Version <?= htmlspecialchars($f['version']) ?>
          <?php if ($f['description']): ?> &bull; <?= htmlspecialchars($f['description']) ?><?php endif; ?>
          <?php if ($f['downloads']): ?> &bull; <?= $f['downloads'] ?> downloads<?php endif; ?>
        </div>
      </div>
      <?php if (!$f['dept_id']): ?>
      <span style="font-size:10px;background:#e8f5e9;color:#27ae60;padding:2px 8px;border-radius:10px;margin-right:8px">All Depts</span>
      <?php endif; ?>
      <a href="<?= __url__ ?>/actions/departments.actions.php?action=download&table=dept_forms&id=<?= $f['id'] ?>"
         class="btn-flat waves-effect file-dl" style="color:#27ae60;font-size:12px">
        <i class="material-icons" style="font-size:18px">download</i>
      </a>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="empty-state"><i class="material-icons">assignment</i><span>No forms available for this department yet</span></div>
  <?php endif; ?>
</div>

<!-- Policies Tab -->
<?php if (count($policies)): ?>
<div id="tab-policies" class="tab-content">
  <?php foreach ($policies as $p): ?>
  <div class="file-row">
    <div class="file-icon pdf">PDF</div>
    <div style="flex:1;min-width:0">
      <div class="file-name"><?= htmlspecialchars($p['title']) ?></div>
      <div class="file-meta">
        <?= htmlspecialchars($p['category']) ?> &bull; Version <?= htmlspecialchars($p['version']) ?>
        <?php if ($p['effective_date']): ?> &bull; Effective <?= date('d M Y', strtotime($p['effective_date'])) ?><?php endif; ?>
      </div>
    </div>
    <a href="<?= __url__ ?>/actions/departments.actions.php?action=download&table=gacl_policies&id=<?= $p['id'] ?>"
       class="btn-flat waves-effect" style="color:<?= htmlspecialchars($dept['color']) ?>">
      <i class="material-icons" style="font-size:18px">download</i>
    </a>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Announcements Tab -->
<?php if (count($announcements)): ?>
<div id="tab-announcements" class="tab-content">
  <?php foreach ($announcements as $a): ?>
  <div class="announce-card <?= $a['priority'] ?>" style="margin-bottom:12px">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
      <i class="material-icons" style="font-size:16px;color:<?= $a['priority']==='urgent'?'#c0392b':($a['priority']==='important'?'#f0a500':'#1565c0') ?>"><?= $priority_icons[$a['priority']] ?></i>
      <strong style="font-size:14px"><?= htmlspecialchars($a['title']) ?></strong>
      <span style="margin-left:auto;font-size:11px;color:#bbb"><?= date('d M Y', strtotime($a['created_at'])) ?></span>
    </div>
    <p style="margin:0;font-size:13px;color:#444;line-height:1.6"><?= nl2br(htmlspecialchars($a['body'])) ?></p>
    <?php if ($a['posted_by']): ?>
    <div style="font-size:11px;color:#aaa;margin-top:6px">Posted by <?= htmlspecialchars($a['posted_by']) ?></div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

</main>

<script>
function showTab(name) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.dept-tab').forEach(el => el.classList.remove('active'));
  var t = document.getElementById('tab-' + name);
  if (t) t.classList.add('active');
  var tabs = document.querySelectorAll('.dept-tab');
  tabs.forEach(function(tab) {
    if (tab.getAttribute('onclick') === "showTab('" + name + "')") tab.classList.add('active');
  });
}
</script>

<?php include(__ROOT__ . 'layout/foot.php'); ?>
