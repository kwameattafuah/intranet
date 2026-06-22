<?php
  // include definition
  include("../../../layout/definition.php");

  include("../../../controllers/ldc.controller.php");

  // initialise controller class
  $class = new Ldc;

  $users = $class->users();  
?> 

<main>
	<div class="row" style="padding: 20px 10px 0px;">
		<span class="flow-text bold"> USERS MANAGEMENT </span>
		<span class="right"><a class="yellow darken-4 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">person_add</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New User username</p>
					<form data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="name" name="name" required class="validate">
							<label for="name">Full Name</label>
						</div>
						<div class="input-field no-pad">
						    <select class="browser-default" name="position" required>
						      <option value="head">Admin Head</option>
						      <option value="member" selected>Admin Member</option>
						    </select>
				        </div>	
						<div class="input-field">
							<input type="email" id="email" name="email" required class="validate">
							<label for="email">Email</label>
						</div>						
	    				<div class="input-field">
	    					<input type="text" name="phone" id="phone" required class="validate">
	    					<label for="phone">Phone</label>
	    				</div>				        	    					    				
						<div class="row">						
		    				<div class="input-field col s12 m6">
								<input type="password" id="password" name="password" required class="validate" >
								<label for="password">Password</label>
							</div>
		    				<div class="input-field col s12 m6">
								<input type="password" id="cpassword" name="cpassword" required class="validate" >
								<label for="cpassword">Repeat Password</label>
							</div>
						</div>									    				
						<div class="input-field center-align">
							<input type="hidden" name="adduser" value="user">
							<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
						</div>
					</form>
				</div>
			</div>
		</div>					
	</div>
	<div class="divider black-text"></div>	
	<div class="scrollable" style="max-height: 500px; overflow-y: auto">
	<?php	if($users !== false)
			foreach ($users as $user) { ?>
		<div class="col s12 m4 l3 user">
			<div class="card red lighten-2">
				<div class="card-content">
					<p class="truncate"><?= $user['name'] ?></p>
				</div>
		      <div class="card-action">
				<div class="row center-align white-text text-darken-2" style="margin: 0px;">
					<div class="col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></div>
					<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" id="<?= $user['id'] ?>" data-query="userdelete" data-fadeOut=".user"><i class="material-icons">delete</i></div>
				</div>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold red-text">User Information</p>
								<form data-dest="<?php echo __url__.'/actions/ldc.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
									<div class="input-field">
										<input type="text" id="name" name="name" required class="validate" value="<?= $user['name'] ?>">
										<label for="name">Full Name</label>
									</div>
									<div class="input-field no-pad">
									    <select class="browser-default" name="position" required>
									      <option value="head" <?= ($user['position'] == 'head')? 'selected' : '' ?> >Admin Head</option>
									      <option value="member" <?= ($user['position'] == 'member')? 'selected' : '' ?> >Admin Member</option>
									    </select>
							        </div>
									<div class="input-field">
										<input type="email" id="email" name="email" required class="validate" value="<?= $user['email'] ?>">
										<label for="email">Email</label>
									</div>						
				    				<div class="input-field">
				    					<input type="tel" name="phone" id="phone" required class="validate" value="<?= $user['phone'] ?>">
				    					<label for="phone">Phone</label>
				    				</div>							        	    					    				
									<div class="row">						
					    				<div class="input-field col s12 m6">
											<input type="password" id="password" name="password" class="validate" >
											<label for="password">Password</label>
										</div>
					    				<div class="input-field col s12 m6">
											<input type="password" id="cpassword" name="cpassword" class="validate" >
											<label for="cpassword">Repeat Password</label>
										</div>
									</div>									    				
									<div class="input-field center-align">
										<input type="hidden" name="edituser" value="<?= $user['id'] ?>">
										<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>										
			</div>
		</div>			
	<?php } ?>
</div>

</main>