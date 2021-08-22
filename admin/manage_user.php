<?php
	include_once("dbconnection.php");
	require_once("D:/wamp64/www/InventorySystem/function.php");
	$image_item_dir = "D:/wamp64/www/InventorySystem/images/profile/"; //UPLOADED ITEM IMAGE DIRECTORY
	$path = "images/profile/";
	$table = new ManageUser($connection);

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	$query = "SELECT accountType, userID FROM user WHERE userID = '$_SESSION[userID]'";
	$result = mysqli_query($connection, $query);
	$getType = mysqli_fetch_assoc($result);

	$query = "SELECT * FROM user WHERE userID = '$getType[userID]'";
	$result = mysqli_query($connection, $query);
	$get_user = mysqli_fetch_row($result);
	
	if(file_exists($image_item_dir.$get_user[0].".jpg")){
		$src = $path.$get_user[0].".jpg";
	}
	else if(file_exists($image_item_dir.$get_user[0].".jpeg")){
		$src = $path.$get_user[0].".jpeg";
	}
	else if(file_exists($image_item_dir.$get_user[0].".png")){
		$src = $path.$get_user[0].".png";
	}
	else{
		$src = $path."noimage.png";
	}

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

			$message_ = "User added successfully :)";
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

			$message_ = "Password for ID no.:".$toReset." has been reset successfully.";
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
	if(isset($_GET['remUser'])){
		$userID = $_GET['remUser'];
		$message = "";
		$message_ = "";
		
		$query = "DELETE FROM user where userID = '$userID'";
		$result = mysqli_query($connection, $query);

		if($result == true){
			$message_ = $userID." Account has been remove successfully.";
		}
		else{
			$message_ = "Failed to remove user.";
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
			
			$imageType = pathinfo($image, PATHINFO_EXTENSION); //RETURN FILE EXTENSION
			$image_file = $userID.".".$imageType; //RENAME FILE NAME WITH ITEM ID
			$move_path = $image_item_dir.$image_file; //DIRECTORY AND FILE DISTINATION TO BE UPLOADED

			if(file_exists($_FILES['image']['tmp_name'])){ //tmp_name is a temp server location
			    if($imageType == "jpeg" || $imageType == "jpg" || $imageType == "png" || $imageType == "JPEG" || $imageType == "JPG" || $imageType == "PNG") { //DETERMINE FILE TYPE
			        if($_FILES['image']['size'] < 500000) { //DETERMINE FILE SIZE MAXIMUM OF 5MB
						
						if(file_exists($src)){
							rename($_FILES['image']['tmp_name'], $move_path);
							$message_ = 'Profile picture has been successfully edited.';
						}
						else{
							unlink($src);
			           	
							rename($_FILES['image']['tmp_name'], $move_path);
							$message_ = 'Profile picture has been successfully edited.';
						}
						
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

	if($getType['accountType'] == "admin"){	
?>
				<div id="manage-user-wrapper">
			<?php
				if(isset($_GET['profile'])){
			?>
				    <div class="bg-form">
				    	<div id="account-bg-wrapper">
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
										$details .= "<div class=\"form-group\">";
										$details .= "<label style=\"font-weight: bold;\" id=\"".$hide[$x]."\">&nbsp$label[$x]</label>";
										$details .= "<label id=\"".$hide1[$x]."\">&nbsp$get_user[$x]</label><br>";
										$details .= "</div>";
									}
									for($x = 1; $x < sizeof($label); $x++){
										$show[$x] = "show_id_".$x;
										$show1[$x] = "show1_id_".$x;
										$name[$x] = "name_".$x;
										$details .= "<div class=\"form-group\">";
										$details .= "<label id=\"".$show[$x]."\" style=\"font-weight: bold; display: none;\">$label[$x]</label>";
										if($x == 2){
											$details .= "<input class=\"form-control mb-2 mr-sm-2\" type=\"password\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
										}
										else{
											$details .= "<input class=\"form-control mb-2 mr-sm-2\" type=\"text\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
										}
										$details .= "</div>";
									}
									$details .= "<div class=\"form-group\">";
									$details .= "<br><input class=\"btn btn-success mb-2 mr-sm-2\" style=\"display: none;\" type=\"submit\" name=\"edit\" value=\"Edit\" id=\"show_edit\">";
									$details .= "</div>";
									$details .= "</div>";

									echo $details;
								?>
									</form>
									<div class="checkbox">
										<label><input type="checkbox" id="chk" onclick="check()"> Enable Edit</label>
									</div>
								</div>
							</div>
						</div>
					</div>
			<?php
				}
				if(isset($_GET['add_user'])){
			?>
					<div class="bg-form">
						<div id="add-user-bg-wrapper">
							<div id="add-user-form-wrapper" class="container">
								<div id="add-user-header">
									<h3 class="modal-title">ADD USER</h3>
								</div>
								<div id="add-user-form" >
									<form action="controller.php?manage_user" method="post">
										<div class="form-group">
											<label>ID No:</label><br>
											<input class="form-control mb-2 mr-sm-2" type="text" name="userID" pattern="^[0-9]*$" title="Input numbers or digits only.">
										</div>
										<div class="form-group">
											<label>Name:</label><br>
											<input class="form-control mb-2 mr-sm-2" type="text" name="name">
										</div>
										<div class="form-group">
											<label>Password:</label><br>
											<input class="form-control mb-2 mr-sm-2" type="password" name="password" placeholder="default password: 123">
										</div>
										<div class="form-group">
											<label>Account Type:</label><br>
											<select class="form-control mb-2 mr-sm-2" name="acctType">
												<option value="user">user</option>
												<option value="admin">admin</option>
											</select>
										</div>
										<input class="btn btn-success mb-2 mr-sm-2" id="add-button" type="submit" name="add" value="Add">
									</form>
								</div>
							</div>
						</div>
						<div class="manage_user-table">
					<?php
						$users = $table->admin_manage_userTable();
						echo $users;
					?>
						</div>
					</div>
			<?php	
				}
				if(isset($_GET['reset_pass'])){
			?>
					<div class="bg-form">
						<div id="reset-user-pass-bg-wrapper">
							<div id="reset-user-pass-wrapper">
								<div id="reset-user-pass-header">
									<h3 class="modal-title">RESET USER'S PASSWORD</h3>
								</div>
								<div id="search-user-id-form">
									<form class="form-inline" action="controller.php?manage_user&reset_pass" method="post">
										<div class="form-group">
											<input class="form-control mb-2 mr-sm-2" type="text" name="toSearch" placeholder="search user ID no.">
											<input class="btn btn-success" id="search-button" type="submit" name="search" value="Search">
										</div>
									</form>
								</div>
								<div id="reset-user-id-form">
									<form class="form-inline" action="controller.php?manage_user" method="post">
										<?php
											if($name_reset != null || $userID_reset != null){
										echo "<label>Name: ".$name_reset."</label><br/>";
											}
										?>
										<div class="form-group">
											<input class="form-control mb-2 mr-sm-2" type="text" name="toReset" value="<?php echo $userID_reset; ?>" readonly>
											<input class="btn btn-success" id="reset-button" type="submit" name="reset" value="Reset">
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="manage_user-table">
					<?php
						$users = $table->admin_manage_userTable();
						echo $users;
					?>
						</div>
					</div>
			<?php
				}
				if(isset($_GET['remove_user'])){
			?>
					<div class="bg-form">
						<div id="remove-user-bg-wrapper">
							<div id="remove-user-wrapper" class="panel panel-success">
								<div id="remove-user-header" class="panel-heading">
									<h3 class="modal-title">REMOVE USER</h3>
								</div>
								<div id="search-remove-user-form" class="panel-body">
									<form class="form-inline" action="controller.php?manage_user&remove_user" method="post">
										<div class="form-group">
											<input class="form-control mb-2 mr-sm-2" type="text" name="toReSearch" placeholder="search user ID no.">
											<input class="btn btn-success" id="search-button1" type="submit" name="search" value="Search">
										</div>
									</form>
								</div>
								<div id="remove-user-form" class="panel-body">
									<form class="form-inline" action="#" method="post">
										<?php
											if($name_remove != null || $userID_remove != null){
										echo "<label>Name: ".$name_remove."</label><br/>";
											}
										?>
										<div class="form-group">
											<input class="form-control mb-2 mr-sm-2" type="text" name="toRemove" value="<?php echo $userID_remove; ?>" readonly>
											<input type="button" class="btn btn-danger" id="remove-button" onclick="rem_user(<?php echo $userID_remove; ?>)" value="Remove">
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="manage_user-table">
<?php
						$users = $table->admin_manage_userTable();
						echo $users;
					echo "</div> <!-- manage_user-table closing tag -->";
				echo "</div? <!-- bg-form closing tag -->";
				}
		echo "</div> <!-- manage-user-wrapper closing tag -->";

	}

	else{
?>
				<div id="manage-user-wrapper">
					<div class="bg-form">
						<div id="account-bg-wrapper">
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
										$details .= "<input class=\"form-control mb-2 mr-sm-2\" type=\"password\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
									}
									else{
										$details .= "<input class=\"form-control mb-2 mr-sm-2\" type=\"text\" id=\"".$show1[$x]."\" name=\"".$name[$x]."\" value=\"".$get_user[$x]."\" style=\"display: none;\">";
									}
								}
								$details .= "<input class=\"btn btn-success mb-2 mr-sm-2\" style=\"display: none;\" type=\"submit\" name=\"edit\" id=\"show_edit\" value=\"Edit\">";
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
					</div>
				</div>
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
	function rem_user(user_ID){
		var userID = user_ID;
		if(userID != null){
			if(confirm("Do you really want to remove user ID no: "+userID)){
				window.location.href = 'controller.php?manage_user&remUser='+userID+'';
				return true;
			}
		}
		else{
			alert('No I.D input!');
			window.location.href = 'controller.php?manage_user&remove_user';
		}
	}
</script>