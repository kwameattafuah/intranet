<?php  
	// initialise controller class
	$class = new Ldc;

	// get default display
	list($courses,$depts) = $class->course_reg();

  date_default_timezone_set("Africa/Accra");
  $copyright = date("Y"); 	
  $json =file_get_contents('country.json');
  $json_data =json_decode($json,true);
?>

<div class="row">
	<div class="col s12 m8 offset-m2">
		<div class="card z-depth-3 white">
		  <nav class="blue darken-4">
		    <div class="nav-wrapper" style="padding-left: 3%; padding-right: 5%;">
		      <p class="center">
		      	<span style="font-size: 140%"><a href="#">GACL LEARNING & DEVELOPMENT CENTRE</a></span>
		      </p>
		    </div>
		  </nav>			
			<div class="card-content" id="display">
				<form class="form" data-dest="<?= __url__.'/actions/ldclogin.actions.php' ?>" data-output=".modal-content" form-type="form">
					<h5>Registration Form</h5>
					<div class="row">	
						<div class="input-field col s12 m8 no-pad-left">
							<input type="text" id="name" name="name" required="true">
							<label for="name">Name</label>
						</div>
						<div class="input-field col s12 m4 row" style="padding-right: 0px">
							<p style="padding-top: 5%" class="col s5 grey-text">Gender</p>
						    <select class="browser-default col s7" name="sex" required>
								<option value="M">Male</option>
								<option value="F">Female</option>
							</select>
						</div>
					</div>
			        <div class="input-field no-pad">
					    <select class="browser-default" name="dept" required>
					      <option value="" disabled selected>select your Department</option>
					    <?php 
							if ($depts !== false)
								foreach ($depts as $dept) {
						?> 
					      <option value="<?= $dept['dept_id']?>"><?= $dept['name'] ?></option>
					      <?php } ?>
					    </select>
			        </div>	
					<div class="row">
						<div class="input-field col s12 m6 no-pad-left">
							<input type="text" name="position" id="position" required="true">
							<label for="position">Position</label>
						</div>
						<div class="input-field col s12 m6 no-pad-right">
							<input type="text" name="staffid" id="staffid" required="true">
							<label for="">Staff ID</label>
						</div>
					</div>						
					<div class="row">					
						<div class="input-field col s12 m6 no-pad-center">
							<input type="text" name="phone" id="phone" class="center" required="true">
							<label for="phone">Mobile Number</label>
						</div>
						<div class="input-field col s12 m6 no-pad-right">
							<input type="text" name="phone_2" id="phone_2" required="false">
							<label for="">Landline</label>
						</div>
					</div>									
			        <div class="input-field no-pad">
					    <select class="browser-default" name="course" required>
					      <option value="" disabled selected>Pick a Course please</option>
					    <?php 
							if ($courses !== false)
								foreach ($courses as $course) {
						?> 
					      <option value="<?= $course['id']?>"><?= $course['title'] ?></option>
					      <?php } ?>
					    </select>
			        </div>
					<div class="input-field no-pad">
						<input type="email" name="email" id="email" required="true">
						<label for="">Email Address</label>
					</div>
					<div class="input-field no-pad">
							<select class="browser-default" name="country" id="country" required>
					      <option value="" disabled selected>Select your Country</option>
							<?php
								foreach($json_data as $key => $value){
									?>
									<option value="<?php echo $json_data[$key]["name"]?>"><?php echo $json_data[$key]["name"]?></option>
									<?php
								}
							?>
							</select>
					</div>
					<div class="row">
						<div class="input-field col s12 m6 no-pad-left">
							<input type="text" name="Organization" id="Organization" required="false">
							<label for="">Organization</label>
						</div>
						<div class="input-field col s12 m6 no-pad-right">
							<input type="text" name="Hotel_Accomodation" id="Hotel_Accomodation" required="false">
							<label for="">Hotel Accomodation</label>
						</div>
					</div>
					<div class="row">					
						<div class="input-field col s12 m6 no-pad-left">
							<input type="password" name="passphrase" id="passphrase" required="true">
							<label for="passphrase">New Password</label>
						</div>
						<div class="input-field col s12 m6 no-pad-right">
							<input type="password" name="cpassphrase" id="cpassphrase" required="true">
							<label for="cpassphrase">Confirm New Password</label>
						</div>
					</div>
					<div class="input-field center-align">
						<input type="hidden" name="register" value="set">
						<input type="submit" class="btn green darken-2" value="REGISTER">
					</div>
				</form>
			</div>	
			<div class="card-action">
				<p class="center">Powered by <a href="" class="blue-text text-darken-2">GACL ICT <span class="green-text text-darken-1">&copy <?= $copyright ?> </span></a></p>	
			</div>	
		</div>		
	</div>
</div>
