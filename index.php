<?php
	require_once("dbconnection.php");

	if(isset($_SESSION["userID"])){
		$query = "SELECT accountType FROM user WHERE userID = '$_SESSION[userID]'";
		$result = mysqli_query($connection, $query);
		$getType = mysqli_fetch_assoc($result);

		if($getType['accountType'] == "user"){
			HEADER("location:controller.php?home");
		}
		else{
			HEADER("location:controller.php?admin_home");
		}
	}
?>

<!DOCTYPE html>
<html>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<head>
		<title>Login</title>
	</head>
	<body>
		
		<div class="limiter">
			<div class="container-login100" 
			style="background:url(images/255.jpg); background-repeat: no-repeat; background-size: 100% 100%; ">
				<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-50">
					<form class="login100-form validate-form " method="post" action="controller.php?user">
						<span class="login100-form-title p-b-33" style="background-color: #ffffff">
							Account Login
						</span>

						<div class="wrap-input100 validate-input" data-validate = "Valid ID is required" >
							<input class="input100" type="text" name="userID" placeholder="ID">
							<span class="focus-input100-1"></span>
							<span class="focus-input100-2"></span>
						</div>

						<div class="wrap-input100 rs1 validate-input" data-validate="Password is required" >
							<input class="input100" type="password" name="password" placeholder="Password">
							<span class="focus-input100-1"></span>
							<span class="focus-input100-2"></span>
						</div>

						<div class="container-login100-form-btn m-t-20">
							<button class="login100-form-btn" type="submit">
								Sign in
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>