<?php
	session_start();
	session_name('Aejay');
	
	date_default_timezone_set("Africa/Accra");
	$copyright = date("Y"); 

	// define root directory
	define("__ROOT__", str_replace("layout", "", __dir__), TRUE);

	// connect to database
	require_once(__ROOT__.'core/functions.php');

	// get metadata from table
	define("__ASSETS__", "http://10.112.2.50:70/assets", TRUE);

	// define url
	define("__URL__", "http://10.112.2.50:70", TRUE);

	// define media
	define("__MEDIA__", "http://10.112.2.50:70/media", TRUE);	

	// define documents
	define("__DOCS__", "http://10.112.2.50:70/docs/", TRUE);		

	/*--- echo errors in development --*/
	ini_set('display_errors', 0);
	//error_reporting(E_ALL);
?>