<?php 
	if (isset($docs) && $docs !== false) {
		foreach ($docs as $doc) {
?>
	<tr class="wow zoomIn">
		<td><a href="<?= __docs__.'/shared/'.$doc['category'].'/'.$doc['source'] ?>" ><?php echo $doc['name']; ?></a></td>
		<td><?php echo $doc['category'] ?></td>
		<td><?php echo $doc['date_modified']; ?></td>
	</tr>	
<?php } } elseif (isset($docsf) && $docsf !== false) {
	foreach ($docsf as $docf) { ?>
		<tr class="wow zoomIn">
			<td><a href="<?= __docs__.'/shared/'.$doc['category'].'/'.$doc['source'] ?>" ><?php echo $docf['name']; ?></a></td>
			<td><?php echo $docf['category'] ?></td>
			<td><?php echo $docf['date_modified']; ?></td>
			<td class="action">
				<span class="center-align col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
				<span class="center-align col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $docf['id'] ?>" data-query="alertdelete" data-fadeOut=".news"><i class="material-icons">delete</i></span>
			<div class="edit hide">
				<div class="row">
					<div class="col s12">
						<p class="flow-text text-center bold red-text">Alert Information Details</p>
						<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
							<div class="input-field">
								<input type="text" id="subject" name="subject" required class="validate" value="<?php echo $docf['subject'] ?>">
								<label for="subject">Subject</label>
							</div>
		    				<div class="input-field">
		    					<textarea name="details" id="details" required="true" class="materialize-textarea"><?= $docf['details'] ?></textarea>
		    					<label for="details">Details</label>
		    				</div>
							<div class="input-field">
								<input type="text" id="authority" name="authority" required class="validate" value="<?php echo $docf['authority'] ?>">
								<label for="authority">Authority</label>
							</div>			    							    				
							<div class="input-field center-align">
								<input type="hidden" name="editalert" value="<?= $docf['id'] ?>">
								<input type="submit" value="UPDATE" class="blue darken-2 btn large">
							</div>
						</form>
					</div>
				</div>
			</div>					
			</td>
		</tr>	
<?php } } else { ?>
		<tr>
			<td colspan="3" class="red-text center"> Please, No Shared Document Found !</td>
		</tr>	
<?php } ?>

