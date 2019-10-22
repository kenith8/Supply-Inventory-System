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

			$query_select = "SELECT * FROM category WHERE supplycat = '$supplycat'";
			$result = mysqli_query($connection, $query);

			$sup = mysqli_fetch_assoc($result);

			if($sup['supplycat'] == $supplycat){
				$message = "<script type='text/javascript'>";
	    		$message .= "window.onload = function(){";
	    		$message .= "alert('$sup[supplycat] is already on Category.');";
	    		$message .= "location = 'controller.php?manage_inventory&cr8_cat';}";
	    		$message .= "</script>";
	    		echo $message;
			}
			else{
				
				if(!(empty($_POST['subcat']))){
					$sub = $_POST['subcat'];
				}
				else{
					$sub = "NONE";
				}
				$query = "INSERT INTO category (supplycat) VALUES ('$supplycat')";
				$result = mysqli_query($connection, $query);

				$query1 = $query_select;
				$result1 = mysqli_query($connection, $query1);

				$supID = mysqli_fetch_assoc($result1);

				$query2 = "INSERT INTO subcat (catID, subcatName) VALUES ('$supID[catID]', '$sub')";
				$result2 = mysqli_query($connection, $query2);

				$message = "<script type='text/javascript'>";
	    		$message .= "window.onload = function(){";
	    		$message .= "alert('$supplycat has successfully added to Category.');";
	    		$message .= "location = 'controller.php?manage_inventory&cr8_cat';}";
	    		$message .= "</script>";
	    		echo $message;
			}

		}
		else if(!(empty($_POST['cat_ID']) || empty($_POST['sub_ID']) || empty($_POST['item_name']))){
			$message = "";
			$message_ = "";

			$query = "SELECT item_name FROM item WHERE item_name = '$_POST[item_name]' AND catID = '$_POST[cat_ID]'";
			$result = mysqli_query($connection, $query);

			if(mysqli_num_rows($result) == null){
				$query = "INSERT INTO item (catID,subcatID,item_name) VALUES ('$_POST[cat_ID]','$_POST[sub_ID]','$_POST[item_name]')";
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
    				
			    			$message_ = "alert('$_POST[item_name] has successfully added to Inventory.');";
			            } else {
			                $query = "DELETE FROM item WHERE itemID = '$itemID'";
			                $result = mysqli_query($connection, $query);

							$message_ = "alert('Wrong Size. Max size Allowed: 5MB');";
			            }
			        } else {
			            $query = "DELETE FROM item WHERE itemID = '$itemID'";
			            $result = mysqli_query($connection, $query);

						$message_ = "alert('Wrong Format. Choose another format');";
			        }
			    }
			    else {
			        $query = "DELETE FROM item WHERE itemID = '$itemID'";
			        $result = mysqli_query($connection, $query);

					$message_ = "alert('Something Went Wrong ! File not uploaded . Try Again !');";
			    }
			    
			}
			else{
				while($item = mysqli_fetch_assoc($result)){
					$message_ = "alert('$item[item_name] is already in the inventory.');";
				}
			}
			$message .= "<script type='text/javascript'>";
			$message .= "window.onload = function(){";
			$message .= $message_;
			$message .= "location = 'controller.php?manage_inventory&add_item';}";
			$message .= "</script>";
					
			echo $message;
		}
		else{
			if(isset($_POST['cat_ID']) || isset($_POST['sub_ID']) || isset($_POST['item_name'])){
				$message = "";
				if(empty($_POST['cat_ID'])){
					$message .= 'Category is empty'.'\n';
				}
				if(empty($_POST['sub_ID'])){
					$message .= 'Sub-Category is empty'.'\n';
				}
				if(empty($_POST['item_name'])){
					$message .= 'Item name is empty';
				}
				$error = "<script type='text/javascript'>";
				$error .= "window.onload = function(){";
				$error .= "alert('$message');";
				$error .= "location = 'controller.php?manage_inventory&add_item';}";
				$error .= "</script>";

				echo $error;
			}
			if(isset($_POST['supplycat'])){
				if(empty($_POST['supplycat'])){
					$error = "<script type='text/javascript'>";
					$error .= "window.onload = function(){";
					$error .= "alert('Supply Category is empty');";
					$error .= "location = 'controller.php?manage_inventory&cr8_cat';}";
					$error .= "</script>";

					echo $error;
				}
			}
		}
	}
	if(isset($_POST['remove'])){
		if(!empty($_POST['catID'])){
			$catID = $_POST['catID'];

			$query = "SELECT supplycat FROM category WHERE catID = '$catID'";
			$result = mysqli_query($connection, $query);
			$catName = mysqli_fetch_assoc($result);

			$cat = $catName['supplycat'];

			$query1 = "DELETE FROM subcat WHERE catID = '$catID'";
			$result1 = mysqli_query($connection, $query1);

			$query2 = "DELETE FROM category WHERE catID = '$catID'";
			$result2 = mysqli_query($connection, $query2);

			$message = "<script type='text/javascript'>";
    		$message .= "window.onload = function(){";
    		$message .= "alert('$cat has been removed on Supply Category.');";
    		$message .= "location = 'controller.php?manage_inventory&rem_cat';}";
    		$message .= "</script>";
    		echo $message;
		}
		else{
			echo "No selected data. :(";
		}
	}
	if(isset($_POST['edit'])){
		$itemID = $_POST['itemID'];
		$message = "";
		$redirect = "";
		if(!(empty($_POST['name_3']))){
			$item_name = $_POST['name_3'];
			$image = basename($_FILES['name_4']['name']);

			$query = "UPDATE item SET item_name = '$item_name' WHERE itemID = '$itemID'";
			$result = mysqli_query($connection, $query);

			$image_item_dir = "F:/Wamp/wamp64/www/InventorySystem/images/items/"; //UPLOADED ITEM IMAGE DIRECTORY
			$imageType = pathinfo($image, PATHINFO_EXTENSION); //RETURN FILE EXTENSION
			$image_file = $itemID.".".$imageType; //RENAME FILE NAME WITH ITEM ID
			$move_path = $image_item_dir.$image_file; //DIRECTORY AND FILE DISTINATION TO BE UPLOADED

			if(file_exists($_FILES['name_4']['tmp_name'])){ //tmp_name is a temp server location
			    if($imageType == "jpeg" || $imageType == "jpg" || $imageType == "png") { //DETERMINE FILE TYPE
			        if($_FILES['name_4']['size'] < 500000) { //DETERMINE FILE SIZE MAXIMUM OF 5MB
			           	
			           	if(file_exists($move_path)){
			           		unlink($move_path);
			           	}

			           	rename($_FILES['name_4']['tmp_name'], $move_path);
			        }
			    }
			}
		
			$message = "Item ID: $itemID has successfully edited.";
			$redirect = "controller.php?manage_inventory&item&itemID=$itemID";
		}
		else{
			if(isset($_POST['name_3'])){
				if(empty($_POST['name_3'])){
					$message = "Item Name is empty";
					$redirect = "controller.php?manage_inventory&item&itemID=$itemID";
				}
			}
		}
		echo "<script type='text/javascript'>
			 window.onload = function(){
				alert('$message');
				location = '$redirect';
			}
			</script>;";
	}
	if(isset($_POST['edit_qty_btn'])){
		$message = "";
		$redirect = "";
		if(!empty($_POST['qty'])){
			$qty = $_POST['qty'];
			$stockID = $_POST['stockID'];
			$query = "UPDATE item_stock SET qty = '$qty' WHERE stockID = '$stockID'";
			$result = mysqli_query($connection, $query);

			$message = "stock ID: $stockID has been edited quantity successfully.";
			$redirect = "controller.php?manage_inventory&item&stockID=$stockID";
		}
		echo "<script type='text/javascript'>
			 window.onload = function(){
				alert('$message');
				location = '$redirect';
			}
			</script>;";
	}
	if(isset($_POST['delete'])){
		$itemID = $_POST['itemID'];
		$query = "SELECT item_name FROM item WHERE itemID = '$itemID'";
		$result = mysqli_query($connection, $query);
		$get_item = mysqli_fetch_assoc($result);
		$query1 = "DELETE FROM item WHERE itemID = '$itemID'";
		$result1 = mysqli_query($connection, $query1);

		if($result1){
			$message = "<script type='text/javascript'>";
			$message .= "window.onload = function(){";
			$message .= "alert('$get_item[item_name] has successfully deleted .');";
			$message .= "location = 'controller.php?manage_inventory';}";
			$message .= "</script>";

			echo $message;
		}
	}
