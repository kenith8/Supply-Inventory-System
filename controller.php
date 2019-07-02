<?php
	require_once("dbconnection.php");

	/*checks if the operation being handled is a login request  */
	if(isset($_GET["user"])){  
		if(isset($_POST["userID"]) && isset($_POST["password"])){
			$username = $_POST["userID"];
			$password = $_POST["password"];
			$query = "select * from user";
			$login_user = mysqli_query($connection,$query);
			while($login = mysqli_fetch_assoc($login_user)){
				if($username == $login['userID'] && $password == $login['password']){
					$_SESSION["userID"] = $username;
					if($login['accountType'] == "admin"){
						HEADER("location:controller.php?admin_home");
					}
					else if($login['accountType'] == "user"){
						HEADER("location:controller.php?home");
					}
				}
				else{
					echo "<script type='text/javascript'>
							window.onload = function(){
								alert('Wrong or Invalid input!');
								location = 'index.php';
							}
							</script>";
				}
			}
		}
	}
	
	/* checks if the operation being handled is a logout request */
	else if(isset($_GET["logout"])){
		session_destroy();
		HEADER("location:index.php");	
	}
	else{				
		include("includes/layouts/header.php");
		include("includes/layouts/sidebar.php");
		if(isset($_GET["admin_home"])){
			include("admin_home.php");
		}
		if(isset($_GET["home"])){
			include("home.php");
		}
		if(isset($_GET["getitem"])){
			include("getitem.php");
		}
		if(isset($_GET["manage_user"])){
			include("manage_user.php");
		}
		if(isset($_GET["manage_inventory"])){
			include("manage_inventory.php");
		}
		include("includes/layouts/footer.php");
	}
?>