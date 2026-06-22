<?php
	$dbhost = '127.0.0.1:3306';
	$dbuser = 'root';
	$dbpass = 'Aa123456';
	$dbname = 'gacl_db';

	$conn = new mysqli ($dbhost, $dbuser, $dbpass, $dbname);

	if($conn->connect_error){
		die("connection failure: something wicked happened");
	}
?>
