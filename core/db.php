<?php
    $conn = new mysqli(DB_HOST . ':' . DB_PORT, DB_USER, DB_PASS, DB_NAME);

	if($conn->connect_error){
		die("connection failure: something wicked happened");
	}
?>
