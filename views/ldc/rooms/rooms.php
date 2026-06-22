<?php
    $class = new Ldc;
    $rooms = $class->rooms(null);
    $depts = $class->deptfetch();
?>

<!-- Page Header -->
<div class="row" style="margin-bottom:0">
  <div class="col s12">
    <div class="page-header">
      <h5><i class="material-icons left" style="line-height:inherit">meeting_room</i> Room Booking</h5>
      <span style="font-size:13px; opacity:0.8">
        <?= date('l, d F Y') ?>
      </span>
    </div>
  </div>
</div>

<div class="row">

  <!-- ── Left: Booking Form ───────────────────────────────── -->
  <div class="col s12 m12 l5">
    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px; vertical-align:middle">event_available</i>
        New Room Requisition
      </p>

      <form class="form"
            data-dest="<?= __url__.'/actions/ldcbook.actions.php' ?>"
            data-output=".modal-content"
            form-type="form"
            data-clear-input="true"
            data-toggle="modal"
            return="true">

        <div class="input-field">
          <i class="material-icons prefix">description</i>
          <input type="text" id="purpose" name="purpose" required>
          <label for="purpose">Purpose / Event Title</label>
        </div>

        <div class="input-field">
          <i class="material-icons prefix">business</i>
          <input type="text" name="booked_by" id="booked_by" required>
          <label for="booked_by">Booking For (Section / Unit)</label>
        </div>

        <div class="input-field" style="margin-top:20px">
          <select class="browser-default" name="dept" required>
            <option value="" disabled selected>— Select Department —</option>
            <?php if ($depts !== false): foreach ($depts as $dept): ?>
              <option value="<?= $dept['dept_id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
            <?php endforeach; endif; ?>
          </select>
        </div>

        <div class="row" style="margin-bottom:0">
          <div class="input-field col s12 m8" style="margin-top:20px">
            <select class="browser-default" name="room" id="room_select" required>
              <option value="" disabled selected>— Choose a Room —</option>
              <?php if ($rooms !== false): foreach ($rooms as $room): ?>
                <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['room_name']) ?></option>
              <?php endforeach; endif; ?>
            </select>
          </div>
          <div class="input-field col s12 m4">
            <i class="material-icons prefix">people</i>
            <input type="number" name="occupancy" id="occupancy" min="1" required>
            <label for="occupancy">Occupancy</label>
          </div>
        </div>

        <div class="row" style="margin-bottom:0">
          <div class="input-field col s12 m6">
            <span class="text-muted" style="font-size:12px; display:block; margin-bottom:4px">Start Date &amp; Time</span>
            <input type="datetime-local" id="start_date" name="start_date" required class="validate">
          </div>
          <div class="input-field col s12 m6">
            <span class="text-muted" style="font-size:12px; display:block; margin-bottom:4px">End Date &amp; Time</span>
            <input type="datetime-local" id="end_date" name="end_date" required class="validate">
          </div>
        </div>

        <div class="row" style="margin-top:10px">
          <div class="col s6">
            <input type="hidden" name="booksave" value="set">
            <button type="submit" class="btn btn-primary fullwidth" style="background:var(--primary)!important">
              <i class="material-icons left">check_circle</i> Submit Request
            </button>
          </div>
          <div class="col s6">
            <button type="reset" class="btn btn-danger fullwidth" style="background:var(--danger)!important">
              <i class="material-icons left">clear</i> Reset
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- ── Right: Room List & Status ────────────────────────── -->
  <div class="col s12 m12 l7">

    <!-- Track Requisition -->
    <div class="booking-panel" style="margin-bottom:16px; padding:18px 24px">
      <p class="panel-title" style="margin-bottom:14px">
        <i class="material-icons left" style="font-size:18px; vertical-align:middle">search</i>
        Track Requisition
      </p>
      <form class="form"
            data-dest="<?= __url__.'/actions/ldcbook.actions.php' ?>"
            data-output=".requisitionPlace"
            form-type="form">
        <div class="row" style="margin-bottom:0; align-items:center; display:flex; gap:8px; flex-wrap:wrap">
          <div class="input-field" style="flex:1; min-width:200px; margin:0">
            <i class="material-icons prefix">confirmation_number</i>
            <input type="text" name="itemcode" id="reqcode" placeholder="Enter requisition code">
            <label for="reqcode">Requisition Code</label>
          </div>
          <div style="padding-top:6px">
            <input type="hidden" name="requisitionSearch" value="set">
            <button type="submit" class="btn" style="background:var(--primary)!important">
              <i class="material-icons left">search</i> Search
            </button>
          </div>
        </div>
      </form>
      <div class="requisitionPlace" style="margin-top:10px"></div>
    </div>

    <!-- Available Rooms -->
    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px; vertical-align:middle">view_list</i>
        Available Rooms
      </p>

      <?php if ($rooms !== false && count($rooms) > 0): ?>
        <div class="row">
          <?php foreach ($rooms as $room): ?>
            <div class="col s12 m6">
              <div class="room-card">
                <div class="room-card-header">
                  <h6><?= htmlspecialchars($room['room_name']) ?></h6>
                  <span>
                    <?php
                      $type = strtolower($room['type'] ?? '');
                      if (strpos($type, 'learn') !== false) echo 'Learning Room';
                      elseif (strpos($type, 'board') !== false) echo 'Boardroom';
                      else echo 'Conference Room';
                    ?>
                  </span>
                </div>
                <div class="room-card-body">
                  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px">
                    <?php
                      $rtype = strtolower($room['type'] ?? 'conference');
                      $tagClass = strpos($rtype,'learn')!==false ? 'learning' : (strpos($rtype,'board')!==false ? 'boardroom' : 'conference');
                    ?>
                    <span class="room-tag <?= $tagClass ?>">
                      <?= ucfirst($tagClass) ?>
                    </span>
                    <span>
                      <span class="room-status-dot available"></span>
                      <span style="font-size:12px; color:#555">Available</span>
                    </span>
                  </div>
                  <?php if (!empty($room['capacity'])): ?>
                  <p style="font-size:12px; color:#666; margin:4px 0">
                    <i class="material-icons tiny" style="vertical-align:middle">people</i>
                    Capacity: <strong><?= $room['capacity'] ?></strong>
                  </p>
                  <?php endif; ?>
                  <?php if (!empty($room['location'])): ?>
                  <p style="font-size:12px; color:#666; margin:4px 0">
                    <i class="material-icons tiny" style="vertical-align:middle">place</i>
                    <?= htmlspecialchars($room['location']) ?>
                  </p>
                  <?php endif; ?>
                  <button class="btn btn-sm waves-effect fullwidth"
                          style="margin-top:10px; background:var(--primary)!important; font-size:12px; height:32px; line-height:32px"
                          onclick="document.getElementById('room_select').value='<?= $room['id'] ?>'; document.getElementById('room_select').dispatchEvent(new Event('change'));">
                    <i class="material-icons left tiny">add</i> Book this Room
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-info">
          <i class="material-icons left tiny">info</i>
          No rooms are currently configured. Contact the L&amp;D administrator.
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>
