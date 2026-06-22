<?php
require_once('../../../layout/definition.php');
require_once(__ROOT__ . 'controllers/services.controller.php');
$svc = new Services();
$requests = $svc->myRequests();
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<main>

<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">assignment</i> My Requests</h5>
  <a href="<?= __url__ ?>/services/" class="btn btn-sm waves-effect" style="background:rgba(255,255,255,0.15)!important;font-size:12px;height:30px;line-height:30px">
    <i class="material-icons left tiny">arrow_back</i> Back to HCOS
  </a>
</div>

<!-- Quick Track -->
<div class="row">
  <div class="col s12">
    <div class="booking-panel" style="padding:18px 24px;margin-bottom:16px">
      <p class="panel-title" style="margin-bottom:12px">
        <i class="material-icons left" style="font-size:18px;vertical-align:middle">search</i>Track by Code
      </p>
      <form class="form" data-dest="<?= __url__ ?>/actions/services.actions.php" data-output=".track-result" form-type="form">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
          <div class="input-field" style="flex:1;min-width:250px;margin:0">
            <i class="material-icons prefix">confirmation_number</i>
            <input type="text" name="code" id="tcode" placeholder=" ">
            <label for="tcode">Enter Request Code (e.g. TRAVEL-2026-0041)</label>
          </div>
          <div style="padding-top:6px">
            <input type="hidden" name="action" value="track">
            <button type="submit" class="btn" style="background:var(--primary)!important">
              <i class="material-icons left">search</i>Track
            </button>
          </div>
        </div>
      </form>
      <div class="track-result" style="margin-top:12px"></div>
    </div>
  </div>
</div>

<!-- My Submissions -->
<div class="row">
  <div class="col s12">
    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px;vertical-align:middle">history</i>
        My Submitted Requests
      </p>

      <?php
      $badges = ['pending'=>'badge-warning','approved'=>'badge-success','declined'=>'badge-danger','completed'=>'badge-primary'];
      $icons  = ['travel'=>'flight','letter'=>'description','loan'=>'account_balance','room'=>'meeting_room'];
      $labels = ['travel'=>'Travel','letter'=>'HR Letter','loan'=>'Loan','room'=>'Room Booking'];
      ?>

      <?php if (!$requests): ?>
        <div class="alert alert-info">
          <i class="material-icons left tiny">info</i>
          You have not submitted any requests yet.
          <a href="<?= __url__ ?>/services/" style="font-weight:600"> Go to HCOS portal →</a>
        </div>
      <?php else: ?>
        <table class="striped responsive-table" style="font-size:13px">
          <thead>
            <tr>
              <th>Reference Code</th>
              <th>Type</th>
              <th>Date Submitted</th>
              <th>Status</th>
              <th>HCOS Note</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($requests as $req): ?>
            <?php $b = $badges[$req['status']] ?? 'badge-info'; ?>
            <tr>
              <td><strong><?= htmlspecialchars($req['request_code']) ?></strong></td>
              <td>
                <i class="material-icons tiny" style="vertical-align:middle"><?= $icons[$req['type']] ?? 'assignment' ?></i>
                <?= $labels[$req['type']] ?? ucfirst($req['type']) ?>
              </td>
              <td><?= date('d M Y', strtotime($req['submitted_at'])) ?></td>
              <td><span class="badge-pill <?= $b ?>"><?= ucfirst($req['status']) ?></span></td>
              <td style="font-size:12px;color:#666"><?= $req['admin_notes'] ? htmlspecialchars($req['admin_notes']) : '—' ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

</main>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
