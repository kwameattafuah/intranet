<div class="row scrollable" style="max-height: 500px; overflow-y: auto">
<?php	if($media !== false)
		foreach ($media as $medium) { ?>
		  <div class="col s12 m4 medium">
		    <div class="card">
		      <div class="card-image">
		      	<?php if($medium['gallery_category'] == 1){  ?>
		        <img src="<?= __media__.'/gallery/'.$medium['frame'] ?>" alt="" class="responsive img">
		        <?php }else{ 
		        	echo ($medium['frame']);
		         } ?>
		      </div>
		      <div class="card-action">
				<div class="row center-align blue-text text-darken-2" style="margin: 0px;">
					<div class="col s6 waves-effect spec-ajax" data-extend-view=".edit" data-parent=".card-action" data-output=".modal-content" data-toggle="modal" return="true"><i class="material-icons">mode_edit</i></div>
					<div class="col s6 waves-effect spec-ajax" data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" id="<?= $medium['id'] ?>" data-query="delete" data-fadeOut=".medium"><i class="material-icons">delete</i></div>
				</div>
				<div class="edit hide">
					<div class="row">
						<div class="col s12">
							<p class="flow-text">Update Image Details</p>
							<form data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
								<div class="input-field">
									<input type="text" id="caption" name="caption" required class="validate" value="<?php echo $medium['caption'] ?>">
									<label for="name">Caption</label>
								</div>
								<div class="row">
						            <div class="input-field col s12 m8">
									<input type="text" id="event" name="event" class="validate" value="">
									<label for="caption">Event</label>
						            </div>
							      	<div class="switch col s12 m4">
							        	<h6>Set Status</h6>
										  <label>
										    <span class="red-text">INVISIBLE</span>
										    <input type="checkbox" name="status" <?= ($medium['visible']==1)? 'checked' : ''; ?> >
										    <span class="lever"></span>
										    <span class="green-text">VISIBLE</span>
										  </label>
									</div>
								</div>
								<div class="input-field center-align">
									<input type="hidden" name="update" value="<?= $medium['id'] ?>">
									<input type="submit" value="UPDATE" class="blue darken-2 btn large">
								</div>
							</form>
						</div>
					</div>
				</div>				
		      </div>
		    </div>
		  </div>
		<?php } 
		else
			echo '<p class="flow-text grey-text center-align">No Media Available</p>'; 
		?>

		<!-- actions -->
		<div class="action">
			<div class="fixed-action-btn horizontal click-to-toggle">
				<a class="btn-floating btn-large green darken-2 waves-effect ">
					<i class="material-icons">add</i>
				</a>
				<ul>
					<li>
						<a href="" class="btn-floating blue waves-effect hoverable z-depth-2 spec-ajax" data-extend-view="#images" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
							<i class="material-icons">image</i>
						</a>
					</li>
					<li>
						<a href="" class="btn-floating yellow darken-2 waves-effect hoverable z-depth-2 spec-ajax" data-extend-view="#videos" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true">
							<i class="material-icons">movie</i>
						</a>
					</li>										
				</ul>
			</div>

			<div id="images" class="hide">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s12">
								<p class="flow-text bold">IMAGE DETAILS</p>
								<form data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">			    
									<div class="input-field">
										<input type="text" id="caption" name="caption" required class="validate" value="">
										<label for="caption">Caption</label>
									</div>
									<div class="input-field">
										<input type="text" id="event" name="event" class="validate" value="">
										<label for="caption">Event</label>
									</div>								
									<div class="row">
							            <div class="input-field file-field col s12 m8">
											<div class="btn green">
												<span>image(s)</span>
												<input type="file" name="pics[]" multiple="true">
											</div>
											<div class="file-path-wrapper">
												<input class="file-path validate" type="text">
											</div>
							            </div>
								      	<div class="switch col s12 m4">
								        	<h6>Set Status</h6>
											  <label>
											    <span class="red-text">INVISIBLE</span>
											    <input type="checkbox" name="status" >
											    <span class="lever"></span>
											    <span class="green-text">VISIBLE</span>
											  </label>
										</div>
									</div>
									<div class="input-field center-align">
										<input type="hidden" name="add" value="image">
										<input type="submit" value="Add Media" class="blue darken-2 btn large">
									</div>	
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="videos" class="hide">
				<div class="card">
					<div class="card-content">
						<div class="row">
							<div class="col s12">
								<p class="flow-text bold">VIDEO DETAILS</p>
								<form data-dest="<?php echo __url__.'/actions/media.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">			    
									<div class="input-field">
										<input type="text" id="caption" name="caption" required class="validate" value="">
										<label for="caption">Caption</label>
									</div>
									<div class="input-field">
										<input type="text" id="event" name="event" class="validate" value="">
										<label for="caption">Event</label>
									</div>									
									<div class="row">
							            <div class="input-field col s12 m8">
											<input type="text" id="vid" name="vid" required class="validate" value="">
											<label for="vid">Embedded Url</label>
							            </div>					            
								      	<div class="switch col s12 m4">
								        	<h6>Set Status</h6>
											  <label>
											    <span class="red-text">INVISIBLE</span>
											    <input type="checkbox" name="status" >
											    <span class="lever"></span>
											    <span class="green-text">VISIBLE</span>
											  </label>
										</div>
									</div>											
									<div class="input-field center-align">
										<input type="hidden" name="add" value="video">
										<input type="submit" value="Add Media" class="blue darken-2 btn large">
									</div>	
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>			
