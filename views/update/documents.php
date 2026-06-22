<div class="row">
	<div class="row">
        <div class="input-field col s12 m9">
          <i class="material-icons prefix">search</i>
          <input id="search" type="text" required class="dynamic-search" data-query="search" data-dest="<?php echo __url__.'/actions/docs.actions.php'; ?>" data-output="tbody">
          <label for="icon_prefix">Type Document Name here</label>
        </div>
        <div class="col s12 m3">		
		<span class="right"><a class="red darken-3 waves-effect waves-light btn spec-ajax" data-extend-view=".add" data-parent=".row" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons left">description</i>Add</a></span>
		<div class="add hide">
			<div class="row">
				<div class="col s12">
					<p class="flow-text text-center bold">New Document Details</p>
					<form data-dest="<?php echo __url__.'/actions/docs.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
						<div class="input-field">
							<input type="text" id="name" name="name" required class="validate">
							<label for="name">Name</label>
						</div>
				        <div class="input-field">
						    <select class="browser-default" name="category" required>
						      <option value="" disabled selected>Please choose a category</option>
						    <?php 
								if ($cats !== false)
									foreach ($cats as $cat) {
							?> 
						      <option value="<?= $cat['id']?>"><?= $cat['name'] ?></option>
						      <?php } ?>
						    </select>
				        </div>
			            <div class="input-field file-field">
							<div class="btn green">
								<span>document</span>
								<input type="file" name="document">
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate" type="text">
							</div>
			            </div>				        				    				
						<div class="input-field center-align">
							<input type="hidden" name="docadd" value="<?= $_SESSION['user'] ?>">
							<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
						</div>
					</form>
				</div>
			</div>
		</div>	
		</div>				
	</div>	
	<div class="card">
		<div class="card-content">	
			<table class="responsive-table">		
				<thead>
					<tr class="blue-text text-darken-3">
						<th>Name</th>
						<th>Category</th>
						<th>Date Modified</th>
						<th class="center-align">Actions</th>
					</tr>
				</thead>
				<tbody style="overflow-y: auto;">
			<?php 
				if ($docs !== false)
					foreach ($docs as $doc) {
			?>
			<tr class="wow zoomIn doc">
				<td><a href="<?= __docs__.'/shared/'.$doc['category'].'/'.$doc['source'] ?>" ><?php echo $doc['name']; ?></a></td>
				<td><?php echo $doc['category'] ?></td>
				<td><?php echo $doc['date_modified']; ?></td>
				<td class="action">
					<span class="center-align col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></span>
					<span class="center-align col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/docs.actions.php' ?>" data-output=".modal-content" id="<?= $doc['id'] ?>" data-query="delete" data-fadeOut=".doc"><i class="material-icons">delete</i></span>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text text-center bold red-text">Document Details</p>
							<form data-dest="<?php echo __url__.'/actions/docs.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
								<div class="input-field">
									<input type="text" id="name" name="name" required class="validate" value="<?= $doc['name'] ?>">
									<label for="name">Name</label>
								</div>			    							    				
								<div class="input-field center-align">
									<input type="hidden" name="editdoc" value="<?= $doc['id'] ?>">
									<input type="hidden" name="person" value="<?= $_SESSION['user'] ?>">
									<input type="submit" value="UPDATE" class="blue darken-2 btn large">
								</div>
							</form>
						</div>
					</div>
				</div>					
				</td>
			</tr>	
		<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>										


