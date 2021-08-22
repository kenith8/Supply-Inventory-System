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
						<h2>GET ITEM LIST</h2>
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
										<th>ICS/RIS</th>
										<th>Item Name</th>
										<th>Qty.</th>
								<?php
									if($result["accountType"]=="admin"){
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
											<input type="button" class="btn btn-danger" onclick="discard(<?php echo $row2['stockID']; ?>)" value="Discard">
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
?>
				<button type="submit" name="checkout" style="background-color:#68a225; border:none;border-radius:4px;"><img src="images/icons/checkout.png" style="background-color:#68a225;height:30px;width: 30px; margin:0;" onclick="return confirm('Confirm Transaction?')"></button>
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
								$none="NONE";
								$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
								$query100 = mysqli_query($connection,"SELECT * FROM user_out_inventory ORDER BY date_out DESC LIMIT 1 ");
								$fetch100 = mysqli_fetch_array($query100);
								$index = 0;
								$char;
								$str="";
								for($x = strlen($fetch100['icsNO']);$x >= 0;$x--){
									$char = substr($fetch100['icsNO'],$x,1);
									if($char == '-'){
										$index=$x+1;
										break;
									}
								}
								$asd = $str.substr($fetch100['icsNO'], $index);
								if(Is_numeric($asd)){
									$num = (int)$asd;
									$num2 = $num + 1;
									foreach($_POST['itemqty'] as $finalqty){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										$out = date("Y/m/d")."-".$_SESSION['userID']."-".$num2;
										mysqli_query($connection,"INSERT INTO user_out_inventory(icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$out','$_SESSION[userID]','$row[itemID]','$finalqty','$none',NOW(),NOW())");
									
									}
									mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
									echo "<script type='text/javascript'>
										alert('SUCCESS KUHA');
										window.onload = function(){
										location = 'controller.php?home';}
									</script>";
								}else if($str=="-"){
									foreach($_POST['itemqty'] as $finalqty){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										$out = date("Y/m/d")."-".$_SESSION['userID']."0";
										mysqli_query($connection,"INSERT INTO user_out_inventory(icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$out','$_SESSION[userID]','$row[itemID]','$finalqty','$none',NOW(),NOW())");
									
									}
									mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
									echo "<script type='text/javascript'>
										alert('SUCCESS KUHA//ELSE IF');
										window.onload = function(){
										location = 'controller.php?home';}
									</script>";
								}else{
									$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
									foreach($_POST['itemqty'] as $finalqty){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										$out = date("Y/m/d")."-".$_SESSION['userID']."-0";
										mysqli_query($connection,"INSERT INTO user_out_inventory(icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$out','$_SESSION[userID]','$row[itemID]','$finalqty','$none',NOW(),NOW())");
									
									}
									mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
									echo "<script type='text/javascript'>
										alert('SUCCESS KUHA//ELSE');
										window.onload = function(){
										location = 'controller.php?home';}
									</script>";
								}
							
														
						}
				}else{
					$checkitemquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
					if(mysqli_num_rows($checkitemquery)<=0){
						
					}else{
						if(isset($_POST['distribute_to[]'])){
							
								echo "<script type='text/javascript'>
												window.onload = function(){
													alert('Please Input Textfield');
												}
									</script>";
					
						}else{
							$none="NONE";
							$checkoutquery = mysqli_query($connection,"SELECT * FROM get_item where userID='$_SESSION[userID]'");
							$query100 = mysqli_query($connection,"SELECT * FROM out_inventory ORDER BY date_out DESC LIMIT 1 ");
							$fetch100 = mysqli_fetch_array($query100);
							$index = 0;
							$char;
							$str="";
							for($x = strlen($fetch100['icsNO']);$x >= 0;$x--){
								$char = substr($fetch100['icsNO'],$x,1);
								if($char == '-'){
									$index=$x+1;
									break;
								}
							}
							$asd = $str.substr($fetch100['icsNO'], $index);
							if(Is_numeric($asd)){
									$num = (int)$asd;
									$num2 = $num + 1;
								foreach(array_combine($_POST['itemqty'],$_POST['distribute_to']) as $finalqty => $dis_to){
									if(mysqli_num_rows($checkoutquery)>0){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										$icsris = date("Y/m/d")."-".$_SESSION['userID']."-".$num2;
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										mysqli_query($connection,"INSERT INTO out_inventory (icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$icsris','$_SESSION[userID]','$row[itemID]','$finalqty','$dis_to',NOW(),NOW())");
									}
								}
								mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
									echo "<script type='text/javascript'>
											window.onload = function(){
												alert('FIRST!');
												location = 'controller.php?admin_home';
											}
											</script>";
							}else if($str=="-"){
								foreach(array_combine($_POST['itemqty'],$_POST['distribute_to']) as $finalqty => $dis_to){
									if(mysqli_num_rows($checkoutquery)>0){
										$row = mysqli_fetch_array($checkoutquery);
										$getitemidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$row[stockID]'");
										$getitemidfetch = mysqli_fetch_array($getitemidquery);
										$qtyupdate = $getitemidfetch['qty'] - $finalqty;
										$icsris = date("Y/m/d")."-".$_SESSION['userID']."0";
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										mysqli_query($connection,"INSERT INTO out_inventory (icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$icsris','$_SESSION[userID]','$row[itemID]','$finalqty','$dis_to',NOW(),NOW())");
									}
								}
								mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
								echo "<script type='text/javascript'>
										window.onload = function(){
											alert('SECOND!');
												location = 'controller.php?admin_home';
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
										$icsris = date("Y/m/d")."-".$_SESSION['userID']."-0";
										mysqli_query($connection,"UPDATE item_stock SET qty='$qtyupdate' where stockID='$getitemidfetch[stockID]'");
										mysqli_query($connection,"INSERT INTO out_inventory (icsNO,userID,itemID,qty,dis_to,date_out,time_out)VALUES('$icsris','$_SESSION[userID]','$row[itemID]','$finalqty','$dis_to',NOW(),NOW())");
									}
								}
									mysqli_query($connection,"DELETE FROM get_item where userID='$_SESSION[userID]'");
									echo "<script type='text/javascript'>
											window.onload = function(){
												alert('THIRD!');
													location = 'controller.php?admin_home';
											}
										  </script>";
							}

						}
				}
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