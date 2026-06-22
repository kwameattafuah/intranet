<?php
require_once('../../../layout/definition.php');
include(__ROOT__ . 'layout/head.php');
include(__ROOT__ . 'layout/nav.php');
?>
<main>

<div class="page-header">
  <h5><i class="material-icons left" style="line-height:inherit">account_balance</i> Loan Request</h5>
  <a href="<?= __url__ ?>/services/" class="btn btn-sm waves-effect" style="background:rgba(255,255,255,0.15)!important;font-size:12px;height:30px;line-height:30px">
    <i class="material-icons left tiny">arrow_back</i> Back to HCOS
  </a>
</div>

<div class="row">
  <div class="col s12 l8 offset-l2">

    <div class="alert alert-info" style="margin-bottom:16px">
      <i class="material-icons left tiny">info</i>
      Submit your loan request online. If your request is cleared, <strong>HCOS will call you</strong> to come in and collect the relevant forms to complete the process.
    </div>

    <div class="booking-panel">
      <p class="panel-title">
        <i class="material-icons left" style="font-size:18px;vertical-align:middle">account_balance</i>
        Staff Loan / Advance Request
      </p>

      <form class="form"
            data-dest="<?= __url__ ?>/actions/services.actions.php"
            data-output=".svc-result"
            form-type="form"
            data-clear-input="true"
            data-toggle="modal"
            return="true">
        <input type="hidden" name="action" value="loan">

        <!-- Loan Type -->
        <div class="input-field" style="margin-top:0">
          <select class="browser-default" name="loan_type" required>
            <option value="" disabled selected>— Select Loan Type —</option>
            <option value="salary_advance">Salary Advance</option>
            <option value="car_loan">Car Loan</option>
            <option value="personal_loan">Personal Loan</option>
            <option value="emergency_loan">Emergency Loan</option>
          </select>
        </div>

        <!-- Amount -->
        <div class="input-field">
          <i class="material-icons prefix">attach_money</i>
          <input type="number" name="amount" id="amount" min="0" step="0.01">
          <label for="amount">Estimated Amount (GHS) — optional</label>
        </div>

        <!-- Reason -->
        <div class="input-field">
          <i class="material-icons prefix">notes</i>
          <textarea name="reason" id="reason" class="materialize-textarea" required></textarea>
          <label for="reason">Reason for the Loan</label>
        </div>

        <!-- Repayment -->
        <div class="input-field">
          <i class="material-icons prefix">date_range</i>
          <input type="text" name="repayment" id="repayment" placeholder="e.g. 6 months">
          <label for="repayment">Proposed Repayment Period</label>
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
