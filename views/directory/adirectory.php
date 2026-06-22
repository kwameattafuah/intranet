<table class="responsive-table">
	<thead>
		<tr class="blue-text text-darken-3">
			<th>Name</th>
			<th>Location</th>
			<th>Number</th>
			<th>Extension</th>
			<th class="center-align">Actions</th>
		</tr>
	</thead>
	<tbody class="wow fadeIn">
	<?php 
		if ($depts !== false){
			foreach ($depts as $dept) {
				$directorys = $class->contacts($dept['dept_id'],$directories);
	?>
		<tr>
			<td colspan="5" class="bold green-text text-darken-3"><h5><?php  echo $dept['name'];  ?></h5></td>
		</tr>
		<?php 
		if ($directorys !== false){
			foreach ($directorys as $directory) { ?>
	<tr class="wow fadeInUp contact">
		<td><?php echo $directory['name']; ?></td>
		<td><?php echo $directory['location']; ?></td>
		<td><?php echo $directory['number']; ?></td>
		<td><?php echo $directory['extension']; ?></td>
		<td class="action">
				<span class="waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
				<span class="waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/directory.actions.php' ?>" data-output=".modal-content" id="<?= $directory['id'] ?>" data-query="dirdelete" data-fadeOut=".contact"><i class="material-icons">delete</i></span>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold red-text">Directory Information Details</p>
							<form data-dest="<?php echo __url__.'/actions/directory.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
								<div class="input-field">
									<input type="text" id="name" name="name" required class="validate" value="<?php echo $directory['name'] ?>">
									<label for="name">Name</label>
								</div>
								<div class="input-field">
									<input type="text" id="location" name="location" required class="validate" value="<?php echo $directory['location'] ?>">
									<label for="location">Location</label>
								</div>
								<div class="input-field">
									<input type="text" id="number" name="number" class="validate" value="<?php echo $directory['number'] ?>">
									<label for="number">Number</label>
								</div>							
								<div class="input-field">
									<input type="text" id="extension" name="extension" required class="validate" value="<?php echo $directory['extension'] ?>">
									<label for="extension">Extension</label>
								</div>
						        <div class="input-field">
								    <select class="browser-default" name="dept" required>
								      <option value="" disabled selected>Choose your Dept</option>
								    <?php 
										if ($depts !== false)
											foreach ($depts as $dept) {
									?> 
								      <option value="<?= $dept['dept_id']?>" <?= ($directory['dept'] == $dept['dept_id'])?  'selected' : '' ?> ><?= $dept['name'] ?></option>
								      <?php } ?>
								    </select>
						        </div>										    							    				
								<div class="input-field center-align">
									<input type="hidden" name="editdir" value="<?= $directory['id'] ?>">
									<input type="submit" value="UPDATE" class="blue darken-2 btn large">
								</div>
							</form>
						</div>
					</div>
				</div>					
			</td>
	</tr>
		<?php } } } } ?>	
	</tbody>
</table>	