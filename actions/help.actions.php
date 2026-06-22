<?php  
	include("../layout/definition.php");
	include("../controllers/help.controller.php");

	$class = new Help;

	// define variables
	$infos = null;

	// search
	if (isset($_POST['search'])) {
		if (empty($_POST['search'])){
			$infos = $class->index();
		}else{
			$infos = $class->search($_POST['search']);
		}
		// include display
		include("../views/help/info.php");
	}

	// send request
	if (isset($_POST['send'])) {
		$result = $class->send($_SESSION['aj.gaclintra']['id'],$_POST['name'],$_POST['extension'],$_POST['subject'],$_POST['description']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Request sent successfully!", 4000)</script>';
		}
	}

	// view help info
	if (isset($_POST['query']) && $_POST['query'] == 'info') {
	// get content of info
	$info = $class->view($_POST['id']);
	
	// info content
	?>
	<div class="info-details">
	    <div class="row">
	      <div class="col s12">
	          <div class="card-content">
	            <h4 class="center"><?php echo $info['heading'] ?></h4>
	            <div class="divider"></div>
	            <p><?php echo $info['body'] ?></p>
	          </div>
	      </div>
	    </div>
  	</div>

<?php
	}
	if(isset($_POST['jobassign'])){
		$result = $class->jobadd($_POST['authority'],$_POST['jobassign']);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Details assigned Successfully</p>';
		}	
	}elseif (isset($_POST['query']) && $_POST['query'] == 'requestdelete') {
			$result = $class->requestdelete($_POST['id']);

			if ($result === false) {
				echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
			}else{
				echo '<script>Materialize.toast("Request Deleted!", 4000)</script>';
			}
		}		
?>