<?php
	ob_start();
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$result = mysqli_query($connection,"SELECT * FROM user WHERE userID='$_SESSION[userID]'");
	$user = mysqli_fetch_array($result);

?>
				<div id="getitem-header-wrapper">
					<div id="back-getitem-button-wrapper">
						<button type="button" class="btn btn-info" onclick="location.href='controller.php?admin_home';">Back</button>
					</div>
					<div id="getitem-header">
						<h2>GET ITEM LIST</h2>
					</div>
				</div>
				<div id="admin-function" style="width: 100%;height: 500px;">
					<div id="table-wrapper">
						<div id="getitemSubmit">
							<form class="form-inline" action="#" method="post">
								<table id="process-manager-table">
									<tr>
										<th>Item ID</th>
										<th>Category</th>
										<th>SubCategory</th>
										<th>ICS/RIS</th>
										<th>Item Name</th>
										<th>Qty.</th>
								<?php
									if($user["accountType"]=="admin"){
								?>
										<th>Distribute to:</th>
								<?php
									}
								?>
										<th>
											<input type="button" class="btn btn-danger" onclick="discardAll()" value="Discard All">
										</th>
									</tr>
							<?php
								$getitem = mysqli_query($connection,"SELECT * from get_item WHERE userID='$_SESSION[userID]'");
								while($row2= mysqli_fetch_array($getitem)){
									$getitem2 = $row2['itemID'];
									$results = mysqli_query($connection,"SELECT * FROM item where itemID='$getitem2'");
									while ($row = mysqli_fetch_array($results)) {
										$subcat3 = $row['subcatID'];
										$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
										$subcat2= mysqli_fetch_array($subcat);
										$category3 = $row['catID'];
										$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
										$category2= mysqli_fetch_array($category);
										$qty1 = mysqli_query($connection, "SELECT * FROM item_stock where itemID='$getitem2' and stockID='$row2[stockID]'");
										$qty2 = mysqli_fetch_array($qty1);
							?>
									<tr>
										<td><?php echo $row['itemID'];?></td>
										<td><?php echo $category2['supplycat'];?></td>
										<td><?php echo $subcat2['subcatName'];?></td>
										<td><?php echo $qty2['risNO']; ?></td>
										<td style="text-align: left;"><?php echo $row['item_name'];?></td>
										<td>
											<select class="form-control mb-2 mr-sm-2" name="itemqty[]">
									<?php
										for($qty=1;$qty<=$qty2['qty'];$qty++){	
											echo "<option value='$qty'>$qty</option>";
										}
									?>
											</select>
										</td>
									<?php
										if($user["accountType"]=="admin"){
									?>
										<td>
											<input class="form-control mb-2 mr-sm-2" type="text" name="distribute_to[]" id="distribute_to[]" required>
										</td>
									<?php
										}
									?>
										<td>
											<input type="button" class="btn btn-danger" onclick="discard(<?php echo $row2['stockID']; ?>)" value="Discard">
										</td>
									</tr>
							<?php
									}
								}
							?>
								</table>
<?php
	$date = date("Y/m/d");
	$userID = $user['userID'];
	$increment = 0;
	
	if($user['accountType'] == "admin"){
		$query_out_inv = "SELECT * FROM out_inventory WHERE userID = '$userID'";
		$result_out_inv = mysqli_query($connection, $query_out_inv);
		$userID_out_inv = mysqli_fetch_assoc($result_out_inv);
		
		if($userID_out_inv['userID']){
			$query = "SELECT * FROM out_inventory WHERE userID = '$userID' ORDER BY date_time DESC LIMIT 1";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_assoc($result);
	
			$index = 0;
			$char;
			$str="";
	
			for($x = strlen($row['icsNO']);$x >= 0;$x--){
				$char = substr($row['icsNO'],$x,1);
				if($char == '-'){
					$index=$x+1;
					break;
				}
			}
	
			$get_sub_string = $str.substr($row['icsNO'], $index);
			$increment = $get_sub_string + 1;
		}
	
		$outID = $date."-".$userID."-".$increment;
	}
	else{
		$query_out_inv = "SELECT * FROM user_out_inventory WHERE userID = '$userID'";
		$result_out_inv = mysqli_query($connection, $query_out_inv);
		$userID_out_inv = mysqli_fetch_assoc($result_out_inv);
		
		if($userID_out_inv['userID']){
			$query = "SELECT * FROM user_out_inventory WHERE userID = '$userID' ORDER BY date_time DESC LIMIT 1";
			$result = mysqli_query($connection, $query);
			$row = mysqli_fetch_assoc($result);
	
			$index = 0;
			$char;
			$str="";
	
			for($x = strlen($row['icsNO']);$x >= 0;$x--){
				$char = substr($row['icsNO'],$x,1);
				if($char == '-'){
					$index=$x+1;
					break;
				}
			}
	
			$get_sub_string = $str.substr($row['icsNO'], $index);
			$increment = $get_sub_string + 1;
		}
	
		$outID = $date."-".$userID."-".$increment;
	}
	
	
