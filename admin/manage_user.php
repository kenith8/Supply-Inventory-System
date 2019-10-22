<?php
	include_once("dbconnection.php");
	require_once("F:/Wamp/wamp64/www/InventorySystem/function.php");
	$table = new ManageUser($connection);

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = "SELECT accountType, userID FROM user WHERE userID = '$_SESSION[userID]'";
	$result = mysqli_query($connection, $query);
	$getType = mysqli_fetch_assoc($result);

	$userID_reset = null;
	$userID_remove = null;
	$name_reset = null;
	$name_remove = null;
	if(isset($_GET['id']) && isset($_GET['name'])){
		$userID_reset = $_GET['id'];
		$userID_remove = $_GET['id'];
		$name_reset = $_GET['name'];
		$name_remove = $_GET['name'];
	}

	if(isset($_POST['add'])){
		$message = "";
		$message_ = "";
		if(empty($_POST['userID']) || empty($_POST['name']) || empty($_POST['acctType'])){
			$message_ = "No data input :(";
		}
		else{
			if(empty($_POST['password'])){
				$password = "123";
			}
			else{
				$password = $_POST['password'];
			}
			$userID = $_POST['userID'];
			$name = $_POST['name'];
			$acctType = $_POST['acctType'];

			$query = "INSERT into user values ('$userID','$name','$password','$acctType')";
			$result = mysqli_query($connection,$query);

			$message_ = "User add successfully :)";
		}
		$message .= "<script type='text/javascript'>";
		$message .= "window.onload = function(){";
		$message .= "alert('$message_');";
		$message .= "location = 'controller.php?manage_user&add_user';}";
		$message .= "</script>";
					
		echo $message;
	}

	if(isset($_POST['search'])){
		$message = "";
		$message_ = "";
		if(!(empty($_POST['toSearch']))){
			$toSearch = $_POST['toSearch'];

			$query = "SELECT userID,name FROM user WHERE userID = '$toSearch'";
			$result = mysqli_query($connection, $query);

			$getUser = mysqli_fetch_assoc($result);

			if($toSearch == $getUser['userID']){
				$message_ = $toSearch." is found in database.";

				$userID_reset = $getUser['userID'];
				$name_reset = $getUser['name'];
			}
			else{
				$message_ = $toSearch." is not found in database.";
			}
		}
		else if(!(empty($_POST['toReSearch']))){
			$toReSearch = $_POST['toReSearch'];

			$query = "SELECT userID,name FROM user WHERE userID = '$toReSearch'";
			$result = mysqli_query($connection, $query);

			$getUser = mysqli_fetch_assoc($result);

			if($toReSearch == $getUser['userID']){
				$message_ = $toReSearch." is found in database.";

				$userID_remove = $getUser['userID'];
				$name_remove = $getUser['name'];
			}
			else{
				$message_ = $toReSearch." is not found in database.";
			}
		}
		else{
			$message_ = "No input data.";
		}

		$message .= "<script type='text/javascript'>";
		$message .= "window.onload = function(){";
		$message .= "alert('$message_');";
		$message .= "}";
		$message .= "</script>";
					
		echo $message;
	}

	if(isset($_POST['reset'])){
		$message = "";
		$message_ = "";
		if(!(empty($_POST['toReset']))){
			$toReset = $_POST['toReset'];

			$query = "UPDATE user SET password = '123' where userID = '$toReset'";
			$result = mysqli_query($connection, $query);

			$message_ = $toReset." Password has been reset successfully.";
		}
		else{
			$message_ = "No I.D. found.";
		}
		$message .= "<script type='text/javascript'>";
		$message .= "window.onload = function(){";
		$message .= "alert('$message_');";
		$message .= "location = 'controller.php?manage_user&reset_pass';}";
		$message .= "</script>";
					
		echo $message;
	}
	if(isset($_POST['remove'])){
		$message = "";
		$message_ = "";
		if(!(empty($_POST['toRemove']))){
			$toRemove = $_POST['toRemove'];

			$query = "DELETE FROM user where userID = '$toRemove'";
			$result = mysqli_query($connection, $query);

			$message_ = $toRemove." Account has been remove successfully.";
		}
		else{
			$message_ = "No I.D. found.";	
		}
		$message .= "<script type='text/javascript'>";
		$message .= "window.onload = function(){";
		$message .= "alert('$message_');";
		$message .= "location = 'controller.php?manage_user&remove_user';}";
		$message .= "</script>";
					
		echo $message;
	}
	if(isset($_POST['edit'])){
		if(!(empty($_POST['name_1']) || empty($_POST['name_2']))){
			$message = "";
			$message_ = "";

			$image = basename($_FILES['image']['name']);
			$name = $_POST['name_1'];
			$pass = $_POST['name_2'];
			$userID = $_SESSION['userID'];


			$query = "UPDATE user SET name = '$name', password = '$pass' WHERE userID = '$userID'";
			$result = mysqli_query($connection, $query);
			if($result){
				$message_ .= 'Name or Password has been successfully edited.\n';
			}

			$image_item_dir = "F:/Wamp/wamp64/www/InventorySystem/images/profile/"; //UPLOADED ITEM IMAGE DIRECTORY
			$imageType = pathinfo($image, PATHINFO_EXTENSION); //RETURN FILE EXTENSION
			$image_file = $userID.".".$imageType; //RENAME FILE NAME WITH ITEM ID
			$move_path = $image_item_dir.$image_file; //DIRECTORY AND FILE DISTINATION TO BE UPLOADED

			if(file_exists($_FILES['image']['tmp_name'])){ //tmp_name is a temp server location
			    if($imageType == "jpeg" || $imageType == "jpg" || $imageType == "png") { //DETERMINE FILE TYPE
			        if($_FILES['image']['size'] < 500000) { //DETERMINE FILE SIZE MAXIMUM OF 5MB
			           	
			           	if(file_exists($move_path)){
			           		unlink($move_path);
			           	}

			           	rename($_FILES['image']['tmp_name'], $move_path);
			           	$message_ = 'Profile picture has been successfully edited.';
			        }
			 		else {
						$message_ = "Wrong Size. Max size Allowed: 5MB";
			        }
				} 
				else {
					$message_ = "Wrong Format. Choose another format";
				}
			}
		
			$message .= "<script type='text/javascript'>";
			$message .= "window.onload = function(){";
			$message .= "alert('$message_');";
			$message .= "location = 'controller.php?manage_user&profile';}";
			$message .= "</script>";
					
			echo $message;
		}
		else{
			if(isset($_POST['name_1']) || isset($_POST['name_2'])){
				$message = "";
				$message_ = "";
				for($x = 0; $x < 3; $x++){
					if($x == 1){
						if(empty($_POST['name_'.$x])){
							$message_ .= 'Name is empty. \n';
						}
					}
					if($x == 2){
						if(empty($_POST['name_'.$x])){
							$message_ .= 'Password is empty.';
						}
					}
				}
				$message .= "<script type='text/javascript'>";
				$message .= "window.onload = function(){";
				$message .= "alert('$message_');";
				$message .= "location = 'controller.php?manage_user&profile';}";
				$message .= "</script>";
							
				echo $message;
			}
		}
	}

	$query = "SELECT * FROM user WHERE userID = '$getType[userID]'";
	$result = mysqli_query($connection, $query);
	$get_user = mysqli_fetch_row($result);
	$full_path = "F:/Wamp/wamp64/www/InventorySystem/images/profile/";
	$path = "images/profile/";
	if(file_exists($full_path.$get_user[0].".jpg")){
		$src = $path.$get_user[0].".jpg";
	}
	else if(file_exists($full_path.$get_user[0].".jpeg")){
		$src = $path.$get_user[0].".jpeg";
	}
	else if(file_exists($full_path.$get_user[0].".png")){
		$src = $path.$get_user[0].".png";
	}
	else{
		$src = $path."noimage.png";
	}

	if($getType['accountType'] == "admin"){	
?>
				<div id="manage-user-wrapper">
			<?php
				if(isset($_GET['profile'])){
			?>
				    <div id="account-wrapper">
						<div id="account-header">
							<h3 class="modal-title">Profile</h3>
						</div>
						<div id="account-image">
								<img src="<?php echo $src; ?>">
								<form action="controller.php?manage_user&profile" method="post" enctype="multipart/form-data">
									<input style="display: none;" type="file" name="image" id="show_image">
						</div>
						<?php
							$hide = array();
							$hide1 = array();
							$show = array();
							$show1 = array();
							$name = array();
							$details = "<div class=\"panel-body\" id=\"account-details\">";
								
							$label = array("ID no: ","Name: ","Password: ");
							for($x = 0; $x < sizeof($label)-1; $x++){
								$hide[$x] = "hide_id_".$x;
								$hide1[$x] = "hide1_id_".$x;
								$details .= "<label style=\"font-weight: bold;\" id=\"".$hide[$x]."\">$label[$x]</label>";
								$details .= "<label id=\"".$hide1[$x]."\">$get_user[$x]</label><br>";
							}
							for($x = 1; $x < sizeof($label); $x++){
								$show[$x] = "show_id_".$x;
								$show1[$x] = "show1_id_".$x;
								$name[$x] = "name_".$x;
								$details .= "<label id=\"".$show[$x]."\" style=\"font-weight: bold; display: none;\">$label[$x]</label>";
								if($x == 2){
									$details .= "<input type=\"password\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
								}
								else{
									$details .= "<input type=\"text\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
								}
							}
							$details .= "<br><input style=\"display: none;\" type=\"submit\" name=\"edit\" value=\"Edit\" id=\"show_edit\">";
							$details .= "</div>";

							echo $details;
						?>
							</form>
							<input type="checkbox" id="chk" onclick="check()">Enable Edit
						</div>
					</div>
			<?php
				}
				if(isset($_GET['add_user'])){
			?>
					<div id="add-user-form-wrapper" class="container">
						<div id="add-user-header">
							<h3>ADD USER</h3>
						</div>
						<div id="add-user-form">
							<form action="controller.php?manage_user" method="post">
								<label>ID No.</label><br>
								<input type="text" name="userID"><br>
								<label>Name</label><br>
								<input type="text" name="name"><br>
								<label>Password</label><br>
								<input type="password" name="password" placeholder="default password: 123"><br>
								<label>Account Type.</label><br>
								<select name="acctType">
									<option value="user">user</option>
									<option value="admin">admin</option>
								</select>
								<input id="add-button" type="submit" name="add" value="Add">
							</form>
						</div>
					</div>
			<?php
					$users = $table->admin_manage_userTable();
					echo $users;
				}
				if(isset($_GET['reset_pass'])){
			?>
					<div id="reset-user-pass-wrapper" class="panel panel-success">
						<div id="reset-user-pass-header" class="panel-heading">
							<h3>RESET USER'S PASSWORD</h3>
						</div>
						<div id="search-user-id-form" class="panel-body">
							<form action="controller.php?manage_user&reset_pass" method="post">
								<input type="text" name="toSearch" placeholder="search user ID no.">
								<input id="search-button" type="submit" name="search" value="Search">
							</form>
						</div>
						<div id="reset-user-id-form" class="panel-body">
							<form action="controller.php?manage_user" method="post">
								<?php
									if($name_reset != null || $userID_reset != null){
								echo "<label>Name: ".$name_reset."</label><br/>";
									}
								?>
								<input type="text" name="toReset" value="<?php echo $userID_reset; ?>" readonly>
								<input id="reset-button" type="submit" name="reset" value="Reset">
							</form>
						</div>
					</div>
			<?php
					$users = $table->admin_manage_userTable();
					echo $users;
				}
				if(isset($_GET['remove_user'])){
			?>
					<div id="remove-user-wrapper" class="panel panel-success">
						<div id="remove-user-header" class="panel-heading">
							<h3>REMOVE USER</h3>
						</div>
						<div id="search-remove-user-form" class="panel-body">
							<form action="controller.php?manage_user&remove_user" method="post">
								<input type="text" name="toReSearch" placeholder="search user ID no.">
								<input id="search-button1" type="submit" name="search" value="Search">
							</form>
						</div>
						<div id="remove-user-form" class="panel-body">
							<form action="controller.php?manage_user" method="post">
								<?php
									if($name_remove != null || $userID_remove != null){
								echo "<label>Name: ".$name_remove."</label><br/>";
									}
								?>
								<input type="text" name="toRemove" value="<?php echo $userID_remove; ?>" readonly>
								<input id="remove-button" type="submit" name="remove" value="Remove">
							</form>
						</div>
					</div>
<?php
					$users = $table->admin_manage_userTable();
					echo $users;
				}
		echo "</div> <!-- manage-user-wrapper closing tag -->";
	}
	else{
?>
				<div id="manage-user-wrapper">
					<div id="account-wrapper">
						<div id="account-header">
							<h3>Profile</h3>
						</div>
						<div id="account-image">
							<img src="<?php echo $src; ?>">
							<form action="controller.php?manage_user" method="post" enctype="multipart/form-data">
								<input style="display: none;" type="file" name="image" id="show_image">
						</div>
					<?php
						$hide = array();
						$hide1 = array();
						$show = array();
						$show1 = array();
						$name = array();
						$details = "<div id=\"account-details\">";
					
						$label = array("ID no: ","Name: ","Password: ");
						for($x = 0; $x < sizeof($label)-1; $x++){
							$hide[$x] = "hide_id_".$x;
							$hide1[$x] = "hide1_id_".$x;
							$details .= "<label style=\"font-weight: bold;\" id=\"".$hide[$x]."\">$label[$x]</label>";
							$details .= "<label id=\"".$hide1[$x]."\">$get_user[$x]</label><br>";
						}
						for($x = 1; $x < sizeof($label); $x++){
							$show[$x] = "show_id_".$x;
							$show1[$x] = "show1_id_".$x;
							$name[$x] = "name_".$x;
							$details .= "<label id=\"".$show[$x]."\" style=\"font-weight: bold; display: none;\">$label[$x]</label>";
							if($x == 2){
								$details .= "<input type=\"password\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
							}
							else{
								$details .= "<input type=\"text\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
							}
						}
						$details .= "<input style=\"display: none;\" type=\"submit\" name=\"edit\" id=\"show_edit\">";
						$details .= "</div>";

						echo $details;
					?>
							</form>
						<input type="checkbox" id="chk" onclick="check()">Enable Edit
						</div>
					</div>
				</div> <!-- manage-user-wrapper closing tag -->
<?php
	}
?>
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->
<script>
	function check() {
		var show = document.getElementById("show_image");
		show.style.display = chk.checked ? "block" : "none";

		var show = document.getElementById("show_edit");
		show.style.display = chk.checked ? "block" : "none";

		for($x = 1; $x < 4; $x++){
			var show = document.getElementById("show_id_"+$x);
			show.style.display = chk.checked ? "block" : "none";

			var show = document.getElementById("show1_id_"+$x);
			show.style.display = chk.checked ? "block" : "none";
		}
	}
</script>