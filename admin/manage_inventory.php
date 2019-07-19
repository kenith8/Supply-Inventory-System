<?php
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

	$query = "SELECT * FROM category";
	$getCat = mysqli_query($connection, $query);
	$getCat2 = mysqli_query($connection, $query);

	if(isset($_POST['add'])){
		if(!(empty($_POST['supplycat']))){
			$supplycat = $_POST['supplycat'];

			$query = "SELECT supplycat FROM category WHERE supplycat = '$supplycat'";
			$result = mysqli_query($connection, $query);

			$supName = mysqli_fetch_assoc($result);

			if($supName['supplycat'] == $supplycat){
				echo "This input is already in Supply Category.";
			}
			else{
				$query1 = "INSERT INTO category (supplycat) VALUES ('$supplycat')";
				$result1 = mysqli_query($connection, $query1);

				HEADER("location:controller.php?manage_inventory");
			}
		}
		else if(!(empty($_POST['cat_ID']) && empty($_POST['sub_ID']) && empty($_POST['item_name']) && empty($_POST['image']) || empty($_POST['qty']))){

			$query = "SELECT item_name FROM item WHERE item_name = '$_POST[item_name]' AND catID = '$_POST[cat_ID]'";
			$result = mysqli_query($connection, $query);

			if(mysqli_num_rows($result) == null){
				$query = "INSERT INTO item (catID,subcatID,item_name,qty) VALUES ('$_POST[cat_ID]','$_POST[sub_ID]','$_POST[item_name]','$_POST[qty]')";
				$result = mysqli_query($connection, $query);

				$query1 = "SELECT itemID FROM item WHERE catID = '$_POST[cat_ID]' AND subcatID = '$_POST[sub_ID]' AND item_name = '$_POST[item_name]'";
				$result1 = mysqli_query($connection, $query1);

				$get_id = mysqli_fetch_assoc($result1);
				$itemID = $get_id['itemID'];

				$image_item_dir = "F:/Wamp/wamp64/www/InventorySystem/images/items/"; //UPLOADED ITEM IMAGE DIRECTORY
				$image = basename($_FILES['image']['name']); //GET THE IMAGE FILE NAME
				$imageType = pathinfo($image, PATHINFO_EXTENSION); //RETURN FILE EXTENSION
				$image_file = $itemID.".".$imageType; //RENAME FILE NAME WITH ITEM ID
				$move_path = $image_item_dir.$image_file; //DIRECTORY AND FILE DISTINATION TO BE UPLOADED

				if(file_exists($_FILES['image']['tmp_name'])){ //tmp_name is a temp server location
			        if($imageType == "jpeg" || $imageType == "jpg" || $imageType == "png") { //DETERMINE FILE TYPE
			            if($_FILES['image']['size'] < 500000) { //DETERMINE FILE SIZE MAXIMUM OF 5MB
			                move_uploaded_file($_FILES['image']['tmp_name'], $move_path); //UPLOAD FUNCTION
			                $query3 = "UPDATE item set img_no = '$itemID' WHERE itemID ='$itemID'";
    						$result3 = mysqli_query($connection,$query3);
			            } else {
			                $uploadOK = false;
			                $query = "DELETE FROM item WHERE itemID = '$itemID'";
			                $result = mysqli_query($connection, $query);
			                $error = "<script type='text/javascript'>";
							$error .= "window.onload = function(){";
							$error .= "alert('Wrong Size. Max size Allowed : 5MB');";
							$error .= "location = 'controller.php?manage_inventory';}";
							$error .= "</script>";
							echo $error;
			            }
			        } else {
			            $uploadOK = false;
			            $query = "DELETE FROM item WHERE itemID = '$itemID'";
			            $result = mysqli_query($connection, $query);
			            $error = "<script type='text/javascript'>";
						$error .= "window.onload = function(){";
						$error .= "alert('Wrong Format. Choose another format');";
						$error .= "location = 'controller.php?manage_inventory';}";
						$error .= "</script>";
						echo $error;
			        }
			    }
			    else {
			        $uploadOK = false;
			        $query = "DELETE FROM item WHERE itemID = '$itemID'";
			        $result = mysqli_query($connection, $query);
			        $error = "<script type='text/javascript'>";
					$error .= "window.onload = function(){";
					$error .= "alert('Something Went Wrong ! File not uploaded . Try Again !');";
					$error .= "location = 'controller.php?manage_inventory';}";
					$error .= "</script>";
					echo $error;
			    }
			    
				//HEADER("location:controller.php?manage_inventory");
			}
			else{
				while($item = mysqli_fetch_assoc($result)){
					echo $item['item_name']." is already in the inventory.";
				}
			}
		}
		else{
			if(isset($_POST['item_name']) || isset($_POST['image']) || isset($_POST['qty'])){
				if(empty($_POST['item_name'])){
				echo "Item name is empty"."<br/>";
				}
				if(empty($_POST['image'])){
					echo "Image is empty"."<br/>";
				}
				if(empty($_POST['qty'])){
					echo "Item quantity is empty";
				}
			}
			if(isset($_POST['supplycat'])){
				if(empty($_POST['supplycat'])){
					echo "Supply Category is empty";
				}
			}
		}
	}
	if(isset($_POST['remove'])){
		if(!empty($_POST['catID'])){
			$catID = $_POST['catID'];

			$query1 = "SELECT supplycat FROM category WHERE catID = '$catID'";
			$result1 = mysqli_query($connection, $query1);
			$catName = mysqli_fetch_assoc($result1);

			$message = $catName['supplycat']." has been removed on Supply Category";

			$query = "DELETE FROM category WHERE catID = '$catID'";
			$result = mysqli_query($connection, $query);

			HEADER("location:controller.php?manage_inventory");
		}
		else{
			echo "No selected data. :(";
		}
	}
