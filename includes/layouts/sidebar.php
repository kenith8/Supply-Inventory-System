<?php
	include_once("dbconnection.php");
	require_once("F:/Wamp/wamp64/www/InventorySystem/function.php");
	
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
?>
        <div id="sidebar-container">
            <nav id="left" class="column">
            	<div id="accordion">
            		<div class="card">
            			<div class="card-header">
            				<a href="controller.php?admin_home">
	                			<a class="card-link" data-toggle="collapse" href="#collapseOne">
	                				<h3 class="subject">
	                					Supply Category
	                				</h3>
	                			</a>
                			</a>
                		</div>
                		<div id="collapseOne" class="collapse" data-parent="#accordion">
                			<div class="card-body">
                				<ul class="orderlist">
				<?php
					$query = "SELECT accountType FROM user WHERE userID = '$_SESSION[userID]'";
					$result = mysqli_query($connection, $query);
					$getType = mysqli_fetch_assoc($result);

					if($getType['accountType'] == "user"){
						$default = "<li>";
                    	$default .= "<a class=\"btn\" href=\"controller.php?home\">All</a>";
                    	$default .= "</li>";
					}
					else{
						$default = "<li>";
                    	$default .= "<a class=\"btn\" href=\"controller.php?admin_home\">All</a>";
                    	$default .= "</li>";
					}

					echo $default;

					$query1 = "SELECT * FROM category";
					$result1 = mysqli_query($connection,$query1);
					while($category = mysqli_fetch_assoc($result1)){
				?>
                    				<li>
                    					<a class="btn" href="controller.php?admin_home&catID=<?php echo $category['catID']; ?>"><?php echo $category['supplycat']; ?>
                    					</a>
                    				</li>
                    			
				<?php
					}
				?>
                				</ul>
                			</div>
                		</div>
                	</div>
            
<?php
	$sidebar = new Sidebar();

	$query = "SELECT accountType FROM user where userID='$_SESSION[userID]'";
	$get_accType = mysqli_query($connection,$query);
	$accType = mysqli_fetch_assoc($get_accType);

	if($accType['accountType'] == "admin"){
		$sidebar->manage_admin_div();
		$sidebar->transaction_admin_div();
		$sidebar->graph_div();

	}
	else{
		$sidebar->manage_user_div();
		$sidebar->transaction_user_div();
		$sidebar->graph_div();
	}
	
?>
				</div> <!-- accordion div closing tag -->
			</nav>
			<div id="center" class="column">