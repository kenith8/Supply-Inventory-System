<?php
	include_once("dbconnection.php");
	
	class Sidebar{

		private function admin_manage(){
			$page = "<li><a href=\"controller.php?manage_user\">Manage user</a></li>";
			$page .= "<li><a href=\"controller.php?manage_inventory\">Manage Inventory</a></li>";
			
			return $page;
		}
		private function user_manage(){
			$page = "<li><a href=\"controller.php?manage_user\">Manage account</a></li>";
			
			return $page;
		}
		private function manage_header(){
			$page = "<a href=\"#\"><h3 class=\"subject\">Manage</h3></a>";
			$page .= "<ul class='orderlist'>";
			
			return $page;
		}

		private function manage_footer(){
			$page = "</ul>";
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
			$page = "<a href=\"#\"><h3 class=\"subject\">Transaction</h3></a>";
			$page .= "<ul class='orderlist'>";
			
			return $page;
		}

		private function transaction_footer(){
			$page = "</ul>";
			return $page;
		}
		private function admin_transaction(){
			$page = "<li><a href=\"#\">IN</a></li>";
			$page .= "<li><a href=\"controller.php?transaction_logs\">OUT</a></li>";
			
			return $page;
		}
		private function user_transaction(){
			$page = "<li><a href=\"#\">IN</a></li>";
			$page .= "<li><a href=\"controller.php?transaction_logs\">OUT</a></li>";
			
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
	}

?>