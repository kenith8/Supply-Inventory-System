<?php
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = "SELECT accountType FROM user WHERE userID = '$_SESSION[userID]'";
	$result = mysqli_query($connection, $query);
	$getType = mysqli_fetch_assoc($result);

	if($getType['accountType'] == "admin"){
		HEADER("location:controller.php?admin_home");
	}
?>
	            <div> 
	             	<div>
						THIS IS USER PAGE
					</div>
	            </div>                              
	        </div>