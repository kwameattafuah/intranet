<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');
$dept_obj = new Departments();
$airports = $dept_obj->airports();
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');

$airport_colors = [
  'ACC'=>'#1a3a5c','KMS'=>'#27ae60','TML'=>'#e65100',
  'TKD'=>'#1565c0','NYI'=>'#6a1b9a','HZA'=>'#00838f'
];
$airport_regions = [
  'ACC'=>'Greater Accra','KMS'=>'Ashanti','TML'=>'Northern',
  'TKD'=>'Western','NYI'=>'Bono','HZA'=>'Volta'
];
?>
<main>
<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">flight</i>GACL Airports</h5>
  <span style="font-size:13px;opacity:.8">Ghana Airports Company Ltd operates <?= count($airports) ?> airports across Ghana</span>
</div>

<div class="row">
  <?php foreach ($airports as $a):
    $color = $airport_colors[$a['code']] ?? '#1a3a5c';
  ?>
  <div class="col s12 m6 l4" style="margin-bottom:4px">
    <a href="<?= __url__ ?>/airports/<?= strtolower($a['code']) ?>/" style="display:block">
    <div class="card cursor" style="border-top:5px solid <?= $color ?>;margin-bottom:16px;transition:.15s" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
      <div class="card-content" style="padding:20px">
        <div style="display:flex;align-items:flex-start;gap:14px">
          <div style="width:52px;height:52px;background:<?= $color ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <span style="color:#fff;font-weight:800;font-size:13px"><?= $a['code'] ?></span>
          </div>
          <div style="flex:1">
            <div style="font-weight:700;font-size:14px;color:var(--primary)"><?= htmlspecialchars($a['name']) ?></div>
            <div style="font-size:12px;color:#888;margin-top:3px">
              <i class="material-icons" style="font-size:13px;vertical-align:middle">place</i>
              <?= htmlspecialchars($a['city']) ?>, <?= htmlspecialchars($a['region']) ?>
            </div>
            <div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap">
              <span style="font-size:11px;background:<?= $color ?>18;color:<?= $color ?>;padding:2px 8px;border-radius:10px">
                <?= $a['runways'] ?> Runway<?= $a['runways']>1?'s':'' ?>
              </span>
              <?php if ($a['phone']): ?>
              <span style="font-size:11px;background:#f5f5f5;color:#666;padding:2px 8px;border-radius:10px">
                <i class="material-icons" style="font-size:11px;vertical-align:middle">phone</i> <?= htmlspecialchars($a['phone']) ?>
              </span>
              <?php endif; ?>
            </div>
          </div>
          <i class="material-icons" style="color:#ddd;font-size:18px">chevron_right</i>
        </div>
        <?php if ($a['description']): ?>
        <p style="font-size:12px;color:#888;margin:12px 0 0;line-height:1.5"><?= htmlspecialchars(substr($a['description'], 0, 100)) ?>...</p>
        <?php endif; ?>
      </div>
    </div></a>
  </div>
  <?php endforeach; ?>
</div>

<div class="card" style="background:var(--primary);color:#fff;margin-top:10px">
  <div class="card-content" style="padding:20px">
    <div style="display:flex;align-items:center;gap:14px">
      <i class="material-icons" style="font-size:36px;opacity:.7">info</i>
      <div>
        <div style="font-weight:700;font-size:14px;margin-bottom:4px">GACL Headquarters</div>
        <div style="font-size:12px;opacity:.85">KA Private Mail Bag 36, Kotoka International Airport, Accra, Ghana</div>
        <div style="font-size:12px;opacity:.85;margin-top:4px">
          Tel: 030 2550612 &nbsp;&bull;&nbsp; Email: info@gacl.com.gh &nbsp;&bull;&nbsp; Website: www.gacl.com.gh
        </div>
        <div style="font-size:11px;opacity:.7;margin-top:4px">Digital Address: GL – 125 – 6946</div>
      </div>
    </div>
  </div>
</div>
</main>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
