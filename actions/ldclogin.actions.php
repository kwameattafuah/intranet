<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/ldclogin.controller.php");
	// initialise controller class
	$class = new Login;

	if (isset($_POST['login'])){
		$mail = stripslashes(strip_tags(trim($_POST['email'])));
		$pass = trim($_POST['passphrase']);
		$credentials = $class->login($mail,$pass);
		if ($credentials === false){
			echo '<script>Materialize.toast("Incorrect Credentials Provided. Please cross-check!", 2000)</script>';	
		}else{
			$tokens = explode(' ', $credentials['name']);
			$_SESSION['aj.ldc'] =
			  array(
			    "ldcuser" => $tokens[0],
			    "ldcmail" => $credentials['email'],
			    "ldcid" => $credentials['id'],
			    "ldcstaffid" => $credentials['staff_id']
			 ); 
			echo '<script> window.location="'.__url__.'/ldc/user/" </script>';
		}
	}

	if (isset($_POST['auth'])){
		$mail = stripslashes(strip_tags(trim($_POST['email'])));
		$pass = trim($_POST['passphrase']);

		$credentials = $class->authenticate($mail,$pass);
		if ($credentials === false){
			echo '<script>Materialize.toast("Incorrect Credentials Provided. Please cross-check!", 2000)</script>';	
		}else{
			$tokens = explode(' ', $credentials['name']);
			$_SESSION['aj.ldc'] =
			  array(
			    "ldcuser" => $tokens[0],
			    "ldcmail" => $credentials['email'],
			    "ldcid" => $credentials['id'],
			    "ldcpos" => $credentials['position']
			 );
			echo '<script>window.location="'.__url__.'/ldc/admin/"</script>';
		}	
	}	

	if (isset($_POST['register'])){
		$state = $class->register( $_POST['name'],$_POST['sex'],strtolower($_POST['email']),$_POST['phone'],$_POST['position'],$_POST['staffid'],$_POST['course'],$_POST['dept'],trim($_POST['passphrase']),trim($_POST['cpassphrase']),$_POST['Hotel_Accomodation'],$_POST['country'],$_POST['Organization'], $_POST['phone_2'] );
		if ($state === false){
			echo '<script>Materialize.toast("An ERROR occured, Please Try Again Later!", 5000)</script>';	
		}elseif ($state === 'pass') {
			echo '<script>Materialize.toast("Passwords do not Match! Please check and Try Again Later", 5000)</script>';
		}elseif ($state === 'present') {
			echo '<script>Materialize.toast("You\'re already registered. Please proceed to Login!", 5000)</script>';
		}else{
			echo '<script> window.location="'.__url__.'/ldc/?ldc=success" </script>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == "logout"){
		unset($_SESSION['aj.ldc']);
		echo '<script>window.location="'.__url__.'/ldc/"</script>';
	}

	// account details update
	if (isset($_POST['update'])) {
		$result = $class->update($_POST['name'],$_POST['position'],$_POST['phone'],$_POST['passphrase'],$_SESSION['aj.ldc']['ldcid']);

		// check if password is correct
		if ($result === "password") {
			echo '<script>Materialize.toast("Incorrect password",3000)</script>';
		}elseif ($result === true){
			$_SESSION['user'] = $_POST['username'];
			echo '<script>Materialize.toast("Updated Successfully", 3000)</script>';
		}elseif ($result === false)
			echo '<script>Materialize.toast("An unexpected error occured! Check Information Provide", 3000)</script>';
	}

	// password change
	if (isset($_POST['passchange'])) {
		if ($_POST['npass'] != $_POST['rpass']) {
			echo '<p class="center-align flow-text red-text">Passwords don\'t match</p>';
			return ;
		}

		$result = $class->pass($_POST['password'],$_POST['npass'],$_SESSION['aj.ldc']['ldcid']);

		if ($result === true)
			echo '<p class="center-align green-text flow-text">Password changed Successfully</p>';
		else
			echo '<p class="center-align red-text flow-text">An unexpected error occured</p>';
	}	

?>

