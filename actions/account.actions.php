<?php  
	// include defines
	include("../layout/definition.php");
	// include controller
	include("../controllers/account.controller.php");
	// initialize class
	$class = new Account;

	// ACCOUNT DETAILS UPDATE
	if (isset($_POST['update'])) {
		$result = $class->update($_POST['name'],$_POST['email'],$_POST['username'],$_POST['password'],$_SESSION['aj.gaclintra']['id']);

		// check if password is correct
		if ($result === "password") {
			echo '<script>Materialize.toast("Incorrect password",3000)</script>';
		}elseif ($result === true){
			$_SESSION['user'] = $_POST['username'];
			echo '<script>Materialize.toast("Updated Successfully", 3000)</script>';
		}elseif ($result === false)
			echo '<script>Materialize.toast("An unexpected error occured! Check Information Provide", 3000)</script>';
	}

	// PASSWORD CHANGE
	if (isset($_POST['pass'])) {
		if ($_POST['npass'] != $_POST['rpass']) {
			echo '<p class="center-align flow-text">Passwords don\'t match</p>';
			return ;
		}

		$result = $class->pass($_POST['password'],$_POST['npass'],$_SESSION['aj.gaclintra']['id']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Password changed Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}

?>