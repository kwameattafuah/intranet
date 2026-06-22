
<?php
	// include definition
	include("../../layout/definition.php");

	include("../../controllers/ldc.controller.php");

	// include head
	include("../../layout/head.php");

	// include groups
	if (isset($_GET['ldc'])&&$_GET['ldc']==='register')
		include("./register.php");
	else
		include("./login.php");

	// include js
	include("../../layout/foot.php");
?>	