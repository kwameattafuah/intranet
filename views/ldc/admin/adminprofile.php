<?php 
  // include controller
  include("../../../layout/definition.php");
  // include dashboard
  include("../../../controllers/ldclogin.controller.php");

  $class = new Login;

  $account = $class->index($_SESSION['aj.ldc']['ldcid']);
?>

<main>
  <div class="row">
    <div class="col s12 m6 offset-m3 parent">
      <p class="center-align flow-text blue-text text-darken-4">Account Profile</p>
      <form class="form" data-dest="<?= __url__.'/actions/ldclogin.actions.php' ?>" data-output=".modal-content" form-type="form">
        <div class="input-field">
          <input type="text" id="name" name="name" required="true" value="<?= $account['name'] ?>">
          <label for="name">Name</label>
        </div>    
        <div class="input-field">
          <input type="text" name="position" id="position" required="true" value="<?= $account['position'] ?>">
          <label for="position">Position</label>
        </div>          
        <div class="input-field">
          <span class="green-text text-darken-7">Email</span>
          <input type="email" id="email" name="email" required="true" value="<?= $account['email'] ?>">
        </div>
        <div class="input-field">
          <input type="text" name="phone" id="phone" required="true" value="<?= $account['phone'] ?>">
          <label for="phone">Phone</label>
        </div>        
        <div class="input-field">
          <input type="password" name="passphrase" id="passphrase" required="true">
          <label for="">Password</label>
        </div>
        <div class="input-field">
          <p><a href="" class="spec-ajax" data-extend-view=".pass_form" data-parent=".parent" data-output=".modal-content" data-toggle="modal" return="true">Change Password?</a></p>
        </div>
        <div class="input-field center-align">
          <input type="hidden" name="update" value="set">
          <input type="submit" class="btn green darken-2" value="UPDATE">
        </div>
      </form>
      <div class="pass_form hide">
        <div class="row">
          <div class="col s12">
            <form class="form" data-dest="<?= __url__.'/actions/ldclogin.actions.php' ?>" data-output=".modal-content" form-type="form">
              <div class="input-field">
                <input type="password" id="password" name="password" required="true">
                <label for="password">Current Password</label>
              </div>
              <div class="input-field">
                <input type="password" id="npass" name="npass" required="true">
                <label for="password">New Password</label>
              </div>
              <div class="input-field">
                <input type="password" id="rpass" name="rpass" required="true">
                <label for="password">Retype Password</label>
              </div>
              <div class="input-field center-align">
                <input type="hidden" name="passchange" value="set">
                <input type="submit" value="CHANGE" class="btn green darken-2">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>