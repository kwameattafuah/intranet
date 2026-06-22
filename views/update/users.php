<div class="row">
	<div class="row">
		<span class="flow-text bold"> USERS MANAGEMENT </span>
		<span class="right"><a class="red darken-3 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">person_add</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New User username</p>
					<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="name" name="name" required class="validate">
							<label for="name">Full Name</label>
						</div>
						<div class="input-field">
							<input type="email" id="email" name="email" required class="validate">
							<label for="email">Email</label>
						</div>
						<div class="row">						
		    				<div class="input-field col s12 m6">
		    					<input type="text" name="username" id="username" required class="validate">
		    					<label for="username">Username</label>
		    				</div>
		    				<div class="input-field col s12 m6">
		    					<input type="text" name="staff_id" id="staff_id" required class="validate">
		    					<label for="staff_id">Staff Id</label>
		    				</div>
	    				</div>
				        <div class="input-field">
						    <select class="browser-default" name="dept" required>
						      <option value="" disabled selected>Please choose a department</option>
						    <?php 
								if ($depts !== false)
									foreach ($depts as $dept) {
							?> 
						      <option value="<?= $dept['dept_id']?>" ><?= $dept['name'] ?></option>
						      <?php } ?>
						    </select>
				        </div>
				        <div class="input-field">
						    <select class="browser-default" name="role" required>
						      <option value="" disabled selected>Please choose a role</option>
						    <?php 
								if ($roles !== false)
									foreach ($roles as $role) {
							?> 
						      <option value="<?= $role['id']?>" ><?= $role['name'] ?></option>
						      <?php } ?>
						    </select>
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
		<div class="col s12 user card no-pad">
			<div class="card-content red lighten-3">
				<div class="user-div">
					<span><?= $user['full_name'] ?></span>
					<span class="right waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $user['id'] ?>" data-query="userdelete" data-fadeOut=".news"><i class="material-icons brown-text text-darken-5">delete</i></span>
					<span class="space-right right waves-effect spec-ajax" data-extend-view=".edit" data-parent=".user-div" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
					<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold red-text">User Information</p>
								<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
									<div class="input-field">
										<input type="text" id="name-<?=$user['id']?>" name="name" required class="validate" value="<?= $user['full_name'] ?>">
										<label for="name">Full Name</label>
									</div>
									<div class="input-field">
										<input type="email" id="email-<?=$user['id']?>" name="email" required class="validate" value="<?= $user['email'] ?>">
										<label for="email">Email</label>
									</div>
									<div class="row">						
					    				<div class="input-field col s12 m6">
					    					<input type="text" name="username" id="username-<?=$user['id']?>" required class="validate" value="<?= $user['username'] ?>">
					    					<label for="username">Username</label>
					    				</div>
					    				<div class="input-field col s12 m6">
					    					<input type="text" name="staff_id" id="staff_id-<?=$user['id']?>" required class="validate" value="<?= $user['staff_id'] ?>">
					    					<label for="staff_id">Staff Id</label>
					    				</div>
				    				</div>
							        <div class="input-field">
									    <select class="browser-default" name="dept" required>
									      <option value="" disabled>Please choose a department</option>
									    <?php 
											if ($depts !== false)
												foreach ($depts as $dept) {
										?> 
									      <option value="<?= $dept['dept_id'] ?>" <?= ($dept['dept_id'] == $user['dept'])? 'selected' : '' ?> ><?= $dept['name'] ?></option>
									      <?php } ?>
									    </select>
							        </div>
							        <div class="input-field">
									    <select class="browser-default" name="role" required>
									      <option value="" disabled>Please choose a role</option>
									    <?php 
											if ($roles !== false)
												foreach ($roles as $role) {
										?> 
									      <option value="<?= $role['id']?>" <?= ($role['id'] == $user['role'])? 'selected' : '' ?> ><?= $role['name'] ?></option>
									      <?php } ?>
									    </select>
							        </div>								        	    					    				
									<div class="row">						
					    				<div class="input-field col s12 m6">
											<input type="password" id="password-<?=$user['id']?>" name="password" class="validate" >
											<label for="password">Password</label>
										</div>
					    				<div class="input-field col s12 m6">
											<input type="password" id="cpassword-<?=$user['id']?>" name="cpassword" class="validate" >
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
</div>

