<?php
require_once('../../layout/definition.php');
require_once(__ROOT__ . 'controllers/services.controller.php');
$svc = new Services();
$stats = $svc->stats();
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<main>

<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">business_center</i>Human Capital &amp; Office Services</h5>
  <span style="font-size:13px;opacity:.8"><?= date('l, d F Y') ?></span>
</div>

<!-- Stats -->
<div class="row">
  <div class="col s6 m3"><div class="stat-card"><div class="stat-icon"><i class="material-icons">hourglass_empty</i></div><div class="stat-value"><?= $stats['pending'] ?></div><div class="stat-label">Pending Requests</div></div></div>
  <div class="col s6 m3"><div class="stat-card" style="border-top-color:var(--success)"><div class="stat-icon"><i class="material-icons" style="color:var(--success)">flight</i></div><div class="stat-value"><?= $stats['travel'] ?></div><div class="stat-label">Travel Requests</div></div></div>
  <div class="col s6 m3"><div class="stat-card" style="border-top-color:var(--primary)"><div class="stat-icon"><i class="material-icons" style="color:var(--primary)">description</i></div><div class="stat-value"><?= $stats['letter'] ?></div><div class="stat-label">Letter Requests</div></div></div>
  <div class="col s6 m3"><div class="stat-card" style="border-top-color:var(--danger)"><div class="stat-icon"><i class="material-icons" style="color:var(--danger)">account_balance</i></div><div class="stat-value"><?= $stats['loan'] ?></div><div class="stat-label">Loan Requests</div></div></div>
</div>

<!-- Service Cards -->
<div class="row">

  <div class="col s12 m6 l4">
    <a href="<?= __url__ ?>/ldcroom/" style="display:block">
    <div class="card cursor" style="border-top:4px solid #1565c0">
      <div class="card-content">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:52px;height:52px;background:#e3f2fd;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:#1565c0;font-size:28px">meeting_room</i>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:var(--primary)">Room &amp; Venue Booking</div>
            <div style="font-size:12px;color:#666;margin-top:3px">Book conference rooms, training rooms and boardrooms</div>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
          <span class="badge-pill badge-primary"><?= $stats['room'] ?> bookings</span>
          <span style="font-size:12px;color:var(--primary);font-weight:600">Request <i class="material-icons tiny" style="vertical-align:middle">arrow_forward</i></span>
        </div>
      </div>
    </div></a>
  </div>

  <div class="col s12 m6 l4">
    <a href="<?= __url__ ?>/services/travel/" style="display:block">
    <div class="card cursor" style="border-top:4px solid var(--success)">
      <div class="card-content">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:52px;height:52px;background:#e8f5e9;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:var(--success);font-size:28px">flight_takeoff</i>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:var(--primary)">Travel Request</div>
            <div style="font-size:12px;color:#666;margin-top:3px">Inter-regional &amp; international travel authorisation</div>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
          <span class="badge-pill badge-success"><?= $stats['travel'] ?> requests</span>
          <span style="font-size:12px;color:var(--primary);font-weight:600">Request <i class="material-icons tiny" style="vertical-align:middle">arrow_forward</i></span>
        </div>
      </div>
    </div></a>
  </div>

  <div class="col s12 m6 l4">
    <a href="<?= __url__ ?>/services/letters/" style="display:block">
    <div class="card cursor" style="border-top:4px solid var(--accent)">
      <div class="card-content">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:52px;height:52px;background:#fff8e1;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:var(--accent);font-size:28px">description</i>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:var(--primary)">HR Letter Request</div>
            <div style="font-size:12px;color:#666;margin-top:3px">Visa introduction &amp; reference letters from HCOS</div>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
          <span class="badge-pill badge-warning"><?= $stats['letter'] ?> requests</span>
          <span style="font-size:12px;color:var(--primary);font-weight:600">Request <i class="material-icons tiny" style="vertical-align:middle">arrow_forward</i></span>
        </div>
      </div>
    </div></a>
  </div>

  <div class="col s12 m6 l4">
    <a href="<?= __url__ ?>/services/loan/" style="display:block">
    <div class="card cursor" style="border-top:4px solid var(--danger)">
      <div class="card-content">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:52px;height:52px;background:#fce4e4;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:var(--danger);font-size:28px">account_balance</i>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:var(--primary)">Loan Request</div>
            <div style="font-size:12px;color:#666;margin-top:3px">Salary advance, car loan, personal &amp; emergency loans</div>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
          <span class="badge-pill badge-danger"><?= $stats['loan'] ?> requests</span>
          <span style="font-size:12px;color:var(--primary);font-weight:600">Request <i class="material-icons tiny" style="vertical-align:middle">arrow_forward</i></span>
        </div>
      </div>
    </div></a>
  </div>

  <div class="col s12 m6 l4">
    <a href="<?= __url__ ?>/services/myrequests/" style="display:block">
    <div class="card cursor" style="border-top:4px solid var(--primary)">
      <div class="card-content">
        <div style="display:flex;align-items:center;gap:14px">
          <div style="width:52px;height:52px;background:#e8edf3;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="material-icons" style="color:var(--primary);font-size:28px">assignment</i>
          </div>
          <div>
            <div style="font-weight:700;font-size:15px;color:var(--primary)">My Requests</div>
            <div style="font-size:12px;color:#666;margin-top:3px">Track all your submitted requests and their status</div>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;justify-content:space-between;align-items:center">
          <span class="badge-pill badge-info">Track requests</span>
          <span style="font-size:12px;color:var(--primary);font-weight:600">View <i class="material-icons tiny" style="vertical-align:middle">arrow_forward</i></span>
        </div>
      </div>
    </div></a>
  </div>

</div>

<!-- Quick Track -->
<div class="row">
  <div class="col s12">
    <div class="booking-panel">
      <p class="panel-title"><i class="material-icons left" style="font-size:18px;vertical-align:middle">search</i>Quick Track a Request</p>
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

</main>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
