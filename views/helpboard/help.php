<?php 
	if (isset($requests) && $requests !== false) {
	foreach ($requests as $request) { ?>
		<tr class="wow zoomIn request cursor">
			<td><?php echo $request['rName'] ?></a></td>
			<td><?php echo $request['subject'] ?></td>
			<td><?php echo $request['rExt']; ?></td>
			<td class="action">
				<span class="center-align col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">visibility</i></span>
				<span class="<?= ($_SESSION['role']=='PROG' || $_SESSION['role']=='ADMIN' )? '' : 'hide' ?> center-align col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/help.actions.php' ?>" data-output=".modal-content" id="<?= $request['id'] ?>" data-query="requestdelete" data-fadeOut=".request"><i class="material-icons">delete</i></span>
			<div class="edit hide">
				<div class="row">
					<div class="col s12">
						<p class="flow-text center-align bold red-text">Request Details</p>
						<form data-dest="<?php echo __url__.'/actions/help.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
							<div class="row">
								<div class="input-field">
									<input type="text" id="name" name="name" disabled="true" class="black-text validate" value="<?php echo $request['rName'] ?>">
									<label for="name">Name</label>
								</div>
								<div class="input-field">
									<input type="text" id="extension" name="extension" disabled="true" class="black-text validate" value="<?php echo $request['rExt'] ?>">
									<label for="extension">Extension</label>
								</div>															
							</div>	
							<div class="input-field">
								<input type="text" id="subject" name="subject" disabled="true" class="black-text validate" value="<?php echo $request['subject'] ?>">
								<label for="subject">Subject</label>
							</div>
		    				<div class="input-field">
		    					<textarea name="details" id="details" disabled="true" class="black-text materialize-textarea"><?= $request['description'] ?></textarea>
		    					<label for="details">Details</label>
		    				</div>
							<div class="input-field">
								<input type="text" id="authority" name="authority" <?= ($request['treated'] !== '#')? 'disabled="true"' : '' ?> required class="validate" value="<?php echo $request['treated'] ?>">
								<label for="authority">To attend</label>
							</div>			    							    				
							<div class="input-field center-align <?= ($request['treated'] !== '#' )? 'hide' : '' ?>">
								<input type="hidden" name="jobassign" value="<?= $request['id'] ?>">
								<input type="submit" value="ASSIGN" class="blue darken-2 btn large">
							</div>
						</form>
					</div>
				</div>
			</div>					
			</td>
		</tr>	
<?php } } else { ?>
		<tr>
			<td colspan="3" class="red-text center"> Please, No Requests Made!</td>
		</tr>	
<?php } ?>

