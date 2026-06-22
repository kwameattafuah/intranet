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
			list($depts,$directories) = $class->search($_POST['search']);
		}

		// include display
		include("../views/directory/directory.php");
	}

?>