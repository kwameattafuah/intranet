<?php  
	// include defines
	include("../layout/definition.php");
	// include controller class
	include("../controllers/directory.controller.php");
	// initialise class
	$class = new Directories;

	// define variables
	$depts = null;
	$directories = null;

	// search
	if (isset($_POST['search'])) {
		if (empty($_POST['search'])){
			list($depts,$directories) = $class->index();
		}else{
			list($depts,$directories) = $class->search(trim($_POST['search']));
		}

		// include display
		include("../views/directory/directory.php");
	}
	// 2nd search
	if (isset($_POST['asearch'])) {
		if (empty($_POST['asearch'])){
			list($depts,$directories) = $class->index();
		}else{
			list($depts,$directories) = $class->search(trim($_POST['asearch']));
		}

		// include display
		include("../views/directory/adirectory.php");
	}


	if (isset($_POST['query']) && $_POST['query'] == "dirs") {
		list($depts,$directories) = $class->index();

		// include display
		include("../views/update/directory.php");
	}

	if(isset($_POST['diradd'])){
		if(empty(trim($_POST['number']))) {
        	$number = "N/A";
        }else{
        	$number = trim($_POST['number']);
		}	
			$result = $class->diradd( $_POST['name'],$_POST['location'],$number,$_POST['extension'],$_POST['dept']);
			if ($result === false) {
				echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
			}else{
				echo '<p class="flow-text center-align green-text">Details added Successfully</p>';
			}	
		}elseif (isset($_POST['editdir'])) {
			$result = $class->editdir( $_POST['name'],$_POST['location'],$_POST['number'],$_POST['extension'],$_POST['dept'],$_POST['editdir']);

			if ($result === true)
				echo '<p class="center-align green-text flow-text">Directory Information updated Successfully</p>';
			else
				echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
		}elseif (isset($_POST['query']) && $_POST['query'] == 'dirdelete') {
			$result = $class->dirdelete($_POST['id']);

			if ($result === false) {
				echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
			}else{
				echo '<script>Materialize.toast("Details Deleted!", 4000)</script>';
			}
		}		

?>