<div class="row">
	<div class="row">
		<span class="flow-text bold"> LINKS MANAGEMENT </span>
		<span class="right"><a class="waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">mouse</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New Link Details</p>
					<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="linktag" name="linktag" required class="validate">
							<label for="linktag">Link Tag</label>
						</div>
						<div class="input-field">
							<input type="text" id="linkurl" name="linkurl" class="validate">
							<label for="linkurl">Link Url</label>
						</div>		    				
						<div class="input-field center-align">
							<input type="hidden" name="addlink" value="set">
							<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
						</div>
					</form>
				</div>
			</div>
		</div>					
	</div>	
	<div class="scrollable" style="max-height: 500px; overflow-y: auto">	
	<?php	if($links !== false)
			foreach ($links as $link) { ?>
		<div class="col s12 m6 l6 link">
			<div class="card blue lighten-1">
				<div class="card-content text-center">
					<p class="truncate"><?= $link['name'] ?></p>
				</div>
		      <div class="card-action">
				<div class="row center-align white-text text-darken-2" style="margin: 0px;">
					<div class="col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></div>
					<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $link['id'] ?>" data-query="linkdelete" data-fadeOut=".link"><i class="material-icons">delete</i></div>
				</div>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold">Update Link Details</p>
							<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
								<div class="input-field">
									<input type="text" id="linktag" name="linktag" required class="validate" value="<?= $link['name'] ?>">
									<label for="linktag">Link Tag</label>
								</div>
								<div class="input-field">
									<input type="text" id="linkurl" name="linkurl" class="validate" value="<?= $link['url'] ?>">
									<label for="linkurl">Link Url</label>
								</div>			    				
								<div class="input-field center-align">
									<input type="hidden" name="editlink" value="<?= $link['id'] ?>">
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

