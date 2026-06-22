<?php 
  if(!isset($_SESSION['aj.ldc']['ldcuser'])){
    header("Location: ".__url__.'/ldc/');
  }
	// initialise controller class
	$class = new Ldc;

  	list($courses,$null) = $class->course_reg();
?>
<div class="container">
	<div class="card z-depth-3 white">
	  <nav class="blue darken-4">
	    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
	      <p>
	      	<span style="font-size: 140%" class="col s6 m6"><a href="#"><span class="hide-on-med-and-up">Gacl LDC</span> <span class="hide-on-small-only">Learning & Development Centre</span></a></span>
	      	<span class="right col s6 m6">welcome, <a href="#!" data-activates='dropdown1' class="waves-effect dropdown-button user-email"><?php echo $_SESSION['aj.ldc']['ldcuser'] ?> <i class="material-icons right">arrow_drop_down</i></a>
        <!-- Dropdown Structure -->
        <ul id='dropdown1' class='dropdown-content'>
        	<li><a href="" class="spec-ajax" data-query="participant_profile" data-value="<?= $_SESSION['aj.ldc']['ldcid'] ?>" data-output=".modal-content" data-toggle="modal" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>">My Profile</a></li>          	
			<li class="divider"></li>
      <li><a href="" class="spec-ajax" data-extend-view=".pass_form" data-parent=".nav-wrapper" data-output=".modal-content" data-toggle="modal" return="true">Change Password</a></li>            
      <li class="divider"></li>
            <li class="hide <?= ($_SESSION['aj.ldc']['ldcstatus']===1)? '': 'hide'; ?>"><a href="<?= __url__.'/ldc/evaluations/' ?>" class="waves-effect" target="_blank">Course Evaluation</a></li>

            <li><a href="#" class="waves-effect spec-ajax" data-query="logout" data-dest="<?php echo __url__.'/actions/ldclogin.actions.php' ?>" data-output=".modal-content">Logout</a></li>
        </ul></span>
	      </p>
        <div class="pass_form hide">
          <div class="row">
            <div class="col s12">
              <form class="form" data-dest="<?= __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" form-type="form">
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
                  <input type="hidden" name="pass" value="set">
                  <input type="submit" value="CHANGE" class="btn green darken-2">
                </div>
              </form>
            </div>
          </div>
        </div>
	    </div>
	 </nav>
		<div class="row card-content">
			<div class="col s12 m3" id="displaynav" style="border-right: 3px solid #005">
				<ul>
					<li><a href="" class="cursor spec-ajax black-text" data-dest="<?= __url__.'/views/ldc/user/home.php' ?>" data-output="#display"><h5><i class="material-icons" style="padding-right: 14px">content_paste</i>Training History</h5></a></li>
					<li class="divider"></li>
					<li><a href="" class="cursor spec-ajax black-text" data-dest="<?= __url__.'/views/ldc/user/materials.php' ?>" data-output="#display"><h5><i class="material-icons" style="padding-right: 14px">view_list</i>Study Materials</h5></a></li>
					<li class="divider"></li>
					<li><a href="" class="cursor spec-ajax black-text" data-dest="<?= __url__.'/views/ldc/user/feedback.php' ?>" data-output="#display"><h5><i class="material-icons" style="padding-right: 14px">forum</i>My Assessments</h5></a></li>
				</ul>
			</div>
			<div class="col s12 m9" id="display">
				<div class="card">
          <div class="card-content">
            <p class="center">Welcome to the <br> <b class="red-text text-darken-2">Ghana Airports Company Limited</b> <br> Learning and Development Centre <br><br> <i>You may access the menu for your needs. <br> Thank you!</i> </p>   
          </div> 
        </div>
			</div>
		</div>

	</div>
</div>