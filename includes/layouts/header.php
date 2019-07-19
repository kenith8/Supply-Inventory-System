<?php
	include_once("dbconnection.php");
	
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
	$query = "select name from user where userID = '".$_SESSION['userID']."'";
	$get_id = mysqli_query($connection,$query);
	$id = mysqli_fetch_assoc($get_id);
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/sidebar.css">
		<link rel="stylesheet" href="css/footer.css">
		<link rel="stylesheet" href="css/admin_home.css">
		<link rel="stylesheet" href="css/manage_user.css">
		<link rel="stylesheet" href="css/manage_inventory.css">
		<link rel="stylesheet" href="css/getitem.css">
		<link rel="stylesheet" href="css/transaction_logs.css">
		<link rel="stylesheet" href="css/filter.css">
		<link rel="stylesheet" href="css/table.css">
		<style type="text/css">
			body {
                margin: 0;
                padding: 0;
                font-family:Sans-serif;
                line-height: 1.5em;
            }
            body {
                height: 100%;
			    width: 100%;
			}
            }
			* html body {
				height: 100%;
            	overflow: hidden;
        	}
        	p {
                color: #555;
            }

            nav ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            
            nav ul a {
                color: darkgreen;
                text-decoration: none;
            }
		</style>
	</head>
	<body>
		<div id="header_layout">
	        <header id="header">
	        	<?php
					echo $id['name'];
				?>
				<a href="controller.php?logout" class="header-item">Log Out</a>
				<a href="#" class="header-item">Notification</a>
	        </header>
    	</div>