<?php
	ob_start();

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}

	class Sidebar{
		
		private function collapse_sb_header(){
			$page = "<div class=\"card\">";
			$page .= "<div class=\"card-header\">";

			return $page;
		}

		private function dp_menu_mu(){
			$page = "<div class=\"dropdown dropright\">";
			$page .= "<button type=\"button\" class=\"btn\" data-toggle=\"dropdown\">Manage user
				</button>";
			$page .= "<div class=\"dropdown-menu\">";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_user&profile\">Profile</a>";
	     	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_user&add_user\">Add user</a>";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_user&reset_pass\">Reset user's password</a>";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_user&remove_user\">Remove user</a>";
	      	$page .= "</div>";
	      	$page .= "</div>";

			return $page;
		}
		private function dp_menu_mi(){
			$page = "<div class=\"dropdown dropright\">";
			$page .= "<button type=\"button\" class=\"btn\" data-toggle=\"dropdown\">Manage Inventory
				</button>";
			$page .= "<div class=\"dropdown-menu\">";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_inventory&cr8_cat\">Add category</a>";
	     	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_inventory&rem_cat\">Remove category</a>";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_inventory&add_item\">Add new item</a>";
	      	$page .= "<a class=\"dropdown-item\" href=\"controller.php?manage_inventory&item\">Item</a>";
	      	$page .= "</div>";
	      	$page .= "</div>";

			return $page;
		}
		private function admin_manage(){
			$page = $this->dp_menu_mu();
			$page .= $this->dp_menu_mi();
			
			return $page;
		}
		private function user_manage(){
			$page = "<li><button type=\"button\" class=\"btn btn-primary\" href=\"controller.php?manage_user\">Manage account</button></li>";
			
			return $page;
		}
		private function manage_header(){
			$page = $this->collapse_sb_header();
			$page .= "<a class=\"card-link\" data-toggle=\"collapse\" href=\"#collapseTwo\">
				<h3 class=\"subject\">Manage</h3>
				</a>";
			$page .= "</div>";
			$page .= "<div id=\"collapseTwo\" class=\"collapse\" data-parent=\"#accordion\">";
			$page .= "<div class=\"card-body\">";
			$page .= "<ul class='orderlist'>";
			
			return $page;
		}

		private function manage_footer(){
			$page = "</ul>";
			$page .= "</div>";
			$page .= "</div>";
			$page .= "</div>";

			return $page;
		}
		public function manage_admin_div(){
			$page = $this->manage_header();
			$page .= $this->admin_manage();
			$page .= $this->manage_footer();

			echo $page;
		}
		public function manage_user_div(){
			$page = $this->manage_header();
			$page .= $this->user_manage();
			$page .= $this->manage_footer();

			echo $page;
		}
		private function transaction_header(){
			$page = $this->collapse_sb_header();
			$page .= "<a class=\"card-link\" data-toggle=\"collapse\" href=\"#collapseThree\">
				<h3 class=\"subject\">Transactions</h3>
				</a>";
			$page .= "</div>";
			$page .= "<div id=\"collapseThree\" class=\"collapse\" data-parent=\"#accordion\">";
			$page .= "<div class=\"card-body\">";
			$page .= "<ul class='orderlist'>";
			
			return $page;
		}

		private function transaction_footer(){
			$page = "</ul>";
			$page .= "</div>";
			$page .= "</div>";
			$page .= "</div>";

			return $page;
		}
		private function admin_transaction(){
			$page = "<li><a class=\"btn\" href=\"controller.php?transaction_logs\">Items</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=1\">Out</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=2\">In</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=3\">Logs</a></li>";
			
			return $page;
		}
		private function user_transaction(){
			$page = "<li><a class=\"btn\" href=\"controller.php?transaction_logs\">Items</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=1\">Out</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=2\">In</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?transaction_logs&logid=3\">Logs</a></li>";
			
			return $page;
		}
		public function transaction_user_div(){
			$page = $this->transaction_header();
			$page .= $this->user_transaction();
			$page .= $this->transaction_footer();

			echo $page;
		}
		public function transaction_admin_div(){
			$page = $this->transaction_header();
			$page .= $this->admin_transaction();
			$page .= $this->transaction_footer();

			echo $page;
		}
		private function bar_graph_items_header(){
			$page = $this->collapse_sb_header();
			$page .= "<a class=\"card-link\" data-toggle=\"collapse\" href=\"#collapseFour\">
				<h3 class=\"subject\">Graph</h3>
				</a>";
			$page .= "</div>";
			$page .= "<div id=\"collapseFour\" class=\"collapse\" data-parent=\"#accordion\">";
			$page .= "<div class=\"card-body\">";
			$page .= "<ul class='orderlist'>";

			return $page;
		}
		private function bar_graph(){
			$page = "<li><a class=\"btn\" href=\"controller.php?stats&stats=1\">Out Transaction</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?stats&stats=2\">In Transaction</a></li>";
			$page .= "<li><a class=\"btn\" href=\"controller.php?stats&stats=3&page=1\">Total Actual Quantity</a></li>";
			
			return $page;
		}
		private function bar_graph_items_footer(){
			$page = "</ul>";
			$page .= "</div>";
			$page .= "</div>";
			$page .= "</div>";

			return $page;
		}
		public function graph_div(){
			$page = $this->bar_graph_items_header();
			$page .= $this->bar_graph();
			$page .= $this->bar_graph_items_footer();

			echo $page;
		}
	}
	class ManageUser{
		private $connection;

		public function __construct($connection) {
	       $this->connection = $connection;
	   }
		public function admin_manage_userTable(){
			$query = "SELECT * FROM user";
			$result = mysqli_query($this->connection, $query);

			$table = "<div id=\"user-table\">";
			$table .= "<table class=\"table\">";
			$table .= "<thead>";
		    $table .= "<tr>";
		    $table .= "<th>ID</th>";
		    $table .= "<th>Name</th>";
		    $table .= "<th>Type</th>";
		    $table .= "</tr>";
		    $table .= "</thead>";
		    $table .= "<tbody>";
		    while($user = mysqli_fetch_assoc($result)){
		    	$table .= "<tr>";
		    	if(isset($_GET['add_user'])){
		    		$table .= "<td>$user[userID]</td>";
		    	}
		    	else if(isset($_GET['reset_pass'])){
		    		$table .= "<td><a href=\"controller.php?manage_user&reset_pass&id=$user[userID]&name=$user[name]\">$user[userID]</a></td>";
		    	}
		    	else if(isset($_GET['remove_user'])){
		    		$table .= "<td><a href=\"controller.php?manage_user&remove_user&id=$user[userID]&name=$user[name]\">$user[userID]</a></td>";
		    	}
		    	$table .= "<td>$user[name]</td>";
		    	$table .= "<td>$user[accountType]</td>";
		    	$table .= "</tr>";
		    }
		    $table .= "</tbody>";
			$table .= "</table>";
			$table .= "</div>";

			return $table;
		}
	}

?>