<?php  
	// include define
	include("../layout/definition.php");
	// include controller class
	include("../controllers/login.controller.php");
	// initialise controller class
	$class = new Login;

	if (isset($_POST['login'])){
		$credentials = $class->login($_POST['username'],$_POST['passphrase']);
		if ($credentials === false){
			echo '<script>Materialize.toast("Incorrect Credentials Provided. Please cross-check!", 4000)</script>';	
		}else{
			$_SESSION['aj.gaclintra'] =
			  array(
			    "user" => $credentials['username'],
			    "sid" => $credentials['staff_id'],
			    "id" => $credentials['id'],
			    "role" => $credentials['role'],
			    "dept" => $credentials['dept']
			 );
			echo '<script>window.location="'.__url__.'/home"</script>';
		}
	}

	if (isset($_POST['query']) && $_POST['query'] == "logout"){
		session_destroy();
		echo '<script>window.location="'.__url__.'/login"</script>';
	}

?>

