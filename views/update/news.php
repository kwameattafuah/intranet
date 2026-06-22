<div id="newsarea">
<?php
	if (isset($_POST['query']) && $_POST['query'] == 'newsadd') { ?>
		<div class="row" id="add">
			<div class="col s12">
				<div class="row">
					<span class="flow-text bold"> NEW NEWS ITEM DETAILS </span>
					<span class="right"><a class="btn-floating btn waves-effect waves-light red spec-ajax" data-trigger="#newsButton"><i class="material-icons left">reply</i></a></span>				
				</div>
				<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
					<div class="input-field">
						<input type="text" id="headline" name="headline" required class="validate">
						<label for="headline">Headline</label>
					</div>
    				<div>
    					<textarea name="content" id="content" required="true" class="materialize-textarea" placeholder="Type brief content here"></textarea>
    				</div>
					<div class="row">
			            <div class="switch col s12 m6">
				        	<h6>The Top Story?</h6>
							  <label>
							    <span class="red-text">NO</span>
							    <input type="checkbox" name="topstory">
							    <span class="lever"></span>
							    <span class="green-text">YES</span>
							  </label>
						</div>
				      	<div class="switch col s12 m6">
				        	<h6>Set Status</h6>
							  <label>
							    <span class="red-text">INVISIBLE</span>
							    <input type="checkbox" name="status">
							    <span class="lever"></span>
							    <span class="green-text">VISIBLE</span>
							  </label>
						</div>
					</div>
					<div class="input-field">
						<input type="text" id="url" name="url" class="validate" value="#">
						<label for="url">NEWS URL</label>
			        </div>
		            <div class="input-field file-field">
						<div class="btn blue">
							<span>image(s)</span>
							<input type="file" name="pics[]" multiple="true">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
		            </div>									    				
					<div class="input-field center-align">
						<input type="hidden" name="addnews" value="set">
						<input type="submit" value="SUBMIT" class="blue darken-2 btn large">
					</div>
				</form>
			</div>
		</div>
<?php	} elseif (isset($_POST['query']) && $_POST['query'] == 'newsedit') { 
			// include controller
			include("../../layout/definition.php");
			// include dashboard
			include("../../controllers/update.controller.php");	
				$class = new Update;
				$new = $class->fetch_newsitem($_POST['id']);
				$newspics = $class->fetch_newspics($_POST['id']);
	?>
	<div class="row" id="edit">
		<div class="col s12">
			<div class="row">
				<span class="flow-text bold"> UPDATE NEWS ITEM DETAILS </span>
				<span class="right"><a class="btn-floating btn waves-effect waves-light red spec-ajax" data-trigger="#newsButton"><i class="material-icons left">reply</i></a></span>				
			</div>
			<form data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
				<div class="input-field">
					<input type="text" id="headline" name="headline" required class="validate" value="<?php echo $new['headline'] ?>">
					<label for="headline">Headline</label>
				</div>
				<div class="input-field">
					<textarea name="content" id="content" required="true" class="materialize-textarea"><?= $new['content'] ?></textarea>
					
				</div>
				<div class="row">
		            <div class="switch col s12 m6">
			        	<h6>The Top Story</h6>
						  <label>
						    <span class="red-text">NO</span>
						    <input type="checkbox" name="topstory" <?= ($new['topstory']==1)? 'checked' : ''; ?> >
						    <span class="lever"></span>
						    <span class="green-text">YES</span>
						  </label>
					</div>
			      	<div class="switch col s12 m6">
			        	<h6>Set Status</h6>
						  <label>
						    <span class="red-text">INVISIBLE</span>
						    <input type="checkbox" name="status" <?= ($new['visible']==1)? 'checked' : ''; ?> >
						    <span class="lever"></span>
						    <span class="green-text">VISIBLE</span>
						  </label>
					</div>
				</div>
				<div class="input-field">
					<input type="text" id="url" name="url" class="validate" value="<?= $new['url'] ?>">
					<label for="url">URL</label>
		        </div>
		        <div class="row">
		        	<h6 class="grey-text">News Item Image(s)</h6>
			      <?php foreach ($newspics as $pic) {  ?>
			        <div class="col s6 m3 newspic">
			          <div class="card">
			            <div class="card-image">
			              <img src="<?= __media__.'/news_gallery/'.$pic['frame'] ?>" class="spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-query="viewnewspic" data-output=".modal-content" id="<?= $pic['id'] ?>" data-toggle="modal">
			            </div> 
			          </div>
			        </div>  
			    <?php } ?>	
		        </div>			    				
				<div class="input-field center-align">
					<input type="hidden" name="editnews" value="<?= $new['id'] ?>">
					<input type="submit" value="UPDATE" class="blue darken-2 btn large">
				</div>
			</form>
		</div>
	</div>
<?php	} else { ?>
	<div class="row">
		<div class="row">
			<span class="flow-text bold"> NEWS PORTAL </span>
			<span class="right"><a class="waves-effect waves-light btn spec-ajax" data-query="newsadd" data-output="#newsarea" data-dest="<?php echo __url__.'/views/update/news.php' ?>"><i class="material-icons left">attachment</i>Add</a></span>				
		</div>	
		<div class="scrollable" style="max-height: 500px; overflow-y: auto">
		<?php	if($news !== false) {
				foreach ($news as $new) { 
					$newspics = $class->fetch_newspics($new['id']); ?>
			<div class="col s12 m6 l6 news">
				<div class="card blue lighten-1">
					<div class="card-content">
						<p class="truncate"><?= $new['headline'] ?></p>
					</div>
				    <div class="card-action">
						<div class="row center-align white-text text-darken-2" style="margin: 0px;">
							<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/views/update/news.php' ?>" data-output="#newsarea" data-query="newsedit" id="<?= $new['id'] ?>"><i class="material-icons">mode_edit</i></div>
							<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/update.actions.php' ?>" data-output=".modal-content" id="<?= $new['id'] ?>" data-query="newsdelete" data-fadeOut=".news"><i class="material-icons">delete</i></div>
						</div>
					</div>										
				</div>
			</div>			
		<?php } } ?>
		</div>
	</div>
<?php } ?>
</div>

<script type="text/javascript"> CKEDITOR.replace( 'content' ); </script>