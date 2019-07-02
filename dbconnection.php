<?php
	/* connection.php */
	require("constants.php");
	
	//create session
	session_start();
	
	//create a database connection
	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	if(!$connection){
		die("Database connection failed: ". mysql_error());
	}
?>