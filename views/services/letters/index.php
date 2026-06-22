<?php
require_once('../../../layout/definition.php');
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<main>

<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">description</i> HR Letter Request</h5>
  <a href="<?= __url__ ?>/services/" class="btn btn-sm waves-effect" style="background:rgba(255,255,255,0.15)!important;font-size:12px;height:30px;line-height:30px">
    <i class="material-icons left tiny">arrow_back</i> Back to HCOS
  </a>
</div>

<div class="row">
  <div class="col s12 l8 offset-l2">
    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px;vertical-align:middle">description</i>
        Request an HR Letter
      </p>
      <p style="font-size:13px;color:#666;margin-top:-12px;margin-bottom:20px">
        HCOS issues <strong>visa introduction letters</strong> and <strong>reference letters</strong>. Allow up to 3 working days for processing.
      </p>

      <form class="form"
            data-dest="<?= __url__ ?>/actions/services.actions.php"
            data-output=".svc-result"
            form-type="form"
            data-clear-input="true"
            data-toggle="modal"
            return="true">
        <input type="hidden" name="action" value="letter">

        <!-- Letter Type -->
        <div class="input-field" style="margin-top:0">
          <select class="browser-default" name="letter_type" id="letter_type" required onchange="toggleLetterFields(this.value)">
            <option value="" disabled selected>— Select Letter Type —</option>
            <option value="visa_intro">Visa Introduction Letter</option>
            <option value="reference">Reference Letter</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- Addressed To -->
        <div class="input-field">
          <i class="material-icons prefix">business</i>
          <input type="text" name="addressed_to" id="addressed_to" required>
          <label for="addressed_to">Addressed To (Embassy / Institution)</label>
        </div>

        <!-- Country — for visa letters -->
        <div class="input-field" id="country_wrap">
          <i class="material-icons prefix">flag</i>
          <input type="text" name="country" id="country">
          <label for="country">Country (for visa letters)</label>
        </div>

        <!-- Purpose -->
        <div class="input-field">
          <i class="material-icons prefix">notes</i>
          <textarea name="purpose" id="purpose" class="materialize-textarea" required></textarea>
          <label for="purpose">Purpose / Reason for the letter</label>
        </div>

        <!-- Urgency -->
        <div class="input-field">
          <select class="browser-default" name="urgency" required>
            <option value="normal" selected>Normal (3 working days)</option>
            <option value="urgent">Urgent (next working day)</option>
          </select>
        </div>

        <!-- Notes -->
        <div class="input-field">
          <i class="material-icons prefix">chat</i>
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
<?php include(__ROOT__ . 'layout/foot.php'); ?>
