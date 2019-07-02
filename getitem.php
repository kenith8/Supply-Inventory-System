<?php
	ob_start();
	include_once("dbconnection.php");
	
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
?>
				<div id = "getitem-wrapper">
					<div id="getitemphp">
						<h1>GET ITEMS</h1>
					</div>
				
				  	<div id="admin-function" style="width: 100%;height: 500px;">
						<div id="table-wrapper">
							<div id="getitemSubmit">
								<form action="#" method="post">
									<table id="process-manager-table">
										<tr>
											<th>Item ID</th>
											<th>Category</th>
											<th>SubCategory</th>
											<th>Item Name</th>
											<th>Qty.</th>
											<th></th>
										</tr>
										<?php
											$getitem = mysqli_query($connection,"SELECT * from get_item WHERE userID = '$_SESSION[userID]'");
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
													$quantity = $row['qty'];

										?>
											<tr style="border: 1px solid black;">
												<td><?php echo $row['itemID'];?></td>
												<td><?php echo $category2['supplycat'];?></td>
												<td><?php echo $subcat2['subcatName'];?></td>
												<td style="text-align: left;border: 1px solid black"><?php echo $row['item_name'];?></td>
												<td style="border: 1px solid black;">
													<select name="itemqty[]">
														<?php
															for($qty=1;$qty<=$row['qty'];$qty++){	
																echo "<option value='$qty'>$qty</option>";
															}
														?>
													</select>
												</td>
												<td><a href="controller.php?getitem&discard=<?php echo $row2['itemID']; ?>">Discard</a> </td>
											</tr>
									<?php
									 		}
										}
									?>
									</table>
							</div>
										<label style="font:bold;">ICS NO.</label>
										<input type="text" name="ics">
										<input type="submit" name="checkout" value="Check Out">
								</form>
							<?php
								if(isset($_POST['checkout'])){
									if(empty($_POST['ics'])){
										
											echo "<script type='text/javascript'>
												window.onload = function(){
												alert('Please Input ICS NO. FIRST');
												location = 'controller.php?getitem';}
												</script>";
										
									}else{
										$checkitemquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
										if(mysqli_num_rows($checkitemquery)<=0){
											echo "<script type='text/javascript'>
												window.onload = function(){
												alert('ERROR: Empty Table');
												location = 'controller.php?getitem';}
												</script>";
										}else{
											$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
											foreach($_POST['itemqty'] as $finalqty){
												if(mysqli_num_rows($checkoutquery)>0){
													$row = mysqli_fetch_array($checkoutquery);
													$getitemidquery = mysqli_query($connection,"SELECT * FROM item where itemID='$row[itemID]'");
													$getitemidfetch = mysqli_fetch_array($getitemidquery);
													$qtyupdate = $getitemidfetch['qty'] - $finalqty;
													mysqli_query($connection,"UPDATE item SET qty='$qtyupdate' where itemID='$getitemidfetch[itemID]'");
													mysqli_query($connection,"INSERT INTO out_inventory (icsNO,userID,itemID,qty,date_out,time_out)VALUES('$_POST[ics]','$_SESSION[userID]','$row[itemID]','$finalqty',NOW(),NOW())");
												}
											}
											mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
											echo "<script type='text/javascript'>
												window.onload = function(){
												alert('Check Out Successfully!');
												location = 'controller.php?admin_home';}
												</script>";
										}
									}
								}
								if(isset($_GET['discard'])){
									mysqli_query($connection,"DELETE FROM get_item where itemID='$_GET[discard]'");
									header("location:controller.php?getitem");
								}
							?>
						</div>
					</div>
				</div>
			</div> <!-- center clossing tag -->
	 	</div> <!-- sidebar-container closing tag -->