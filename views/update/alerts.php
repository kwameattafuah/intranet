<div class="row">
	<div class="row">
		<span class="flow-text bold"> ALERT MANAGEMENT </span>
		<span class="right"><a class="red darken-3 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">add_alert</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New Alert Details</p>
					<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="subject" name="subject" required class="validate">
							<label for="subject">Subject</label>
						</div>
	    				<div class="input-field">
	    					<textarea name="details" id="details" required="true" class="materialize-textarea"></textarea>
	    					<label for="details">Details</label>
	    				</div>
						<div class="input-field">
							<input type="text" id="authority" name="authority" required class="validate" >
							<label for="authority">From</label>
						</div>			    				
						<div class="input-field center-align">
							<input type="hidden" name="addalert" value="set">
							<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
						</div>
					</form>
				</div>
			</div>
		</div>					
	</div>	
	<div class="scrollable" style="max-height: 500px; overflow-y: auto">
	<?php	if($alerts !== false)
			foreach ($alerts as $alert) { ?>
		<div class="col s12 m6 l6 news">
			<div class="card red lighten-2">
				<div class="card-content">
					<p class="truncate center-align"><?= $alert['subject'] ?></p>
				</div>
		      <div class="card-action">
				<div class="row center-align white-text text-darken-2" style="margin: 0px;">
					<div class="col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></div>
					<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $alert['id'] ?>" data-query="alertdelete" data-fadeOut=".news"><i class="material-icons">delete</i></div>
				</div>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold red-text">Alert Information Details</p>
							<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
								<div class="input-field">
									<input type="text" id="subject" name="subject" required class="validate" value="<?php echo $alert['subject'] ?>">
									<label for="subject">Subject</label>
								</div>
			    				<div class="input-field">
			    					<textarea name="details" id="details" required="true" class="materialize-textarea"><?= $alert['details'] ?></textarea>
			    					<label for="details">Details</label>
			    				</div>
								<div class="input-field">
									<input type="text" id="authority" name="authority" required class="validate" value="<?php echo $alert['authority'] ?>">
									<label for="authority">Authority</label>
								</div>			    							    				
								<div class="input-field center-align">
									<input type="hidden" name="editalert" value="<?= $alert['id'] ?>">
									<input type="submit" value="UPDATE" class="blue darken-2 btn large">
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

