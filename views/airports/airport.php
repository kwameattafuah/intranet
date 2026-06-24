<?php
require_once('../../../layout/definition.php');
require_once(__ROOT__ . 'controllers/departments.controller.php');

$uri   = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $uri);
$code  = '';
foreach ($parts as $i => $p) {
    if ($p === 'airports' && isset($parts[$i+1])) {
        $code = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $parts[$i+1]));
        break;
    }
}

$dept_obj = new Departments();
$airport  = $code ? $dept_obj->airport($code) : false;

if (!$airport) {
    header("Location: " . __url__ . "/airports/");
    exit;
}

include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');

$colors = [
  'ACC'=>'#1a3a5c','KMS'=>'#27ae60','TML'=>'#e65100',
  'TKD'=>'#1565c0','NYI'=>'#6a1b9a','HZA'=>'#00838f'
];
$color = $colors[$airport['code']] ?? '#1a3a5c';

// Tamale airport contacts from directory
$tamale_contacts = [
  'TML' => [
    ['unit'=>'Administration','ext'=>'7302'],
    ['unit'=>'Finance','ext'=>'7312'],
    ['unit'=>'ICT','ext'=>'7311'],
    ['unit'=>'RFFS','ext'=>'7375 / 7376'],
    ['unit'=>'AVSEC','ext'=>'7315 / 7337'],
    ['unit'=>'Facilities','ext'=>'7310 / 7323'],
    ['unit'=>'Airside Ops','ext'=>'7364'],
    ['unit'=>'Terminal Ops','ext'=>'7316'],
    ['unit'=>'Commercials','ext'=>'7309'],
  ]
];
?>

