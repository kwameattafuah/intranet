<?php
	// include definitons
	include('../../layout/definition.php');

	// include dashboard controller
	include("../../controllers/dashboard.controller.php");

	// include head
	include('../../layout/head.php');

	// include navigation
	include ('../../layout/nav.php');


	/*-- body --*/

	// include dashboard
	include("./panel.php");

	/*-- end of body --*/

	// include foot
	include ('../../layout/foot.php');
?>