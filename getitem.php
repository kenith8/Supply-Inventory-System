<?php
	ob_start();
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = mysqli_query($connection,"SELECT * FROM user WHERE userID='$_SESSION[userID]'");
	$result = mysqli_fetch_array($query);

?>
				<div id="getitem-header-wrapper">
					<div id="back-getitem-button-wrapper">
						<button type="button" class="btn btn-info" onclick="location.href='controller.php?admin_home';">Back</button>
					</div>
					<div id="getitem-header">
						<h2>GET LIST</h2>
					</div>
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
								<?php
									if($result["accountType"]=="admin"){
								?>
										<th>Distribute to:</th>
								<?php
									}
								?>
										<th><button  class="btn btn-danger" onclick="discardAll()">Discard All</button></th>
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
										<td style="text-align: left;"><?php echo $row['item_name'];?></td>
										<td>
											<select name="itemqty[]">
									<?php
										for($qty=1;$qty<=$qty2['qty'];$qty++){	
											echo "<option value='$qty'>$qty</option>";
										}
									?>
											</select>
										</td>
									<?php
										if($result["accountType"]=="admin"){
									?>
										<td>
											<input type="text" name="distribute_to[]" id="distribute_to[]">
										</td>
									<?php
										}
									?>
										<td>
											<button class="btn btn-danger" onclick="discard(<?php echo $row['itemID']; ?>)">
											<img src="images/icons/discard.png" style="height:30px;width: 30px;">
											</button>
										</td>
									</tr>
							<?php
									}
								}
							?>
								</table>
							
<?php
				$query = mysqli_query($connection,"SELECT * FROM user where userID='$_SESSION[userID]'");
				$result = mysqli_fetch_array($query);
				if($result['accountType']=="admin"){
?>
					<label style="font:bold;">ICS NO.</label>
					<input type="text" name="ics" onkeyup="var start = this.selectionStart;
					var end = this.selectionEnd;
					this.value = this.value.toUpperCase();
					this.setSelectionRange(start, end);">
<?php
				}
?>
				<button type="submit" name="checkout" style="background-color:#68a225; border:none;border-radius:4px;"><img src="images/icons/checkout.png" style="background-color:#68a225;height:30px;width: 30px; margin:0;" onclick="return confirm('Confirm checkout:')"></button>
							</form>
						</div>
<?php
			$query = mysqli_query($connection, "SELECT * FROM user WHERE userID='$_SESSION[userID]'");
			$result = mysqli_fetch_array($query);
			if(isset($_POST['checkout'])){
				if($result["accountType"]=="user"){
						$checkitemquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
						if(mysqli_num_rows($checkitemquery)<=0){
							echo "<script type='text/javascript'>
							window.onload = function(){
							alert('ERROR: Empty Table');
							location = 'controller.php?getitem';}
							</script>";
						}else{
							$num=0;
							$none="NONE";
							$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
							$query100 = mysqli_query($connection,"SELECT * FROM user_out_inventory ORDER BY date_out DESC LIMIT 1");
							$fetch100 = mysqli_fetch_array($query100);
							$increment = preg_replace('/\D/', '', $fetch100['icsNO']);
							$num=(int)$increment;

							if($num<=0 || $num==NULL){
								$num=0;
							}else{
								$num = $num + 1;
							}
							
								foreach($_POST['itemqty'] as $finalqty){
									if(mysqli_num_rows($checkoutquery)>0){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										$out = "OUT-USER-".$num."-".$result['name'];
										mysqli_query($connection,"INSERT INTO user_out_inventory (icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$out','$_SESSION[userID]','$row[itemID]','$finalqty',$none,NOW(),NOW())");
									}
								}
								mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
								echo "<script type='text/javascript'>
										window.onload = function(){
											alert('Check Out Successfully!');
											location = 'controller.php?admin_home';
											}
									</script>";
						}		
				}else{
					if(empty($_POST['ics'])){
						echo "<script type='text/javascript'>
						window.onload = function(){
							alert('Please Input ICS NO. FIRST');
							location = 'controller.php?getitem';}
							</script>";

					}else{
						$checkduplicatequery = mysqli_query($connection,"SELECT * FROM out_inventory where icsNO='$_POST[ics]'");
						if(mysqli_num_rows($checkduplicatequery)>0){
							echo "<script type='text/javascript'>
							window.onload = function(){
									alert('ICS NO. IS ALREADY USED');
									location = 'controller.php?getitem';
							}
							</script>";
						}else{
							$checkitemquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
							if(mysqli_num_rows($checkitemquery)<=0){
								echo "<script type='text/javascript'>
										window.onload = function(){
											alert('ERROR: Empty Table');
											location = 'controller.php?getitem';
										}
											</script>";
							}else{
								$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
								foreach(array_combine($_POST['itemqty'],$_POST['distribute_to']) as $finalqty => $dis_to){
									if(mysqli_num_rows($checkoutquery)>0){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										mysqli_query($connection,"INSERT INTO out_inventory (icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$_POST[ics]','$_SESSION[userID]','$row[itemID]','$finalqty','$dis_to',NOW(),NOW())");
									}
								}
								mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
								echo "<script type='text/javascript'>
										window.onload = function(){
											alert('Check Out Successfully!');
											location = 'controller.php?admin_home';
										}
										</script>";
								}
							}
					}
				}
			}
							if(isset($_GET['discard'])){
								$itemID = $_GET['discard'];
								$query = "DELETE FROM get_item where itemID = '$itemID'";
								$discard = mysqli_query($connection, $query);
							}
							if(isset($_GET['discardAll'])){
								$query = "DELETE FROM get_item where userID = '$_SESSION[userID]'";
								$discard = mysqli_query($connection, $query);
							}
?>
					</div> <!-- table-wrapper closing tag -->
				</div>	<!-- admin-function closing tag -->
			</div>
		</div>

<script type="text/javascript">
	function discard(item_ID){
		var id = item_ID;
		var c = confirm("Do really want to discard?");
		if(c == true){
			location.href = "controller.php?getitem&discard="+id;
		}
	}
	function discardAll(){
		var c = confirm("Do really want to discard all?");
		if(c == true){
			location.href = "controller.php?getitem&discardAll";
		}
	}
</script>