?>
				<div id="manage-inventory-wrapper">
			<?php
				if(isset($_GET['cr8_cat'])){
			?>
					<div id="add-category-form-wrapper">
						<div id="add-category-header">
							<h3>ADD SUPPLY CATEGORY</h3>
						</div>
						<div id="add-category-form">
							<form action="controller.php?manage_inventory&cr8_cat" method="post">
								<label>Category Name:</label>
								<input type="text" name="supplycat">
								<div id="enable-add-sub" style="display: none">
									<label>Sub-Category:</label>
									<input type="text" name="subcat">
								</div>
								<input type="checkbox" id="chk_sub" onclick="check_subcat()">Add Sub-Category<br>
								<input type="submit" name="add" value="Add">
							</form>
						</div>
					</div> <!-- add-category-form-wrapper closing tag -->
			<?php
				}
				if(isset($_GET['rem_cat'])){
			?>
					<div id="remove-category-form-wrapper">
						<div id="remove-category-header">
							<h3>REMOVE SUPPLY CATEGORY</h3>
						</div>
						<div id="search-remove-category-form">
							<form action="controller.php?manage_inventory&rem_cat" method="post">
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
					</div> <!-- remove-category-form-wrapper closing tag -->
			<?php
				}
				if(isset($_GET['add_item'])){
			?>
					<div id="add-item-form-wrapper">
						<div id="add-item-header">
							<h3>ADD NEW ITEM</h3>
						</div>
						<div id="add-item-form">
							<form action="controller.php?manage_inventory&add_item" method="post" enctype="multipart/form-data">
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
										echo "<option value=\"controller.php?manage_inventory&add_item&cat=$category[catID]\">$category[supplycat]</option>";
									}
								?>
								</select>
								<label>Sub-Category:</label>
								<?php
									$select = "<select name=\"sub_ID\">";
									if(isset($_GET['cat'])){
										
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
								<input type="submit" name="add" value="Add">
							</form>
						</div>
					</div> <!-- add-item-form-wrapper closing tag -->
			<?php
				}
				if(isset($_GET['item'])){
			?>
					<div id="filter-wrapper">
						<div id="subcatdropdrown">
							<select class="form-control mb-2 mr-sm-2" onchange="location = this.value">
								<?php 
									if(isset($_GET['value'])){
										$value = $_GET['value'];
										$select = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$value'");
										$selectquery = mysqli_fetch_array($select);
										$results = mysqli_query($connection, "SELECT * FROM subcat");
								?>
										<option value="controller.php?manage_inventory&item&value=<?php echo $value;?>" select="selected"><?php echo $selectquery['subcatName']?></option>
									<?php
										while($row = mysqli_fetch_array($results)){
									?>
											<option value="controller.php?manage_inventory&item&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
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
											<option value="controller.php?manage_inventory&item&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
									<?php
											}
										}
									?>
							</select>
						</div>
						<div id="clear">
							<form action="controller.php?manage_inventory&item" method="post">
								<input class="btn btn-info" type="submit" name="clear" value="Clear Filter">
							</form>
						</div>
						<div id="search">
							<form class="form-inline" action="controller.php?manage_inventory&item" method="post">
								<input class="form-control mb-2 mr-sm-2" id="text" type="text" name="valueToSearch" placeholder="Item Name or Item I.D">
								<input class="btn btn-info mb-2 mr-sm-2" id="searchbtn" type="submit" name="search" value="Search">
							</form>
						</div>
						<div id="additem">
							<form method="post">
								<input class="btn btn-info" type="submit" name="add_to_sList" value="Add item list">
							</form>
							<?php
								if(isset($_POST['add_to_sList'])){
									$checker = mysqli_query($connection,"SELECT userID FROM add_stock where userID='$_SESSION[userID]'");
									if(mysqli_num_rows($checker)>0){
										header("location:controller.php?add_stock");
									}else{
										echo "<script type='text/javascript'>
											window.onload = function(){
											alert('Empty Table. Add item first');
											location = 'controller.php?manage_inventory&item';}
											</script>";
									}
								}
							?>
						</div>
					</div> <!-- filter-wrapper closing tag -->
					<div id="admin-function">
			            <div id="table-header"></div>
						<div id="table-wrapper">
							<table id="process-manager-table">
								<tr>
									<th>Item ID</th>
									<th>Item Name</th>
									<th>Date</th>
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
											$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
											$fetchitemstock = mysqli_fetch_array($itemstock);
								?>
											<tr style="border: 1px solid black;">
												<td><?php echo $row['itemID'];?></td>
								            	<td style="text-align: left;"><a href="controller.php?manage_inventory&item&itemID=<?php echo $row['itemID']; ?>"><?php echo $row['item_name'];?></a></td>
								            	<td></td>
								            	<td style="font-weight: bold;"><?php echo  $fetchitemstock['totalqty']; ?></td>
								            	<td><a class="btn btn-info mb-2 mr-sm-2" class="btn btn-info" href="controller.php?manage_inventory&item&get=<?php echo $row['itemID']; ?>">Add</a></td>
								        <?php
											$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
											while($row2 = mysqli_fetch_array($query)){
											$date = date('F j, Y',strtotime($row2['date_in']));
										?>
												<tr id="datarow">
								        			<td></td>
								        			<td></td>
													<td><?php echo  $date; ?></td>
													<td><a href="controller.php?manage_inventory&item&stock_qty=<?php echo $row2['stockID'] ?>"><?php echo  $row2['qty']; ?></a></td>
													<td></td>
								    			</tr>
										<?php
											}
										?>
											</tr>
								<?php	
									 	}
									 	if(isset($_GET['get'])){
											$getID = $_GET['get'];
											$check = "SELECT * FROM add_stock where itemID = '$getID'";
											$checkquery = mysqli_query($connection,$check);
											$checkidquery = mysqli_query($connection,"SELECT * FROM item where itemID='$getID'");
											$checkidfetch = mysqli_fetch_assoc($checkidquery);
											if(mysqli_num_rows($checkquery)>0){
												echo "<script type='text/javascript'>
														window.onload = function(){
														alert('You already add this Item!');
														location = 'controller.php?manage_inventory&item';}
													</script>";
											}
											else{
												mysqli_query($connection,"INSERT INTO add_stock (userID,itemID) VALUES ('$_SESSION[userID]','$getID')");
												echo "<script type='text/javascript'>
														window.onload = function(){
														alert('\"$checkidfetch[item_name]\" is added to Add item list.');
														}
													</script>";
											}
										}
									}
									else{
										$results = mysqli_query($connection, "SELECT * FROM item where subcatID='$value'");
										while($row = mysqli_fetch_array($results)){ 
											$subcat3 = $row['subcatID'];
											$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
											$subcat2= mysqli_fetch_array($subcat);
											$category3 = $row['catID'];
											$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
											$category2= mysqli_fetch_array($category);
											$itemstock = mysqli_query($connection,"SELECT SUM(qty) as totalqty FROM item_stock where itemID='$row[itemID]'");
											$fetchitemstock = mysqli_fetch_array($itemstock);
								?>
											<tr style="border: 1px solid black;">
												<td><?php echo $row['itemID'];?></td>
								            	<td style="text-align: left;"><a href="controller.php?manage_inventory&item&itemID=<?php echo $row['itemID']; ?>"><?php echo $row['item_name'];?></a></td>
								            	<td></td>
								            	<td style="font-weight: bold;"><?php echo  $fetchitemstock['totalqty']; ?></td>
								            	<td><a class="btn btn-info mb-2 mr-sm-2" class="btn btn-info" href="controller.php?manage_inventory&item&get=<?php echo $row['itemID']; ?>">Add</a></td>
								        <?php
											$query = mysqli_query($connection,"SELECT * FROM item_stock where itemID='$row[itemID]'");
											while($row2 = mysqli_fetch_array($query)){
											$date = date('F j, Y',strtotime($row2['date_in']));
										?>
												<tr id="datarow">
								        			<td></td>
								        			<td></td>
													<td><?php echo  $date; ?></td>
													<td><a href="controller.php?manage_inventory&item&stock_qty=<?php echo $row2['stockID'] ?>"><?php echo  $row2['qty']; ?></a></td>
													<td></td>
								    			</tr>
										<?php
											}
										?>
											</tr>
								<?php
										}
										if(isset($_GET['get'])){
											$getID = $_GET['get'];
											$check = "SELECT * FROM add_stock where itemID = '$getID'";
											$checkquery = mysqli_query($connection,$check);
											$checkidquery = mysqli_query($connection,"SELECT * FROM item where itemID='$getID'");
											$checkidfetch = mysqli_fetch_assoc($checkidquery);
											if(mysqli_num_rows($checkquery)>0){
												echo "<script type='text/javascript'>
														window.onload = function(){
														alert('You already add this Item!');
														location = 'controller.php?manage_inventory&item';}
													</script>";
											}
											else{
												mysqli_query($connection,"INSERT INTO add_stock (userID,itemID)VALUES ('$_SESSION[userID]','$getID')");
												echo "<script type='text/javascript'>
														window.onload = function(){
														alert('\"$checkidfetch[item_name]\" is added to Add item list.');
														}
													</script>";
											}
										}
									}
								?>
							</table>
						</div>
					</div><!-- admin-function closing tag -->
				<?php
					if(isset($_GET['itemID'])){
						$itemID = $_GET['itemID'];
						$query = "SELECT * FROM item WHERE itemID = '$itemID'";
						$result = mysqli_query($connection, $query);
						$get_item = mysqli_fetch_row($result);
						$query1 = "SELECT supplycat FROM category WHERE catID = '$get_item[1]'";
						$result1 = mysqli_query($connection, $query1);
						$get_cat = mysqli_fetch_assoc($result1);
						$query2 = "SELECT subcatName FROM subcat WHERE subcatID = '$get_item[2]'";
						$result2 = mysqli_query($connection, $query2);
						$get_sub_cat = mysqli_fetch_assoc($result2);
				?>
					<div class="container" id="edit-item-wrapper">
						<div id="edit_remove-item-form-wrapper">
							<div id="edit_remove-item-header">
								<h3>
									<?php
										echo "Item ID: ".$itemID;
									?>
								</h3>
							</div>
							<div id="edit_remove-item-form">
								<form action="controller.php?manage_inventory&edit" method="post" enctype="multipart/form-data">
			<?php
								$y = 1;
								$label = array("Category: ","Sub-Category: ","Item Name: ","Image: ");
								$path = "F:/Wamp/wamp64/www/InventorySystem/images/items/";
								$name = array();
								$enable_edit = array();
								for($x = 0; $x < sizeof($label); $x++){
									$name[$y] = "name_".$y;
									$enable_edit[$y] = "id_".$y;
									if($x == 3){
										$form = "<div class=\"form-group\">";
										if(file_exists($path.$get_item[$y].".jpg")){
											$src = "images/items/".$get_item[$y].".jpg";
										}
										else if(file_exists($path.$get_item[$y].".jpeg")){
											$src = "images/items/".$get_item[$y].".jpeg";
										}
										else if(file_exists($path.$get_item[$y].".png")){
											$src = "images/items/".$get_item[$y].".png";
										}
										else{
											$src = "images/items/noimage.png";
										}
										$sub_src = substr($src, 13);

										$form  .= "<label style=\"font-weight: bold;\">".$label[$x]."<a href=\"$src\" target=\"blank\">$sub_src</a>"."</label><br/>";
										$form .= "<div id=\"enable-edit-div\" style=\"display: none\">";
										$form .= "<input type=\"file\" name=\"$name[$y]\" value=\"$src\" id=\"".$enable_edit[$y]."\" readonly=\"true\"></a><br/>";
										$form .= "</div>";
										$form .= "</div>";
									}
									else if($x == 2){
										$form = "<div class=\"form-group\">";
										$form  .= "<label style=\"font-weight: bold;\">".$label[$x]."</label><br/>";
										$form .= "<input class=\"form-control\" type=\"text\" name=\"$name[$y]\" value=\"$get_item[$y]\" id=\"".$enable_edit[$y]."\" readonly=\"true\"><br/>";
										$form .= "</div>";
									}
									else if($x == 1){
										$form = "<div class=\"form-group\">";
										$form .= "<label style=\"font-weight: bold;\">".$label[$x]."</label>"." ".$get_sub_cat['subcatName']."<br/>";
										$form .= "</div>";
									}
									else if($x == 0){
										$form = "<div class=\"form-group\">";
										$form  .= "<label style=\"font-weight: bold;\">".$label[$x]."</label>"." ".$get_cat['supplycat']."<br/>";
										$form .= "</div>";
									}
									
									$form .= "<input type=\"hidden\" name=\"itemID\" value=\"$itemID\">";
												
									echo $form;
									$y++;
								}
											
								$enable_edit = "<input type=\"checkbox\" id=\"chk\" onclick=\"check()\">Enable Edit";
								$enable_edit .= "<div id=\"enable-edit-div1\" style=\"display: none\">";
								$enable_edit .= "<input class=\"btn btn-success\" type=\"submit\" name=\"edit\" value=\"Edit\" id=\"edit_button\">";
								$enable_edit .= "<input class=\"btn btn-danger\" type=\"submit\" name=\"delete\" value=\"Delete\" id=\"delete_button\" style=\"margin-left: 50%;\">";
								$enable_edit .= "</div>";
								
								echo $enable_edit;	
			?>
					
					
				
								</form>
							</div>
						</div> <!-- edit_remove-item-form-wrapper closing tag -->
				<?php
					}
					if(isset($_GET['stock_qty'])){
						$stockID = $_GET['stock_qty'];
						$query = "SELECT * FROM item_stock WHERE stockID = '$stockID'";
						$result = mysqli_query($connection, $query);
						$stock = mysqli_fetch_assoc($result);
						$query1 = "SELECT * FROM item WHERE itemID = '$stock[itemID]'";
						$result1 = mysqli_query($connection, $query1);
						$item = mysqli_fetch_assoc($result1);
						$date = date('F j, Y',strtotime($stock['date_in']));
				?>
						<div id="stock-qty" class="container">
							<div id="stock-qty-header" class="container">
								<h3>Stock ID: <?php echo $stockID; ?></h3>
							</div>
							<div id="stock-qty-form-wrapper" class="container">
								<form action="controller.php?manage_inventory&stock_qty" method="post">
									<div class="form-group">
										<label style="font-weight: bold;">Item Name:</label> <?php echo $item['item_name']; ?><br/>
									</div>
									<div class="form-group">
										<label style="font-weight: bold;">Date IN:</label> <?php echo $date; ?><br/>
									</div>
									<div class="form-group">
										<label style="font-weight: bold;">Quantity:</label><br/>
										<input class="form-control" id="edit_qty" type="text" name="qty" value="<?php echo $stock['qty'] ?>" readonly="true">
									</div>
									<input type="hidden" name="stockID" value="<?php echo $stockID; ?>">
									<input type="checkbox" id="chk_edit_qty" onclick="check1()">Enable Edit
									<div id="enable-edit-div2" style="display: none">
										<input class="btn btn-success" type="submit" name="edit_qty_btn" value="Edit" id="edit_button">
									</div>
								</form>
							</div>
						</div>
				<?php
					}
				?>
					</div>
			<?php
				}
			?>
				</div> <!-- manage-inventory-wrapper clossing tag -->
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->

<script>
	function check() {
		var enable = document.getElementById("enable-edit-div");
		enable.style.display = chk.checked ? "block" : "none";

		var enable = document.getElementById("enable-edit-div1");
		enable.style.display = chk.checked ? "block" : "none";
		

		var x;
		for(x = 3; x <= 5; x++){
			var tf_enable = document.getElementById("id_"+x);
			if(chk.checked){
				tf_enable.readOnly = false;
			}
			else{
				tf_enable.readOnly = true;
			}
		}	
	}
	function check_subcat() {
		var enable = document.getElementById("enable-add-sub");
		enable.style.display = chk_sub.checked ? "block" : "none";
	}
	function check1(){
		var enable = document.getElementById("enable-edit-div2");
		enable.style.display = chk_edit_qty.checked ? "block" : "none";

		var tf_enable = document.getElementById("edit_qty");
		if(chk_edit_qty.checked){
			tf_enable.readOnly = false;
		}
		else{
			tf_enable.readOnly = true;
		}
	}
</script>