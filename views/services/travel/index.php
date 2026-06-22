<?php
require_once('../../../layout/definition.php');
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<main>

<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">flight_takeoff</i> Travel Request</h5>
  <a href="<?= __url__ ?>/services/" class="btn btn-sm waves-effect" style="background:rgba(255,255,255,0.15)!important;font-size:12px;height:30px;line-height:30px">
    <i class="material-icons left tiny">arrow_back</i> Back to HCOS
  </a>
</div>

<div class="row">
  <div class="col s12 l8 offset-l2">
    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px;vertical-align:middle">flight</i>
        New Travel Authorisation Request
      </p>
      <p style="font-size:13px;color:#666;margin-top:-12px;margin-bottom:20px">
        Travel arrangements are managed by <strong>Office Services</strong>. Complete this form and they will process your request.
      </p>

      <form class="form"
            data-dest="<?= __url__ ?>/actions/services.actions.php"
            data-output=".svc-result"
            form-type="form"
            data-clear-input="true"
            data-toggle="modal"
            return="true">
        <input type="hidden" name="action" value="travel">

        <!-- Travel Type -->
        <div class="input-field" style="margin-top:0">
          <select class="browser-default" name="travel_type" id="travel_type" required onchange="toggleAirport(this.value)">
            <option value="" disabled selected>— Select Travel Type —</option>
            <option value="inter_regional">Inter-Regional (within Ghana)</option>
            <option value="international">International</option>
          </select>
        </div>

        <!-- Destination -->
        <div class="row" style="margin-bottom:0">
          <div class="input-field col s12 m8">
            <i class="material-icons prefix">place</i>
            <input type="text" name="destination" id="destination" required>
            <label for="destination">Destination (City / Country)</label>
          </div>
          <div class="input-field col s12 m4" id="airport_wrap" style="display:none">
            <select class="browser-default" name="airport_code">
              <option value="">— Airport —</option>
              <option value="ACC">Accra (ACC)</option>
              <option value="KMS">Kumasi (KMS)</option>
              <option value="TML">Tamale (TML)</option>
              <option value="TKD">Takoradi (TKD)</option>
              <option value="NYI">Sunyani (NYI)</option>
              <option value="HZA">Ho (HZA)</option>
            </select>
          </div>
        </div>

        <!-- Purpose -->
        <div class="input-field">
          <i class="material-icons prefix">description</i>
          <textarea name="purpose" id="purpose" class="materialize-textarea" required></textarea>
          <label for="purpose">Purpose / Nature of Trip</label>
        </div>

        <!-- Dates -->
        <div class="row" style="margin-bottom:0">
          <div class="input-field col s12 m6">
            <span class="text-muted" style="font-size:12px;display:block;margin-bottom:4px">Departure Date</span>
            <input type="date" name="departure_date" required>
          </div>
          <div class="input-field col s12 m6">
            <span class="text-muted" style="font-size:12px;display:block;margin-bottom:4px">Return Date</span>
            <input type="date" name="return_date" required>
          </div>
        </div>

        <!-- Travellers -->
        <div class="row" style="margin-bottom:0">
          <div class="input-field col s12 m4">
            <i class="material-icons prefix">group</i>
            <input type="number" name="num_travellers" id="num_travellers" min="1" value="1" required>
            <label for="num_travellers">No. of Travellers</label>
          </div>
          <div class="input-field col s12 m8">
            <i class="material-icons prefix">people</i>
            <input type="text" name="travellers" id="travellers" placeholder="Names of other travellers (if any)">
            <label for="travellers">Other Travellers</label>
          </div>
        </div>

        <!-- Logistical needs -->
        <p style="font-size:13px;font-weight:600;color:var(--primary);margin:8px 0 6px">Logistical Requirements</p>
        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:16px">
          <label style="font-size:13px;cursor:pointer">
            <input type="checkbox" name="accommodation" class="filled-in" value="1">
            <span>Accommodation</span>
          </label>
          <label style="font-size:13px;cursor:pointer">
            <input type="checkbox" name="transport" class="filled-in" value="1">
            <span>Ground Transport</span>
          </label>
          <label style="font-size:13px;cursor:pointer">
            <input type="checkbox" name="per_diem" class="filled-in" value="1">
            <span>Per Diem</span>
          </label>
        </div>

        <!-- Notes -->
        <div class="input-field">
          <i class="material-icons prefix">notes</i>
          <textarea name="extra_notes" id="extra_notes" class="materialize-textarea"></textarea>
          <label for="extra_notes">Additional Notes (optional)</label>
        </div>

        <div class="svc-result"></div>

        <div class="row" style="margin-top:10px">
          <div class="col s6">
            <button type="submit" class="btn btn-primary fullwidth" style="background:var(--primary)!important">
              <i class="material-icons left">send</i> Submit Request
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
</div>

</main>
<script>
function toggleAirport(v) {
  document.getElementById('airport_wrap').style.display = v === 'inter_regional' ? 'block' : 'none';
}
</script>
<?php include(__ROOT__ . 'layout/foot.php'); ?>
