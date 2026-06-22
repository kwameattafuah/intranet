<?php  
	// include defines
	include("../layout/definition.php");
	// include controller class
	include("../controllers/docs.controller.php");
	// initialise class
	$class = new Docs;

	// define variables
	$docs = null; $docsf = null;

	if (isset($_POST['query']) && $_POST['query'] == "docs") {
		list($cats,$docs) = $class->index();

		// include display
		include("../views/update/documents.php");

	}elseif(isset($_POST['docadd'])){
		$result = $class->adddoc($_FILES['document'], $_POST['name'],$_POST['category'],$_POST['docadd']);
		if ($result === false) {
			echo '<p class="flow-text center-align red-text">An unexpected error occured! Please try again</p>';
		}else{
			echo '<p class="flow-text center-align green-text">Document added Successfully</p>';
		}	
	}elseif (isset($_POST['editdoc'])) {
		$result = $class->editdoc( $_POST['name'],$_POST['person'],$_POST['editdoc']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Document Information updated Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}elseif (isset($_POST['query']) && $_POST['query'] == 'delete') {
		$result = $class->delete($_POST['id']);

		if ($result === false) {
			echo '<script>Materialize.toast("An unexpected error occured!", 4000)</script>';
		}else{
			echo '<script>Materialize.toast("Document Deleted!", 4000)</script>';
		}
	}else{

	if (isset($_POST['search'])) {
		$docsf = $class->docsfetch($_POST['search']);
	
	// search
	}elseif (isset($_POST['docSearch'])) {
		$docs = $class->search($_POST['category'],$_POST['item']);
	}else{
			$docs = false;
	}
		// include display
		include("../views/docs/doc.php");
	}

			

?>
