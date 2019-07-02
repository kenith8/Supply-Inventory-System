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

	$userID_reset = null;
	$userID_remove = null;
	$name_reset = null;
	$name_remove = null;

	if(isset($_POST['add'])){
		if(empty($_POST['userID']) || empty($_POST['name']) || empty($_POST['password']) || empty($_POST['acctType'])){
			echo "No data input :(";
		}
		else{
			$userID = $_POST['userID'];
			$name = $_POST['name'];
			$password = $_POST['password'];
			$acctType = $_POST['acctType'];

			$query = "INSERT into user values ('$userID','$name','$password','$acctType')";
			$result = mysqli_query($connection,$query);

			echo "User add successfully :)";
			
		}
	}

	if(isset($_POST['search'])){
		if(!(empty($_POST['toSearch']))){
			$toSearch = $_POST['toSearch'];

			$query = "SELECT userID,name FROM user WHERE userID = '$toSearch'";
			$result = mysqli_query($connection, $query);

			$getUser = mysqli_fetch_assoc($result);

			if($toSearch == $getUser['userID']){
				echo $toSearch." is found in database.";

				$userID_reset = $getUser['userID'];
				$name_reset = $getUser['name'];
			}
			else{
				echo $toSearch." is not found in database.";
			}
		}
		else if(!(empty($_POST['toReSearch']))){
			$toReSearch = $_POST['toReSearch'];

			$query = "SELECT userID,name FROM user WHERE userID = '$toReSearch'";
			$result = mysqli_query($connection, $query);

			$getUser = mysqli_fetch_assoc($result);

			if($toReSearch == $getUser['userID']){
				echo $toReSearch." is found in database.";

				$userID_remove = $getUser['userID'];
				$name_remove = $getUser['name'];
			}
			else{
				echo $toReSearch." is not found in database.";
			}
		}
		else{
			echo "No input data.";
		}
	}

	if(isset($_POST['reset'])){
		if(!(empty($_POST['toReset']))){
			$toReset = $_POST['toReset'];

			$query = "UPDATE user SET password = '123' where userID = '$toReset'";
			$result = mysqli_query($connection, $query);

			echo $toReset." Password has been reset successfully.";
		}
		else{
			echo "No I.D. found.";
		}
	}
	if(isset($_POST['remove'])){
		if(!(empty($_POST['toRemove']))){
			$toRemove = $_POST['toRemove'];

			$query = "DELETE FROM user where userID = '$toRemove'";
			$result = mysqli_query($connection, $query);

			echo $toRemove." Account has been remove successfully.";
		}
		else{
			echo "No I.D. found.";	
		}
	}
?>
				<div id="manage-user-wrapper">
					<div id="add-user-form-wrapper">
						<div id="add-user-header">
							<h3>ADD USER</h3>
						</div>
						<div id="add-user-form">
							<form action="controller.php?manage_user" method="post">
								<label>ID No.</label>
								<input type="text" name="userID">
								<label>Name</label>
								<input type="text" name="name">
								<label>Password</label>
								<input type="password" name="password" value="123">
								<label>Account Type.</label>
								<select name="acctType">
									<option value="user">user</option>
									<option value="admin">admin</option>
								</select>
								<input type="submit" name="add" value="Add">
							</form>
						</div>
					</div>
					<div id="reset-user-pass-wrapper">
						<div id="reset-user-pass-header">
							<h3>RESET USER'S PASSWORD</h3>
						</div>
						<div id="search-user-id-form">
							<form action="controller.php?manage_user" method="post">
								<input type="text" name="toSearch">
								<input type="submit" name="search" value="Search">
							</form>
						</div>
						<div id="reset-user-id-form">
							<form action="" method="post">
								<?php
									if($name_reset != null || $userID_reset != null){
								echo "<label>Name: ".$name_reset."</label><br/>";
									}
								?>
								<input type="text" name="toReset" value="<?php echo $userID_reset; ?>" readonly>
								<input type="submit" name="reset" value="Reset">
							</form>
						</div>
					</div>
					<div id="remove-user-wrapper">
						<div id="remove-user-header">
							<h3>REMOVE USER</h3>
						</div>
						<div id="search-remove-user-form">
							<form action="controller.php?manage_user" method="post">
								<input type="text" name="toReSearch">
								<input type="submit" name="search" value="Search">
							</form>
						</div>
						<div id="remove-user-form">
							<form action="controller.php?manage_user" method="post">
								<?php
									if($name_remove != null || $userID_remove != null){
								echo "<label>Name: ".$name_remove."</label><br/>";
									}
								?>
								<input type="text" name="toRemove" value="<?php echo $userID_remove; ?>" readonly>
								<input type="submit" name="remove" value="Remove">
							</form>
						</div>
					</div>
				</div>
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->