?>
								<br/>
								<button type="submit" name="checkout" style="background-color:#68a225; border:none;border-radius:4px;"><img src="images/icons/checkout.png" style="background-color:#68a225;height:30px;width: 30px; margin:0;" onclick="return confirm('Confirm Transaction?')"></button>
								<label>Out ID: <?php echo $outID; ?></label>
							</form>
						</div>
<?php
			
			if(isset($_POST['checkout'])){
				if($user["accountType"]=="user"){
					$checkitemquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
					if(mysqli_num_rows($checkitemquery)<=0){
						echo "<script type='text/javascript'>
						window.onload = function(){
						alert('ERROR: Empty Table');
						location = 'controller.php?getitem';}
						</script>";
					}
					else{
						$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
						
						$qty = $_POST['itemqty'];
						$none="NONE";
						$qty_len = count($qty);
						$today = date('Y/m/d H:i:s');

						for($x = 0; $x < $qty_len; $x++){
							$row = mysqli_fetch_array($checkoutquery);

							$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
							$getitemidfetch = mysqli_fetch_array($getitemidquery);

							$qtyupdate = $getitemidfetch['qty'] - $qty[$x];
							
							mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
							
							$query = "INSERT INTO `user_out_inventory` (`icsNO`, `ics_ris`, `userID`, `itemID`, `qty`, `dis_to`, `date_time`) VALUES ('$outID', '$getitemidfetch[risNO]', '$userID', '$row[itemID]', '$qty[$x]', '$none', '$today')";
							$result = mysqli_query($connection, $query);
						}
						
						mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
						
						echo "<script type='text/javascript'>
							alert('Get item successfully');
							window.onload = function(){
							location = 'controller.php?admin_home';}
						</script>";
					}
					
				}
				else{
					$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
					
					$qty = $_POST['itemqty'];
					$dis_to = $_POST['distribute_to'];
					$qty_len = count($qty);
					$today = date('Y/m/d H:i:s');

					for($x = 0; $x < $qty_len; $x++){
						$row = mysqli_fetch_array($checkoutquery);

						$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
						$getitemidfetch = mysqli_fetch_array($getitemidquery);

						$qtyupdate = $getitemidfetch['qty'] - $qty[$x];
						
						mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
						
						$query = "INSERT INTO `out_inventory` (`icsNO`, `ics_ris`, `userID`, `itemID`, `qty`, `dis_to`, `date_time`) VALUES ('$outID', '$getitemidfetch[risNO]', '$userID', '$row[itemID]', '$qty[$x]', '$dis_to[$x]', '$today')";
						$result = mysqli_query($connection, $query);
					}
					
					mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
					
					echo "<script type='text/javascript'>
						alert('Get item successfully');
						window.onload = function(){
						location = 'controller.php?admin_home';}
					</script>";
				}
			}
			
			if(isset($_GET['discard'])){
				$stockID = $_GET['discard'];
				$query = "DELETE FROM get_item where stockID = '$stockID' AND userID = '$_SESSION[userID]'";
				$discard = mysqli_query($connection, $query);
				header("location:controller.php?getitem");
			}
			if(isset($_GET['discardAll'])){
				$query = "DELETE FROM get_item where userID = '$_SESSION[userID]'";
				$discard = mysqli_query($connection, $query);
				header("location:controller.php?getitem");
			}
?>
					</div> <!-- table-wrapper closing tag -->
				</div>	<!-- admin-function closing tag -->
			</div>
		</div>

<script type="text/javascript">
	function discard(stock_ID){
		var id = stock_ID;
		if(confirm("Do you really want to discard?")){
			window.location.href = 'controller.php?getitem&discard='+id+'';
			return true;
		}
	}
	function discardAll(){
		if(confirm("Do really want to discard all?")){
			window.location.href = 'controller.php?getitem&discardAll';
			return true;
		}
	}
</script>