<?php  
	include("../layout/definition.php");
	include("../controllers/forum.controller.php");

	$class = new Forum;

	// define variables
	$recents = null;
	$mine = null;

	// search
	if (isset($_POST['search'])) {
		if (empty($_POST['search']))
			list($recents,$mine) = $class->index($_SESSION['aj.gaclintra']['id']);
		else
			$recents = $class->search($_POST['topic']);
			if ($recents !== false) {
				foreach ($recents as $recent) {
	?>
		<div class="card-content">
			<h5><a  class="blue-text text-darken-4" href=""><?= $recent['topic'] ?></a></h5>
			<p class="truncate"><?= $recent['request'] ?></p>
			<p><?= '<span class="green-text text-darken-1"> By: </span><span class="grey-text">'.$recent['membername'].'</span> | <span class="grey-text">'.date("jS M",strtotime($recent['dateMade'])).'</span>' ?></p>
		</div>
	<?php } } else { ?>
		<div class="row col s12">
			<p class="center flow-text red-text">
			 	No Discussion found with the Topic !
			</p>	
		</div>	
	<?php } }	

	// start discussion
	if (isset($_POST['new'])) {
		$result = $class->new($_SESSION['aj.gaclintra']['id'],$_POST['topic'],$_POST['message']);

		if ($result === false) {
			echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
		}else{
			echo '<p class = "green-text flow-text center"> Topic submitted successfully! </p> <script>window.location="'.__url__.'/forum"</script>';
		}
	}

	// send comment
	if (isset($_POST['comment'])) {
		$result = $class->comment($_SESSION['aj.gaclintra']['id'],$_POST['topic'],$_POST['message']);

		if ($result === false) {
			echo '<p class = "red-text flow-text center"> An unexpected error occured! </p>';
		}else{
			echo '<p class = "green-text flow-text center"> Comment sent successfully! </p>';
		}
	}

	// view discussion
	if (isset($_POST['query']) && $_POST['query'] == 'topic') {
	// get content of info
	list($lead,$recents) = $class->view($_POST['id']);
	// count comments
	$replies = ($recents !== false) ? count($recents):0; 
	// info content
	?>
	<div class="info-details">
	    <div class="row">
	      <div class="col s12">
	          <div class="card-content wrap">
	            <h4 class="blue-text text-darken-3"><?php echo $lead['topic'] ?></h4>
	            <p> <span class="green-text">By</span> <?= $lead['membername'] .' | '.date("jS M @ H:i ",strtotime($lead['dateMade'])).'GMT' ?>
	            	<span class="right yellow-text text-darken-4"><?= ($replies == 1) ? $replies.' Reply' : $replies. ' Replies' ?></span>
	            </p>
	            <div class="divider yellow darken-3"></div>
	            <p class="flow-text"><?php echo $lead['request'] ?></p>
	            <div class="divider yellow darken-3"></div> 
		            <div style="max-height: 500px; overflow-y: auto;">
						<?php 
							if ($recents !== false) {
								foreach ($recents as $recent) {
						?>
						<div class="card">
							<div class="card-content">
								<p><?= '<span class="green-text text-darken-3">'.$recent['membername'].'</span> | <span class="grey-text">'.date("jS M @ H:i ",strtotime($recent['dateMade'])).'GMT</span>' ?></p>
								<p><?= $recent['comment'] ?></p>
							</div>
						</div>	
						<?php } } else { ?>
								<p class="center flow-text">
								 	No replies to <b>Topic</b> yet !
								</p>	
						<?php } ?>	
					</div>
		<!-- actions -->
		<div class="action">
			<div class="fixed-action-btn">
				<a href="" class="btn-floating green darken-2 waves-effect hoverable z-depth-2 spec-ajax" data-extend-view=".add" data-output=".modal-content" data-toggle="modal" data-parent=".action" return="true"><i class="material-icons">add</i></a>
			</div>

			<div class="add hide">
				<div class="row">
					<div class="col s12">
						<p class="flow-text">New Comment</p>
						<form data-dest="<?php echo __url__.'/actions/forum.actions.php' ?>" data-output=".modal-content" class="form" form-type="form">
							<div class="input-field">
	        					<textarea name="message" id="message" required="true" class="materialize-textarea"></textarea>
	        					<label for="message">Comment</label>
	        				</div>
							<div class="input-field center-align">
								<input type="hidden" name="comment" value="set">
								<input type="hidden" name="topic" value="<?= $lead['id'] ?>">
								<input type="submit" value="ADD" class="green darken-2 btn large">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>						            
	          </div>
	      </div>
	    </div>
  	</div>

<?php
	}	
?>