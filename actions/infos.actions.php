<?php  
	// include defines
	include("../layout/definition.php");
	// include controller class
	include("../controllers/infos.controller.php");
	// initialise class
	$class = new Docs;

	// define variables
	$infos = null;
	$newx = null;

	// search
	if (isset($_POST['search'])) {
		if (empty($_POST['search']) && $_POST['id']=="1"){
			list($newx,$infos) = $class->index();
			// include display
			include("../views/info/infodoc.php");
		}elseif(empty($_POST['search']) && $_POST['id']=="0"){
			list($newx,$infos) = $class->index();
			// include display
			include("../views/info/news.php");			
		}elseif($_POST['id']=="1"){
			$infos = $class->infosearch($_POST['search']);
			// include display
			include("../views/info/infodoc.php");
		}elseif($_POST['id']=="0"){
			$newx = $class->newssearch($_POST['search']);
			// include display
			include("../views/info/news.php");
		}
	}	

	if (isset($_POST['query']) && $_POST['query'] == "all") {
		$docs = $class->fetch();

		// include display
		include("../views/update/notices.php");
	}

	if(isset($_POST['infoadd'])){
		$result = $class->infoadd($_FILES['information'], $_POST['name'], $_POST['infoadd']);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Notice / Document added Successfully</p>';
		}	
	}

	if (isset($_POST['editdoc'])) {
		$result = $class->editdoc( $_POST['name'],$_POST['person'],$_POST['editdoc']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Notice / Information updated Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}

	if (isset($_POST['query']) && $_POST['query'] == 'delete') {
		$result = $class->delete($_POST['id']);

		if ($result === false) 
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		else
			echo '<script>Materialize.toast("Notice / Information Deleted!", 4000)</script>';
		
	}			
		

?>