<?php
	// include controller
	include("../../layout/definition.php");
	// include dashboard
	include("../../controllers/infos.controller.php");

	// include head
	include("../../layout/head.php");

	// include nav
	include("../../layout/nav.php");

	if (isset($_GET['aj_news'])) {
		// include docs
		include("./news.php");
	}else{
		// include docs
		include("./infos.php");		
	}


	// include js
	include("../../layout/foot.php")
?>
