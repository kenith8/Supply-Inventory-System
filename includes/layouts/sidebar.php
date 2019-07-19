<?php
	include_once("dbconnection.php");
	require_once("F:/Wamp/wamp64/www/InventorySystem/function.php");
	
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
?>
        <div id="sidebar-container">
            <nav id="left" class="column">
                <a href="controller.php?admin_home"><h3 class="subject">Supply Category</h3></a>
                
                <ul class="orderlist">
<?php
	$query = "SELECT supplycat FROM category";
	$result = mysqli_query($connection,$query);
	while($category = mysqli_fetch_assoc($result)){
?>
                    <li><a href="#"><?php echo $category['supplycat']; ?></a></li>
<?php
	}
?>
                </ul>
            
<?php
	$sidebar = new Sidebar();

	$query = "SELECT accountType FROM user where userID='$_SESSION[userID]'";
	$get_accType = mysqli_query($connection,$query);
	$accType = mysqli_fetch_assoc($get_accType);

	if($accType['accountType'] == "admin"){
		$sidebar->manage_admin_div();
		$sidebar->transaction_admin_div();
	}
	else{
		$sidebar->manage_user_div();
		$sidebar->transaction_user_div();
	}
	
?>
			</nav>
			<div id="center" class="column">