<?php
	include_once("dbconnection.php");
	
	class Manage{
		
	}

	class Sidebar{

		public function admin_manage(){
			$page = "<li><a href=\"controller.php?manage_user\">Manage user</a></li>";
			$page .= "<li><a href=\"controller.php?manage_inventory\">Manage Inventory</a></li>";
			
			return $page;
		}
		public function user_manage(){
			$page = "<li><a href=\"controller.php?manage_user\">Manage account</a></li>";
			
			return $page;
		}

		public function manage_header(){
			$page = "<a href=\"#\"><h3 class=\"subject\">Manage</h3></a>";
			$page .= "<ul class='orderlist'>";
			
			return $page;
		}

		public function manage_footer(){
			$page = "</ul>";
			return $page;
		}
	}
	
	class Container{

	}

	class UserAccess{
		private $accntType;

	}
?>