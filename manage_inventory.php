<?php
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = "SELECT * FROM category";
	$getCat = mysqli_query($connection, $query);

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

				$message = $supplycat." successfully added to Supply Category";
				HEADER("location:controller.php?manage_inventory");
			}
		}
		else{
			echo "No input data. :(";
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
									while($category = mysqli_fetch_assoc($getCat)){
										echo "<option value=\"$category[catID]\">$category[supplycat]</option>";
									}
								?>
								</select>
								<input type="submit" name="remove" value="Remove">
							</form>
						</div>
					</div>
					<div id="">
						
					</div>
				</div>
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->