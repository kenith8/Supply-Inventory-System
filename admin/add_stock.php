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

	if(isset($_POST['add'])){
		$message = "";
		$redirect = "";
		if(empty($_POST['ris'])){
			$message = "Please Input ICS NO. / RIS NO. FIRST";
			$redirect = "controller.php?add_stock";
		}
		else{
			$checkitemquery = mysqli_query($connection,"SELECT * FROM add_stock where userID ='$_SESSION[userID]'");
			if(mysqli_num_rows($checkitemquery) <= 0){
				$message = "ERROR: Empty Table";
				$redirect = "controller.php?add_stock";
			}
			else{
				$addquery = mysqli_query($connection,"SELECT * FROM add_stock where userID ='$_SESSION[userID]'");

				$qty = $_POST['itemqty'];
				$qty_len = count($qty);

				for($x = 0; $x < $qty_len; $x++){
					$row = mysqli_fetch_array($addquery);
					$addstockidquery = mysqli_query($connection,"SELECT * FROM item where itemID ='$row[itemID]'");
					$addstockidfetch = mysqli_fetch_array($addstockidquery);

					$qtyupdate = $addstockidfetch['qty'] + $qty[$x];
					
					mysqli_query($connection,"UPDATE item SET qty='$qtyupdate' where itemID='$addstockidfetch[itemID]'");
					
					$query = "INSERT INTO item_stock(itemID, risNO, qty, date_in) VALUES('$row[itemID]','$_POST[ris]','$qty[$x]',NOW())";
					$result = mysqli_query($connection, $query);
					
					$query1 = "INSERT INTO `in_inventory` (`risNO`, `userID`, `itemID`, `qty`, `date_in`) VALUES ('$_POST[ris]','$_SESSION[userID]','$row[itemID]','$qty[$x]',NOW())";
					$result1 = mysqli_query($connection, $query1);
				}
				
				mysqli_query($connection,"DELETE FROM add_stock where userID='$_SESSION[userID]'");
				
				$message = "Item/s added successfully.";
				$redirect = "controller.php?manage_inventory&item";
			}
		}
		echo "<script type='text/javascript'>
				onload = function(){
				alert('$message');
				location = '$redirect';}
			</script>";
	}
	if(isset($_GET['discard'])){
		$itemID = $_GET['discard'];
		$query = "DELETE FROM add_stock where itemID='$itemID' AND userID='$_SESSION[userID]'";
		$discard = mysqli_query($connection, $query);
		header("location:controller.php?add_stock");
	}
	if(isset($_GET['discardAll'])){
		$query = "DELETE FROM add_stock where userID='$_SESSION[userID]'";
		$discard = mysqli_query($connection, $query);
		header("location:controller.php?add_stock");
	}
?>
				<div id = "additem-wrapper">
					<div id="add-stock-header-wrapper">
						<div class="container" id="back-addstock-button-wrapper">
							<button type="button" class="btn btn-info" onclick="location.href='controller.php?manage_inventory&item';">Back</button>
						</div>
						<div class="container" id="additemphp">
							<h4>ADD STOCK LIST</h4>
						</div>
					</div>
				  	<div id="admin-function" style="width: 100%;height: 800px;">
						<div id="table-wrapper">
							<div id="additemSubmit">
								<form class="form-inline" action="#" method="post">
									<table id="process-manager-table">
										<tr>
											<th>Item ID</th>
											<th>Category</th>
											<th>SubCategory</th>
											<th>Item Name</th>
											<th>Qty.</th>
											<th>
												<input type="button" class="btn btn-danger" onclick="discardAll()" value="Discard All">
											</th>
										</tr>
									<?php
										$addstock = mysqli_query($connection,"SELECT * from add_stock WHERE userID = '$_SESSION[userID]'");
										while($row2 = mysqli_fetch_array($addstock)){
											$addstock2 = $row2['itemID'];
											$results = mysqli_query($connection,"SELECT * FROM item where itemID = '$addstock2'");
											while ($row = mysqli_fetch_array($results)) { 
												$subcat3 = $row['subcatID'];
												$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID ='$subcat3'");
												$subcat2 = mysqli_fetch_array($subcat);
												$category3 = $row['catID'];
												$category = mysqli_query($connection, "SELECT * FROM category where catID ='$category3'");
												$category2 = mysqli_fetch_array($category);
												$quantity = $row['qty'];

									?>
										<tr style="border: 1px solid black;">
											<td><?php echo $row['itemID'];?></td>
											<td><?php echo $category2['supplycat'];?></td>
											<td><?php echo $subcat2['subcatName'];?></td>
											<td style="text-align: center"><?php echo $row['item_name'];?></td>
											<td>
												<select class="form-control mb-2 mr-sm-2" name="itemqty[]">
												<?php
													for($qty=1; $qty<=1000; $qty++){	
														echo "<option value='$qty'>$qty</option>";
													}
												?>
												</select>
											</td>
												<td>
													<input type="button" class="btn btn-danger" onclick="discard(<?php echo $row['itemID']; ?>)" value="Discard">
												</td>
											</tr>
									<?php
									 		}
										}
									?>
									</table>
									<label style="font:bold;">ICS/RIS NO.</label>
									<input class="form-control mb-2 mr-sm-2" type="text" name="ris">
									<button class="btn btn-info mb-2 mr-sm-2" type="submit" name="add">Add</button>
								</form>
							</div>	
						</div>
					</div>
				</div>
			</div> <!-- center clossing tag -->
	 	</div> <!-- sidebar-container closing tag -->

<script type="text/javascript">
	function discard(item_ID){
		var id = item_ID;
		if(confirm("Do you really want to discard?")){
			window.location.href = 'controller.php?add_stock&discard='+id+'';
			return true;
		}
	}
	function discardAll(){
		if(confirm("Do you really want to discard all?")){
			window.location.href = 'controller.php?add_stock&discardAll';
			return true;
		}
	}
</script>