<?php
	ob_start();
	include_once("dbconnection.php");
		
	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
		
	$query = mysqli_query($connection,"SELECT accountType FROM user WHERE userID='$_SESSION[userID]'");
	$result = mysqli_fetch_array($query);
	
	if($result['accountType']=="user"){
		header("location:controller.php?home");
	}
	
	$all = mysqli_query($connection,"SELECT * from item");

	while($row = mysqli_fetch_array($all)){
		$updateqty = mysqli_query($connection,"SELECT SUM(qty) as totalqty from item_stock where itemID='$row[itemID]'");
		$updateqtyfetch = mysqli_fetch_array($updateqty);
		mysqli_query($connection,"UPDATE item SET qty='$updateqtyfetch[totalqty]' WHERE itemID='$row[itemID]'");
	}

	$img_items_dir = 'D:/wamp64/www/InventorySystem/images/items/';
	$img_items_subdir = 'images/items/';
		
	if(isset($_GET['catID'])){
		$categoryid = $_GET['catID'];
		$categorynamequery = mysqli_query($connection,"SELECT * from category where catID='$categoryid'");
		$categorynamefetch = mysqli_fetch_array($categorynamequery);
?>
				<div id="admin_home-header-wrapper">
					<h2>
						<?php echo $categorynamefetch['supplycat'];?>
					</h2>
				</div>
				<div id="filter">
					<div id="subcatdropdrown">
						<select class="form-control mb-2 mr-sm-2" onchange="location = this.value">
					<?php 
						if(isset($_GET['value'])){
							$value = $_GET['value'];
							$select = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$value' AND catID='$_GET[catID]'");
							$selectquery = mysqli_fetch_array($select);
							$results = mysqli_query($connection, "SELECT * FROM subcat WHERE catID='$_GET[catID]'");
					?>
							<option value="controller.php?admin_home&catID=<?php echo $_GET['catID']; ?>value=<?php echo $value;?>" select="selected"><?php echo $selectquery['subcatName']?></option>
						<?php
							while($row = mysqli_fetch_array($results)){
						?>
							<option value="controller.php?admin_home&catID=<?php echo $_GET['catID']; ?>&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
					<?php 
							}
						}
						else{
							$value = 0;
							$results = mysqli_query($connection, "SELECT * FROM subcat WHERE catID='$_GET[catID]'");
					?>
							<option value="novalue" select="selected">Supply...</option>
						<?php	
							while($row = mysqli_fetch_array($results)){
						?>
							<option value="controller.php?admin_home&catID=<?php echo $_GET['catID']; ?>&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
					<?php
							}
						}
					?>
						</select>
					</div>
					<div id="search">
						<form class="form-inline" action="controller.php?admin_home&catID=<?php echo $categoryid; ?>" method="post">
							<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="item ID and Item Name">
							<button class="form-control mb-2 mr-sm-2" id="searchbtn" type="submit" name="search" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/search.png" style="background-color:#B3de81;height:30px;width: 30px; margin:0;">&nbspSearch&nbsp</button>
						</form>
					</div>
					<div id="getitem">
						<form class="form-inline" method="post">
							<button class="form-control mb-2 mr-sm-2" type="submit" name="add_to_cart" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/get.png" style="background-color:#B3de81;height:30px;width: 30px; margin:0;"> Get Item List</button>
						</form>
				<?php
					if(isset($_POST['add_to_cart'])){
						$checker = mysqli_query($connection,"SELECT userID FROM get_item where userID='$_SESSION[userID]'");
						if(mysqli_num_rows($checker)>0){
							header("location:controller.php?getitem");
						}
						else{
				?>
						<script type='text/javascript'>
							window.onload = function(){
								alert("Table is empty, no selected item yet.");
								location = "controller.php?admin_home&catID=<?php echo $categoryid; ?>";
							}
						</script>
				<?php
						}
					}
				?>
					</div>
					<div id="clear">
						<form class="form-inline" action="controller.php?admin_home&catID=<?php echo $categoryid; ?>" method="post">
							<button class="form-control mb-2 mr-sm-2" id="searchbtn" type="submit" name="clear" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/clear.png" style="background-color:#B3de81;height:30px;width: 30px; margin:0;"> Clear</button>
						</form>
					</div>
				</div>
		        <div id="admin-function">
					<div id="table-wrapper">
						<table id="process-manager-table">
							<thead>							
								<tr class="tableheader">
									<th>Item Name</th>
									<th>ICS/RIS</th>
									<th>Date</th>
									<th>Qty.</th>
									<th></th>
								</tr>
							</thead>
					<?php
						if(isset($_POST['search'])){
							$searchresult=$_POST['valueToSearch'];
							$results= mysqli_query($connection,"SELECT * FROM item WHERE CONCAT(itemID,item_name) LIKE '%".$searchresult."%' AND catID = '".$categoryid."'");
							if(mysqli_num_rows($results)==NULL){
					?>
							<script type='text/javascript'>
								window.onload = function(){
								alert("No DATA found");
								location = "controller.php?admin_home&catID=<?php echo $categoryid; ?>";
								}
							</script>

					<?php		
							}
						}
						else{
							$results = mysqli_query($connection,"SELECT * FROM item where catID='$categoryid'");
						}
						if($value<=0){
							while ($row = mysqli_fetch_array($results)) { 
								$subcat3 = $row['subcatID'];
								$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3' and catID='$categoryid'");
								$subcat2= mysqli_fetch_array($subcat);
								$category3 = $row['catID'];
								$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3' and catID='$categoryid'");
								$category2= mysqli_fetch_array($category);
								$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
								$fetchitemstock = mysqli_fetch_array($itemstock);
								if (file_exists($img_items_dir.$row['img_no'].'.jpg')){ 
								   $src = $img_items_subdir.$row['img_no'].'.jpg';
								}
								else if (file_exists($img_items_dir.$row['img_no'].'.jpeg')){ 
									$src = $img_items_subdir.$row['img_no'].'.jpeg';
								}
								else{
									$src = $img_items_subdir.$row['img_no'].'.png';
								}
					?>
							<tbody>
								<tr>
							       	<td style="text-align: center;"><a href="<?php echo $src;?>" data-lightbox="mygallery"><?php echo $row['item_name'];?></a></td>
							        <td></td>
									<td></td>
							        <td style="font-weight: bold;"><?php echo  $fetchitemstock['totalqty']; ?></td>
							        <td></td>
							<?php
								$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
								while($row2 = mysqli_fetch_array($query)){
									$date = date('F j, Y',strtotime($row2['date_in']));
							?>
									<tr id="datarow">
							            <td></td>
							            <td><?php echo $row2['risNO']; ?></td>
									    <td><?php echo $date; ?></td>
									   	<td><?php echo $row2['qty']; ?></td>
									    <td><button class="form-control mb-2 mr-sm-2" style="background-color:#B3de81; border:none;border-radius:4px;" onclick="window.location.href = 'controller.php?admin_home&catID=<?php echo $_GET['catID'];?>&get=<?php echo $row2['stockID'];?>';"><img src="images/icons/add.png" style="background-color:#B3de81;height:30px;width: 30px;"> Get</button></td>
								    </tr>
							<?php
								}
							?>
								</tr>
							</tbody>

					<?php	
							}
							if(isset($_GET['get'])){
								$getID = $_GET['get'];
								$catID = $_GET['catID'];
								$check = "SELECT * FROM get_item where stockID = '$getID' and userID='$_SESSION[userID]'";
								$checkquery = mysqli_query($connection,$check);
								$checkidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$getID'");
								$checkidfetch = mysqli_fetch_array($checkidquery);
								$item_query = "SELECT item_name FROM item WHERE itemID = '$checkidfetch[itemID]'";
								$item_result = mysqli_query($connection, $item_query);
								$item = mysqli_fetch_assoc($item_result);

								if(mysqli_num_rows($checkquery)>0){
									echo "<script type='text/javascript'>
										window.onload = function(){
											alert('You already add this $item[item_name] to the Get item list');
											location = 'controller.php?admin_home&catID=$catID';}
										</script>";
								}
								else{
									if($checkidfetch['qty']<=0){
										echo "<script type='text/javascript'>
											window.onload = function(){
												alert('Not enough Quantity');
												location = 'controller.php?admin_home&catID=$catID';}
											</script>";
									}
									else{
										$result = mysqli_query($connection,"INSERT INTO get_item (userID,stockID,itemID) VALUES ('$_SESSION[userID]','$getID','$checkidfetch[itemID]')");
										echo "<script type='text/javascript'>
											window.onload = function(){
												alert('$item[item_name] is added to Get Item List.');
												location = 'controller.php?admin_home&catID=$catID';}
											</script>";
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
								$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
								$fetchitemstock = mysqli_fetch_array($itemstock);
								if (file_exists($img_items_dir.$row['img_no'].'.jpg')){ 
								   $src = $img_items_subdir.$row['img_no'].'.jpg';
								}
								else if (file_exists($img_items_dir.$row['img_no'].'.jpeg')){ 
									$src = $img_items_subdir.$row['img_no'].'.jpeg';
								}
								else if (file_exists($img_items_dir.$row['img_no'].'.png')){ 
									$src = $img_items_subdir.$row['img_no'].'.png';
								}
								else{
									$src = $img_items_subdir.'noimage.png';
								}
					?>		
							<tbody>
								<tr>
							       	<td style="text-align: center;"><a href="<?php echo $src;?>" data-lightbox="mygallery"><?php echo $row['item_name'];?></a></td>
							       	<td></td>
									<td></td>
							        <td style="font-weight: bold;"><?php echo $fetchitemstock['totalqty'];?></td>
							        <td></td>
							<?php
								$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
								while($row2 = mysqli_fetch_array($query)){
									$date = date('F j, Y',strtotime($row2['date_in']));
							?>
									<tr id="datarow">
							          	<td></td>
							            <td><?php echo $row2['risNO']; ?></td>
									   	<td><?php echo $date; ?></td>
									    <td><?php echo $row2['qty']; ?></td>
									    <td><button class="form-control mb-2" style="background-color:#B3de81; border:none;border-radius:4px;" onclick="window.location.href = 'controller.php?admin_home&catID=<?php echo $_GET['catID'];?>&get=<?php echo $row2['stockID'];?>';"><img src="images/icons/add.png" style="background-color:#B3de81;height:30px;width: 30px;"> Get</button></td>
								    </tr>
							<?php
								}
							?>
								</tr>
							</tbody>
					<?php
							}
						}
					?>
						</table>
					</div>
				</div> <!-- admin-function closing tag -->                        
		 	</div>
		</div>
<?php
	}
	else{
?>
				<div id="admin_home-header-wrapper">
					<h2>
						All Inventory Supplies
					</h2>
				</div>
				<div id="filter">
					<div id="subcatdropdrown">
						<select class="form-control mb-2 mr-sm-2" onchange="location = this.value">
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
							<option value="novalue" select="selected">Supply...</option>
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
					<div id="search">
						<form class="form-inline" action="controller.php?admin_home" method="post">
							<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="item ID and Item Name">
							<button class="form-control mb-2 mr-sm-2" id="searchbtn" type="submit" name="search" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/search.png" style="background-color:#B3de81;height:30px;width: 30px;">&nbspSearch&nbsp</button>
						</form>
					</div>
					<div id="getitem">
						<form class="form-inline" method="post">
							<button class="form-control mb-2 mr-sm-2" type="submit" name="add_to_cart" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/get.png" style="background-color:#B3de81;height:30px;width: 30px; margin:0;"> Get Item List</button>
						</form>
				<?php
					if(isset($_POST['add_to_cart'])){
						$checker = mysqli_query($connection,"SELECT userID FROM get_item where userID='$_SESSION[userID]'");
						if(mysqli_num_rows($checker)>0){
							header("location:controller.php?getitem");
						}
						else{
							echo "<script type='text/javascript'>
								window.onload = function(){
									alert('Table is empty, no selected item yet.');
									location = 'controller.php?admin_home';}
								</script>";
						}
					}
				?>
					</div>
					<div id="clear">
						<form class="form-inline" action="controller.php?admin_home" method="post">
							<button class="form-control mb-2 mr-sm-2" type="submit" name="clear" style="background-color:#B3de81; border:none;border-radius:4px;"><img src="images/icons/clear.png" style="background-color:#B3de81;height:30px;width: 30px; margin:0;">&nbspClear</button>
						</form>
					</div>
				</div>
		         	<div id="admin-function">
						<div id="table-wrapper">
							<table class="gridtable" id="tableMain">
								<thead>							
									<tr class="tableheader">
										<th>Item Name</th>
										<th>ICS/RIS</th>
										<th>Date</th>
										<th>Qty.</th>
										<th></th>
									</tr>
								</thead>
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
									$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
									$fetchitemstock = mysqli_fetch_array($itemstock);
									if (file_exists($img_items_dir.$row['img_no'].'.jpg')){ 
									    $src = $img_items_subdir.$row['img_no'].'.jpg';
									}
									else if (file_exists($img_items_dir.$row['img_no'].'.jpeg')){ 
										$src = $img_items_subdir.$row['img_no'].'.jpeg';
									}
									else if (file_exists($img_items_dir.$row['img_no'].'.png')){ 
										$src = $img_items_subdir.$row['img_no'].'.png';
									}
									else{
										$src = $img_items_subdir.'noimage.png';
									}	
						?>
								<tbody>
									<tr id="breakrow">
							            <td style="text-align: center;"><a href="<?php echo $src; ?>" data-lightbox="mygallery"><?php echo $row['item_name'];?></a></td>
							            <td></td>
										<td></td>
							            <td style="font-weight: bold;"><?php echo $fetchitemstock['totalqty'];?></td>
							            <td></td>
								<?php
									$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
									while($row2 = mysqli_fetch_array($query)){
										$date = date('F j, Y',strtotime($row2['date_in']));
								?>
										<tr id="datarow">
							            	<td></td>
							            	<td><?php echo $row2['risNO'];?></td>
									        <td><?php echo  $date; ?></td>
									        <td><?php echo  $row2['qty']; ?></td>
									        <td><button class="form-control mb-2 mr-sm-2" style="background-color:#B3de81; border:none;border-radius:4px;" onclick="window.location.href = 'controller.php?admin_home&get=<?php echo $row2['stockID'];?>';"><img src="images/icons/add.png" style="background-color:#B3de81;height:30px;width: 30px;"> Get</button></td>
								        </tr>
								<?php
									}
								?>
									</tr>
								</tbody>
						<?php			
								}
								if(isset($_GET['get'])){
									$getID = $_GET['get'];
									$check = "SELECT * FROM get_item where stockID = '$getID' and userID='$_SESSION[userID]'";
									$checkquery = mysqli_query($connection,$check);
									$checkidquery = mysqli_query($connection,"SELECT * FROM item_stock where stockID='$getID'");
									$checkidfetch = mysqli_fetch_array($checkidquery);
									$item_query = "SELECT item_name FROM item WHERE itemID = '$checkidfetch[itemID]'";
									$item_result = mysqli_query($connection, $item_query);
									$item = mysqli_fetch_assoc($item_result);

									if(mysqli_num_rows($checkquery)>0){
										echo "<script type='text/javascript'>
											window.onload = function(){
												alert('You already add this $item[item_name] to the Get item list');
												location = 'controller.php?admin_home';}
											</script>";
									}
									else{
										if($checkidfetch['qty']<=0){
											echo "<script type='text/javascript'>
												window.onload = function(){
													alert('Not enough Quantity');
													location = 'controller.php?admin_home';}
												</script>";
										}
										else{
											$result = mysqli_query($connection,"INSERT INTO get_item (userID,stockID,itemID) VALUES ('$_SESSION[userID]','$getID','$checkidfetch[itemID]')");
											echo "<script type='text/javascript'>
												window.onload = function(){
													alert('$item[item_name] is added to Get Item List.');
													location = 'controller.php?admin_home';}
												</script>";
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
									$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
									$fetchitemstock = mysqli_fetch_array($itemstock);	
									if (file_exists($img_items_dir.$row['img_no'].'.jpg')){ 
									    $src = $img_items_subdir.$row['img_no'].'.jpg';
									}else if (file_exists($img_items_dir.$row['img_no'].'.jpeg')){ 
										$src = $img_items_subdir.$row['img_no'].'.jpeg';
									}else if (file_exists($img_items_dir.$row['img_no'].'.png')){ 
										$src = $img_items_subdir.$row['img_no'].'.png';
									}
									else{
										$src = $img_items_subdir.'noimage.png';
									}
						?>
								<tbody>
									<tr id="breakrow">
							            <td style="text-align: center;"><a href="<?php echo $src; ?>" data-lightbox="mygallery"><?php echo $row['item_name'];?></a></td>
							            <td></td>
										<td></td>
							            <td style="font-weight: bold;"><?php echo $fetchitemstock['totalqty'];?></td>
							            <td></td>
								<?php
									$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
									while($row2 = mysqli_fetch_array($query)){
										$date = date('F j, Y',strtotime($row2['date_in']));
								?>
										<tr id="datarow">
											<td></td>
											<td ><?php echo $row2['risNO'];?></td>
									        <td><?php echo  $date; ?></td>
									        <td><?php echo  $row2['qty']; ?></td>
									        <td><button class="form-control mb-2" style="background-color:#B3de81; border:none;border-radius:4px;" onclick="window.location.href = 'controller.php?admin_home&get=<?php echo $row2['stockID'];?>';"><img src="images/icons/add.png" style="background-color:#B3de81;height:30px;width: 30px;"> Get</button></td>
								        </tr>
								<?php
									}
								?>
									</tr>
								</tbody>
						<?php
								}
							}		
						?>
						</table>
					</div>
				</div> <!-- admin-function closing tag -->                        
		 	</div>
		 </div>
<?php
	}
?>
<script>
	$( document ).ready(function() {
		//$('.breakrow').click(function(){
		$('#tableMain').on('click','tr.breakrow',function(){
			$(this).nextUntil('tr.breakrow').slideToggle(200);
		});
	}); 
</script>

<?php
	mysqli_query($connection,"DELETE FROM item_stock where qty<=0");
?>