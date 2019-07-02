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
		$page = $sidebar->manage_header();
		$page .=$sidebar->admin_manage();
		$page .= $sidebar->manage_footer();
	}
	else{
		$page = $sidebar->manage_header();
		$page .= $sidebar->user_manage();
		$page .= $sidebar->manage_footer();
	}
	

	echo $page;
?>
				<a href="controller.php?admin_home"><h3 class="subject">Transactions</h3></a>
				<ul>
					
				</ul>
			</nav>
			<div id="center" class="column">