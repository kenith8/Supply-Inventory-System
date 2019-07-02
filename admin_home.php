<?php
	ob_start();
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = "SELECT accountType FROM user WHERE userID = '$_SESSION[userID]'";
	$result = mysqli_query($connection, $query);
	$getType = mysqli_fetch_assoc($result);

	if($getType['accountType'] == "user"){
		HEADER("location:controller.php?home");
	}
?>
				<div id="filter">
					<div id="subcatdropdrown">
							<select onchange="location = this.value">
								<?php 
									if(isset($_GET['value'])){
										$value = $_GET['value'];
										$select = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$value'");
										$selectquery = mysqli_fetch_array($select);
										$results = mysqli_query($connection, "SELECT * FROM subcat");
								?>
										<option value="controller.php?admin_home&value=<?php echo $value;?>" select="selected"><?php echo $selectquery['subcatName']?></option>
								<?php
										while($row = mysqli_fetch_array($results)){
								?>
											<option value="controller.php?admin_home&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
								<?php 
										}
									}
									else{
										$value = 0;
										$results = mysqli_query($connection, "SELECT * FROM subcat");
								?>
										<option value="novalue" select="selected">Filter table</option>
								<?php	
										while($row = mysqli_fetch_array($results)){
								?>
											<option value="controller.php?admin_home&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
								<?php
										}
									}
								?>
							</select>
					</div>
					<div id="clear">
						<form action="controller.php?admin_home" method="post">
							<input type="submit" name="clear" value="Clear Filter">
						</form>
					</div>
					<div id="search">
						<form action="controller.php?admin_home" method="post">
							<input id="textfield" type="text" name="valueToSearch" placeholder="item ID and Item Name">
							<input id="searchbtn" type="submit" name="search" value="Search">
						</form>
					</div>
					<div id="getitem">
						<form method="post">
							<input type="submit" name="add_to_cart" value="Get Items">
						</form>
						<?php
							if(isset($_POST['add_to_cart'])){
								$checker = mysqli_query($connection,"SELECT userID FROM get_item where userID='$_SESSION[userID]'");
								if(mysqli_num_rows($checker)>0){
									header("location:controller.php?getitem");
								}else{
									echo "<script type='text/javascript'>
										window.onload = function(){
										alert('Empty Table. Get item first');
										location = 'controller.php?admin_home';}
										</script>";
								}
							}
						?>
					</div>
				</div>
	            <div id="admin-function">
	            	<div id="table-header"></div>
					<div id="table-wrapper">
						<table id="process-manager-table">
							<tr>
								<th>Item ID</th>
								<th>Item Name</th>
								<th>Qty.</th>
								<th colspan="2"></th>
							</tr>
							<?php
								if(isset($_POST['search'])){
									$searchresult=$_POST['valueToSearch'];
									$results= mysqli_query($connection,"SELECT * FROM item WHERE CONCAT(itemID,item_name) LIKE '%".$searchresult."%'");
										if(mysqli_num_rows($results)==NULL){
											echo "DATA NOT FOUND";
										}
								}
								else{
									$results = mysqli_query($connection,"SELECT * FROM item");
								}
								if($value<=0){
									while ($row = mysqli_fetch_array($results)) { 
										$subcat3 = $row['subcatID'];
										$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
										$subcat2= mysqli_fetch_array($subcat);
										$category3 = $row['catID'];
										$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
										$category2= mysqli_fetch_array($category);
							?>
									<tr style="border: 1px solid black;">
										<td><?php echo $row['itemID'];?></td>
						            	<td style="text-align: left;"><?php echo $row['item_name'];?></td>
						            	<td><?php echo  $row['qty']; ?></td>
						            	<td><a href="controller.php?admin_home&get=<?php echo $row['itemID']; ?>">Get</a> </td>
									</tr>
							<?php	
							 		}
							 		if(isset($_GET['get'])){
										$getID = $_GET['get'];
										$check = "SELECT * FROM get_item where itemID = '$getID'";
										$checkquery = mysqli_query($connection,$check);
										$checkidquery = mysqli_query($connection,"SELECT * FROM item where itemID='$getID'");
										$checkidfetch = mysqli_fetch_array($checkidquery);
										if(mysqli_num_rows($checkquery)>0){
											echo "<script type='text/javascript'>
													window.onload = function(){
													alert('You already add this Item!');
													location = 'controller.php?admin_home';}
													</script>";
										}else{
											if($checkidfetch['qty']<=0){
												echo "<script type='text/javascript'>
													window.onload = function(){
													alert('Not enough Quantity');
													location = 'controller.php?admin_home';}
													</script>";
											}else{
												mysqli_query($connection,"INSERT INTO get_item (userID,itemID) VALUES ('$_SESSION[userID]','$getID')");
											}
										}
									}
								}
								else{
									$results = mysqli_query($connection, "SELECT * FROM item where subcatID='$value'");
									while ($row = mysqli_fetch_array($results)) { 
										$subcat3 = $row['subcatID'];
										$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
										$subcat2= mysqli_fetch_array($subcat);
										$category3 = $row['catID'];
										$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
										$category2= mysqli_fetch_array($category);
							?>
									<tr style="border: 1px solid black;">
										<td><?php echo $row['itemID'];?></td>
						            	<td style="text-align: left;"><?php echo $row['item_name'];?>
						            	<td><?php echo  $row['qty']; ?></td>
						            	<td><a href="controller.php?admin_home&get=<?php echo $row['itemID']; ?>">Get</a> </td>
									</tr>
							<?php
									}
									if(isset($_GET['get'])){
										$getID = $_GET['get'];
										$check = "SELECT * FROM get_item where itemID = '$getID'";
										$checkquery = mysqli_query($connection,$check);
										if(mysqli_num_rows($checkquery)>0){
											echo "<script type='text/javascript'>
													window.onload = function(){
													alert('You already add this Item!');
													location = 'controller.php?admin_home';}
													</script>";
										}else{
											mysqli_query($connection,"INSERT INTO get_item (userID,itemID)VALUES ('$_SESSION[userID]','$getID')");
										}
									}
								}
							?>

						</table>
					</div>
					<div id="table-footer"></div>
				</div>
	 		</div> <!-- center clossing tag -->
	 	</div> <!-- sidebar-container closing tag -->