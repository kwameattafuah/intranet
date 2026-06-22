<div class="row">
	<div class="row">
		<span class="flow-text bold"> MANAGEMENT </span>
		<span class="right"><a class="brown lighten-2 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">person_add</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New Management Member</p>
					<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="name" name="name" required class="validate">
							<label for="name">Full Name</label>
						</div>
						<div class="input-field">
							<input type="text" id="position" name="position" required class="validate">
							<label for="position">Position</label>
						</div>
				        <div class="input-field">
						    <select class="browser-default" name="rank" required>
						      <option value="" disabled selected>Please select management function</option>
						      <option value="-1" >Board Management Only</option>
						      <option value="1" >Top Level Management</option>
						      <option value="0" >Both Board & Top Level</option>
						    </select>
				        </div>	    					    													    				
						<div class="input-field center-align">
							<input type="hidden" name="addmgt" value="mgt">
							<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
						</div>
					</form>
				</div>
			</div>
		</div>					
	</div>
	<div class="divider black-text"></div>	
	<div class="scrollable" style="max-height: 500px; overflow-y: auto">
	<?php	if($mgts !== false)
			foreach ($mgts as $mgt) { ?>
		<div class="col s12 m6 l6">
			<div class="card-content blue lighten-2 mgt-card">
				<div class="mgt-div">
					<span><?= $mgt['name'] ?></span>
					<span class="waves-effect spec-ajax right space-left" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $mgt['id'] ?>" data-query="mgtdelete" data-fadeOut=".mgt-card"><i class="material-icons red-text text-darken-2">delete</i></span>
					<span class="waves-effect spec-ajax right" data-extend-view=".edit" data-parent=".mgt-div" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
					<div class="edit hide">
						<div class="row">
							<div class="col s12">
								<p class="flow-text text-center bold red-text">Member Information</p>
									<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
										<div class="input-field">
											<input type="text" id="name-<?=$mgt['id']?>" name="name" required class="validate" value="<?= $mgt['name'] ?>">
											<label for="name">Full Name</label>
										</div>
										<div class="input-field">
											<input type="text" id="position-<?=$mgt['id']?>" name="position" required class="validate" value="<?= $mgt['position'] ?>">
											<label for="position">position</label>
										</div>
								        <div class="input-field">
										    <select class="browser-default" name="rank" required>
										      <option value="" disabled selected>Please choose a type</option>
										      <option value="0" <?= ($mgt['rank'] == 0)?  'selected' : '' ?>>Both Board & Top Level</option>
										      <option value="1" <?= ($mgt['rank'] == 1)?  'selected' : '' ?>>Top Level Management</option>
										      <option value="-1" <?= ($mgt['rank'] == -1)?  'selected' : '' ?>>Board Management Only</option>
										    </select>
								        </div>	    					    													    				
										<div class="input-field center-align">
											<input type="hidden" name="editmgt" value="<?= $mgt['id'] ?>">
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