<style>
.airport-hero{background:<?= $color ?>;color:#fff;padding:28px 24px;border-radius:8px;margin-bottom:24px;position:relative;overflow:hidden}
.airport-hero::before{content:'';position:absolute;right:-20px;top:-20px;width:120px;height:120px;background:rgba(255,255,255,.08);border-radius:50%}
.info-card{background:#f8f9fa;border-radius:8px;padding:14px 16px;margin-bottom:10px;border-left:3px solid <?= $color ?>}
.contact-row{display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f8f9fa;border-radius:6px;margin-bottom:6px}
</style>

<main>

<nav style="font-size:12px;color:#888;margin-bottom:16px">
  <a href="<?= __url__ ?>/airports/" style="color:#888">Airports</a>
  <i class="material-icons" style="font-size:14px;vertical-align:middle;margin:0 4px">chevron_right</i>
  <span style="color:var(--primary)"><?= htmlspecialchars($airport['name']) ?></span>
</nav>

<div class="airport-hero">
  <div style="position:relative;z-index:1;display:flex;align-items:flex-start;gap:16px">
    <div style="width:60px;height:60px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <span style="font-weight:800;font-size:16px;color:#fff"><?= $airport['code'] ?></span>
    </div>
    <div>
      <h4 style="margin:0 0 4px;font-size:20px;font-weight:700"><?= htmlspecialchars($airport['name']) ?></h4>
      <div style="font-size:13px;opacity:.85;margin-bottom:8px">
        <i class="material-icons" style="font-size:14px;vertical-align:middle">place</i>
        <?= htmlspecialchars($airport['city']) ?>, <?= htmlspecialchars($airport['region']) ?> Region
      </div>
      <?php if ($airport['description']): ?>
      <p style="margin:0;font-size:12px;opacity:.8;line-height:1.6"><?= htmlspecialchars($airport['description']) ?></p>
      <?php endif; ?>
      <div style="margin-top:12px;display:flex;gap:14px;flex-wrap:wrap">
        <span style="font-size:12px;opacity:.9">
          <i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">flight_land</i>
          <?= $airport['runways'] ?> Runway<?= $airport['runways']>1?'s':'' ?>
        </span>
        <?php if ($airport['phone']): ?>
        <span style="font-size:12px;opacity:.9">
          <i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">phone</i>
          <?= htmlspecialchars($airport['phone']) ?>
        </span>
        <?php endif; ?>
        <?php if ($airport['ext']): ?>
        <span style="font-size:12px;opacity:.9">
          <i class="material-icons" style="font-size:14px;vertical-align:middle;margin-right:3px">dialpad</i>
          Ext: <?= htmlspecialchars($airport['ext']) ?>
        </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col s12 m7">
    <!-- Airport departments/units -->
    <?php if (isset($tamale_contacts[$airport['code']])): ?>
    <div style="margin-bottom:24px">
      <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid <?= $color ?>">Department Extensions</div>
      <?php foreach ($tamale_contacts[$airport['code']] as $c): ?>
      <div class="contact-row">
        <div style="width:36px;height:36px;background:<?= $color ?>18;border-radius:8px;display:flex;align-items:center;justify-content:center">
          <i class="material-icons" style="color:<?= $color ?>;font-size:18px">business</i>
        </div>
        <div style="flex:1">
          <div style="font-weight:600;font-size:13px"><?= htmlspecialchars($c['unit']) ?></div>
        </div>
        <div style="display:flex;align-items:center;gap:4px;font-size:12px;color:#555">
          <i class="material-icons" style="font-size:14px">phone</i><?= htmlspecialchars($c['ext']) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;padding:40px;color:#bbb;background:#f8f9fa;border-radius:8px;margin-bottom:20px">
      <i class="material-icons" style="font-size:42px;display:block;margin-bottom:8px">contacts</i>
      <span style="font-size:13px">Contact details for this airport will be available soon</span>
    </div>
    <?php endif; ?>
  </div>

  <div class="col s12 m5">
    <!-- Airport info -->
    <div style="margin-bottom:24px">
      <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin-bottom:12px;padding-bottom:8px;border-bottom:2px solid <?= $color ?>">Airport Information</div>

      <div class="info-card">
        <div style="font-size:11px;color:#888;text-transform:uppercase;font-weight:600;margin-bottom:4px">IATA Code</div>
        <div style="font-weight:700;font-size:18px;color:var(--primary)"><?= $airport['code'] ?></div>
      </div>

      <div class="info-card">
        <div style="font-size:11px;color:#888;text-transform:uppercase;font-weight:600;margin-bottom:4px">Location</div>
        <div style="font-size:13px;font-weight:600"><?= htmlspecialchars($airport['city']) ?></div>
        <div style="font-size:12px;color:#888"><?= htmlspecialchars($airport['region']) ?> Region, Ghana</div>
      </div>

      <div class="info-card">
        <div style="font-size:11px;color:#888;text-transform:uppercase;font-weight:600;margin-bottom:4px">Runways</div>
        <div style="font-size:13px;font-weight:600"><?= $airport['runways'] ?> Runway<?= $airport['runways']>1?'s':'' ?></div>
      </div>

      <?php if ($airport['phone'] || $airport['email']): ?>
      <div class="info-card">
        <div style="font-size:11px;color:#888;text-transform:uppercase;font-weight:600;margin-bottom:6px">Contact</div>
        <?php if ($airport['phone']): ?>
        <div style="font-size:13px;margin-bottom:4px"><i class="material-icons" style="font-size:14px;vertical-align:middle;color:<?= $color ?>">phone</i> <?= htmlspecialchars($airport['phone']) ?></div>
        <?php endif; ?>
        <?php if ($airport['email']): ?>
        <div style="font-size:13px"><i class="material-icons" style="font-size:14px;vertical-align:middle;color:<?= $color ?>">email</i> <?= htmlspecialchars($airport['email']) ?></div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- HQ contacts -->
    <div style="background:var(--primary);color:#fff;border-radius:8px;padding:16px">
      <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;opacity:.8">Regional Airports HQ</div>
      <div style="font-size:12px;opacity:.9;margin-bottom:6px">
        <i class="material-icons" style="font-size:13px;vertical-align:middle">phone</i> Kumasi: Ext 6200
      </div>
      <div style="font-size:12px;opacity:.9;margin-bottom:6px">
        <i class="material-icons" style="font-size:13px;vertical-align:middle">phone</i> Tamale: Ext 6300
      </div>
      <div style="font-size:12px;opacity:.9;margin-bottom:6px">
        <i class="material-icons" style="font-size:13px;vertical-align:middle">phone</i> Sunyani: Ext 6400
      </div>
      <div style="font-size:12px;opacity:.9;margin-bottom:6px">
        <i class="material-icons" style="font-size:13px;vertical-align:middle">phone</i> Wa: Ext 6701
      </div>
      <div style="font-size:12px;opacity:.9">
        <i class="material-icons" style="font-size:13px;vertical-align:middle">phone</i> Ho: Ext 5063
      </div>
    </div>
  </div>
</div>

</main>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
