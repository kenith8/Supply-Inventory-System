<?php
	ob_start();
	include_once("dbconnection.php");
	
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$img_profile_dir = 'D:/wamp64/www/InventorySystem/images/profile/';
	$img_profile_subdir = 'images/profile/';

	$query = "SELECT * FROM user WHERE userID ='$_SESSION[userID]'";
	$result = mysqli_query($connection,$query);
	$user = mysqli_fetch_assoc($result);
		
	if (file_exists($img_profile_dir.$user['userID'].'.jpg')){ 
		$src = $img_profile_subdir.$user['userID'].'.jpg';
	}
	else if (file_exists($img_profile_dir.$user['userID'].'.jpeg')){ 
		$src = $img_profile_subdir.$user['userID'].'.jpeg';
	}
	else if(file_exists($img_profile_dir.$user['userID'].'.png')){
		$src = $img_profile_subdir.$user['userID'].'.png';
	}
	else{
		$src = $img_profile_subdir.'noimage'.'.png';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/popper.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/header.css">
		<link rel="stylesheet" href="css/sidebar.css">
		<link rel="stylesheet" href="css/footer.css">
		<link rel="stylesheet" href="css/admin_home.css">
		<link rel="stylesheet" href="css/individual.css">
		<link rel="stylesheet" href="css/manage_user.css">
		<link rel="stylesheet" href="css/manage_inventory.css">
		<link rel="stylesheet" href="css/filter.css">
		<link rel="stylesheet" href="css/table.css">
		<link rel="stylesheet" href="css/stats.css">
		<link rel="stylesheet" href="css/lightbox.min.css">
		<link rel="stylesheet" href="css/transaction_logs.css">
		<link rel="stylesheet" href="css/logout.css">
		<link rel="stylesheet" href="css/add_stock.css">
		<link rel="stylesheet" href="css/getitem.css">
		<link rel="stylesheet" href="css/bootstrap/morris.css">
		<script type="text/javascript" src="js/lightbox-plus-jquery.min.js"></script>
		<script type="text/javascript" src="js/chart_jquery.min.js"></script>
		<script type="text/javascript" src="js/chart_jquery2.min.js"></script>
		<script type="text/javascript" src="js/chart_jquery3.min.js"></script>
		<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="js/FileSaver.min.js"></script>
		<script type="text/javascript" src="js/tableexport.min.js"></script>
		<script type="text/javascript" src="js/childrow.js"></script>
	</head>
<style>
	body{
		margin:0;
		padding:0;
		font-family: sans-serif;
		line-height: 1.5em;
		height: 100%;
		width: 100%;
	}
	html, body {
		height: 100%;
		width: 100%;
		overflow-y: auto;
	}
	p{
		color:#555;
	}
	nav ul{
		list-style: none;
		margin:0;
		padding:0;
	}
	nav ul a{
		color: darkgreen;
		text-decoration: none;
	}
</style>
<body>
	<div id="header_layout">
		<header id="header">
			<div id="name-image">
				<div id="image-profile">
				<?php
					echo "<img src=\"$src\" alt=\"Avatar\" class=\"avatar\">";
				?>
				</div>
			</div>
			<div id="notif-logout">
				<div id="user-name">
				<?php
					echo $user['name'];
				?>
				</div>
	  			<div id="notif-btn">
		  			<div class="header-item">
		  				<div class="dropdown dropleft">
		<?php
							$query = mysqli_query($connection,"SELECT * from item WHERE qty<=10");
							$count = 0;
							while($row = mysqli_fetch_array($query)){
								$count++;	
							}
		?>
		  					<button type="button" class="btn btn-primary dropdown" data-toggle="dropdown" style="padding: 0;border-radius:50%; background-color:#B3de81"><img src="images/icons/notification.png" style="background-color:#B3de81;height:35px;width: 35px; border-radius:50%;"></button>
		  					<div class="dropdown-menu">
		<?php
								$query = mysqli_query($connection,"SELECT * from item WHERE qty<=10");
								while($row = mysqli_fetch_array($query)){
									if($row['qty']<=0){
										echo "<label class='dropdown-item' style='border-bottom:1px solid;'>".$row['item_name']." has no quantity left.<img src='images/warning2.png' style='height:20px;width:20px;'></label>";
									}else{
										echo "<label class='dropdown-item' style='border-bottom:1px solid;'>".$row['item_name']." has only ".$row['qty']." quantity left.<img src='images/warning2.png' style='height:20px;width:20px;'></label>";
									}
								}
		?>
		  					</div>
		  				</div>
		  			</div>
	  			</div>
	  			<div id="logout-btn">
					<a type="button" href="controller.php?logout" onclick="return confirm('Confirm Logout:')" style="padding: 0;border-radius:50%; background-color:#B3de81">
		  				<img src="images/icons/logout.png" style="background-color:#B3de81;height:35px;width: 35px; border-radius:50%;">
		  			</a>
	  			</div>
  			</div>
		</header>
	</div>