?>
				<div id="manage-inventory-wrapper">
					<div id="add-category-form-wrapper">
						<div id="add-category-header">
							<h3>CREATE SUPPLY CATEGORY</h3>
						</div>
						<div id="add-category-form">
							<form action="controller.php?manage_inventory" method="post">
								<label>Category Name:</label>
								<input type="text" name="supplycat">
								<input type="submit" name="add" value="Add">
							</form>
						</div>
					</div>
					<div id="remove-category-form-wrapper">
						<div id="remove-category-header">
							<h3>REMOVE SUPPLY CATEGORY</h3>
						</div>
						<div id="search-remove-category-form">
							<form action="controller.php?manage_inventory" method="post">
								<label>Category Name:</label>
								<select name="catID">
									<option></option>
								<?php
									$stopat = 1;
									while($category = mysqli_fetch_assoc($getCat)){
										if($stopat > 2){
											echo "<option value=\"$category[catID]\">$category[supplycat]</option>";
										}
										else{
											$stopat++;
										}
									}
								?>
								</select>
								<input type="submit" name="remove" value="Remove">
							</form>
						</div>
					</div>
					<div id="add-item-form-wrapper">
						<div id="add-item-header">
							<h3>ADD NEW ITEM</h3>
						</div>
						<div id="add-item-form">
							<form action="controller.php?manage_inventory" method="post" enctype="multipart/form-data">
								<label>Category:</label>
								<select name="cat_ID" onchange="location = this.value">
								<?php
									if(isset($_GET['cat'])){
										$query = "SELECT * FROM category WHERE catID = '$_GET[cat]'";
										$getCat = mysqli_query($connection, $query);
										$category = mysqli_fetch_assoc($getCat);
										echo "<option value=\"$category[catID]\">$category[supplycat]</option>";
										$query_sub = "SELECT * FROM subcat WHERE catID = '$_GET[cat]'";
										$getsub = mysqli_query($connection, $query_sub);
									}
									else{
										echo "<option></option>";
									}
									while($category = mysqli_fetch_assoc($getCat2)){
										echo "<option value=\"controller.php?manage_inventory&cat=$category[catID]\">$category[supplycat]</option>";
									}
								?>
								</select>
								<label>Sub-Category:</label>
								<?php
									$select = "<select name=\"sub_ID\">";
									if(isset($_GET['cat'])){
										$getCat = mysqli_query($connection, $query);
										
										while($sub_cat = mysqli_fetch_assoc($getsub)){
										$select .= "<option value=\"$sub_cat[subcatID]\">$sub_cat[subcatName]</option>";
										}
										
									}
									$select .= "</select>";
									echo $select;
								?>
								<label>Item Name:</label>
								<input type="text" name="item_name">
								<label>Image:</label>
								<input type="file" name="image">
								<label>Quantity:</label>
								<input type="text" name="qty">
								<input type="submit" name="add" value="Add">
							</form>
						</div>
					</div>
					<div id="filter-wrapper">
						<div id="subcatdropdrown">
							<select onchange="location = this.value">
								<?php 
									if(isset($_GET['value'])){
										$value = $_GET['value'];
										$select = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$value'");
										$selectquery = mysqli_fetch_array($select);
										$results = mysqli_query($connection, "SELECT * FROM subcat");
								?>
										<option value="controller.php?manage_inventory&value=<?php echo $value;?>" select="selected"><?php echo $selectquery['subcatName']?></option>
									<?php
										while($row = mysqli_fetch_array($results)){
									?>
											<option value="controller.php?manage_inventory&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
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
											<option value="controller.php?manage_inventory&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
									<?php
											}
										}
									?>
							</select>
						</div>
						<div id="clear">
							<form action="controller.php?manage_inventory" method="post">
								<input type="submit" name="clear" value="Clear Filter">
							</form>
						</div>
						<div id="search">
							<form action="controller.php?manage_inventory" method="post">
								<input id="textfield" type="text" name="valueToSearch" placeholder="Item Name or Item I.D">
								<input id="searchbtn" type="submit" name="search" value="Search">
							</form>
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
								            	<td><a href="controller.php?admin_home&get=<?php echo $row['itemID']; ?>">Add</a></td>
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
								            	<td><a href="controller.php?admin_home&get=<?php echo $row['itemID']; ?>">Add</a></td>
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
											}
											else{
												mysqli_query($connection,"INSERT INTO get_item (userID,itemID)VALUES ('$_SESSION[userID]','$getID')");
											}
										}
									}
								?>
							</table>
						</div>
						<?php
							if(isset($_GET['value']) || isset($_POST['search'])){
								echo "<div></div>";
							}
							else{
								echo "<div id=\"table-footer\"></div>";
							}
						?>
					</div>
				</div><!-- manage-inventory-wrapper clossing tag -->
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->