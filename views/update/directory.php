<div class="row">
	<div class="row">
        <div class="input-field col s12 m9">
          <i class="material-icons prefix">search</i>
          <input id="search" type="text" required class="admin-search" data-query="search" data-dest="<?php echo __url__.'/actions/directory.actions.php'; ?>" data-output="#dirSearch">
          <label for="icon_prefix">Type Department or Name here</label>
        </div>
        <div class="col s12 m3">		
		<span class="right"><a class="red darken-3 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">settings_phone</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New Directory Details</p>
						<form data-dest="<?php echo __url__.'/actions/directory.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
							<div class="input-field">
								<input type="text" id="name" name="name" required class="validate" >
								<label for="name">Name</label>
							</div>
							<div class="input-field">
								<input type="text" id="location" name="location" required class="validate" >
								<label for="location">Location</label>
							</div>
							<div class="input-field">
								<input type="text" id="number" name="number" class="validate" >
								<label for="number">Number</label>
							</div>							
							<div class="input-field">
								<input type="text" id="extension" name="extension" required class="validate" >
								<label for="extension">Extension</label>
							</div>
					        <div class="input-field">
							    <select class="browser-default" name="dept" required>
							      <option value="" disabled selected>Choose your Dept</option>
							    <?php 
									if ($depts !== false)
										foreach ($depts as $dept) {
								?> 
							      <option value="<?= $dept['dept_id']?>" ><?= $dept['name'] ?></option>
							      <?php } ?>
							    </select>
					        </div>									    							    				
							<div class="input-field center-align">
								<input type="hidden" name="diradd" value="set">
								<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
							</div>
						</form>
				</div>
			</div>
		</div>	
		</div>				
	</div>	
	<div class="card">
		<div class="card-content" id="dirSearch">	
			<?php include("../views/directory/adirectory.php") ?>
		</div>
	</div>
</div>										


