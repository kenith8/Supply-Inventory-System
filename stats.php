<?php
	ob_start();
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
?>
<?php
if(isset($_GET['stats'])){
	if(($_GET['stats'])==1){
?>
		<div id="stats">
			<div>
				<h4 style="text-align: center">
					Out Transaction
				<h4>
			</div>
			<div id="go">
				<form method="post">
					<label>From</label>
					<input type="date" name="date_filter">
					<label>To</label>
					<input type="date" name="date_filter2">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;"></button>
				</form>
			</div>
			<div id="clear">
				<form action="controller.php?stats&stats=1" method="post">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;"></button>
				</form>
			</div>
<?php
				if(isset($_POST['bong'])){
?>
				<div>
					<h4 style="text-align: center;">
<?php
						$date = date('F j, Y h:i:sa',strtotime($_POST['date_filter']));
						echo $date;
?>
						TO
<?php
						$date = date('F j, Y h:i:sa',strtotime($_POST['date_filter2']." 23:59:59"));
						echo $date;
?>
						</h4>
				</div>
<?php
				}
?>
			
		<?php
				$query = mysqli_query($connection,"SELECT * FROM item_logs");
				$fetch = mysqli_fetch_array($query);
				$chart_data = '';
				$temp_id = array();
				$temp = null;
				$count = 0;
				$count2 = 0;
				while ($get_item_id = mysqli_fetch_assoc($query)) {
					$temp_id[$count] = $get_item_id['itemID'];
					$count++;
				}
				sort($temp_id);
				for($x = 0; $x < sizeof($temp_id); $x++){
					if(isset($_POST['bong'])){
						if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
							
						}else{
							$date1 = $_POST['date_filter'];
							$date2 = $_POST['date_filter2']." 23:59:59";
							$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
							$fetch2 = mysqli_fetch_array($query2);
							//$query3 = mysqli_query($connection,"SELECT SUM(qty) as total FROM out_inventory where itemID='$temp_id[$x]' AND date_out BETWEEN '$_POST[date_filter]' AND '$_POST[date_filter2]'");
							$qty = 0;
							$query4 = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$temp_id[$x]' AND date_time BETWEEN '$date1' AND '$date2' ORDER BY date_time DESC");
							while($row = mysqli_fetch_array($query4)){
								$qty += $row['qty']; 
								
							}	
							if($temp != $temp_id[$x]){
								if($qty>0){
									$chart_data .="{ item: '$fetch2[item_name]', qty: '$qty'},";
								}
							}
							$temp = $temp_id[$x];
						}
					}
					else{
						$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
						$fetch2 = mysqli_fetch_array($query2);
						$query3 = mysqli_query($connection,"SELECT SUM(qty) as total FROM item_logs where itemID='$temp_id[$x]'");
						$fetch3 = mysqli_fetch_array($query3);
						$sum = $fetch3['total'];
						if($temp != $temp_id[$x]){
							$chart_data .="{ item: '$fetch2[item_name]', qty: '$sum'},";
						}
						$temp = $temp_id[$x];
					}
				}
		?>

			<div id="myfirstchart">
				<script type="text/javascript">
						new Morris.Bar({
					  element: 'myfirstchart',
					  data: [<?php echo $chart_data;?>],
					  xkey: 'item',
					  ykeys: ['qty'],
					  labels: ['Total Quantity']
					});
				</script>
			</div>
		</div>
	</div>
</div>
<?php
	}else if(($_GET['stats'])==2){
?>
		<div id="stats">
			<div>
				<h4 style="text-align: center;margin:1%;">
					In Transaction
				<h4>
			</div>
			<div id="go">
				<form method="post">
					<label>From:</label>
					<input type="date" name="date_filter">
					<label>To:</label>
					<input type="date" name="date_filter2">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;"></button>
				</form>
			</div>
			<div id="clear">
				<form action="controller.php?stats&stats=2" method="post">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;"></button>
				</form>
			</div>
<?php
				if(isset($_POST['bong'])){
?>
				<div>
					<h4 style="text-align: center;">
<?php
						$date = date('F j, Y',strtotime($_POST['date_filter']));
						echo $date;
?>
						TO
<?php
						$date = date('F j, Y',strtotime($_POST['date_filter2']));
						echo $date;
?>
						</h4>
				</div>
<?php
				}
?>
			
		<?php
				$query = mysqli_query($connection,"SELECT * FROM in_inventory");
				$fetch = mysqli_fetch_array($query);
				$chart_data = '';
				$temp_id = array();
				$temp = null;
				$count = 0;
				$count2 = 0;
				while ($get_item_id = mysqli_fetch_assoc($query)) {
					$temp_id[$count] = $get_item_id['itemID'];
					$count++;
				}
				sort($temp_id);
				for($x = 0; $x < sizeof($temp_id); $x++){
					if(isset($_POST['bong'])){
						if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
?>
							<script type='text/javascript'>
								window.onload = function(){
									alert("Empty Table Get Item First!");
									location = "controller.php?admin_home&catID=<?php echo $categoryid; ?>";
								}
							</script>";
<?php
						}else{
							$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
							$fetch2 = mysqli_fetch_array($query2);
							$qty = 0;
							$query4 = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$temp_id[$x]' AND date_in BETWEEN '$_POST[date_filter]' AND '$_POST[date_filter2]'");
							while($row = mysqli_fetch_array($query4)){
								$qty += $row['qty'];	
							}	
							if($temp != $temp_id[$x]){
								if($qty>0){
									$chart_data .="{ item: '$fetch2[item_name]', qty: '$qty'},";
								}
							}
							$temp = $temp_id[$x];
						}
					}else{
						$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
						$fetch2 = mysqli_fetch_array($query2);
						$query3 = mysqli_query($connection,"SELECT SUM(qty) as total FROM in_inventory where itemID='$temp_id[$x]'");
						$fetch3 = mysqli_fetch_array($query3);
						$sum = $fetch3['total'];
						if($temp != $temp_id[$x]){
							$chart_data .="{ item: '$fetch2[item_name]', qty: '$sum'},";
						}
						$temp = $temp_id[$x];
					}
				}
		?>

			<div id="myfirstchart">
				<script type="text/javascript">
						new Morris.Bar({
					  element: 'myfirstchart',
					  data: [<?php echo $chart_data;?>],
					  xkey: 'item',
					  ykeys: ['qty'],
					  labels: ['Total Quantity']
					});
				</script>
			</div>
		</div>
	</div>
</div>
<?php
	}else{

?>
		<div id="stats">
			<div>
				<h4 style="text-align: center;margin:1%;">
					Total quantity left
				<h4>
			</div>

<?php
		if(isset($_GET['page'])){
			if($_GET['page']==1){
				$result = mysqli_query($connection,"SELECT * FROM item");
				$chart_data = '';
				$temp_id = array();
				$temp = null;
				$count = 0;

				while ($get_item_id = mysqli_fetch_assoc($result)) {
					$temp_id[$count] = $get_item_id['itemID'];
					$count++;
				}
				sort($temp_id);
				for($x = 0; $x < (sizeof($temp_id)/2); $x++){
					$sum = 0;
					$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
					$fetch2 = mysqli_fetch_array($query2);
					$query3 = mysqli_query($connection,"SELECT SUM(qty) as total FROM item_stock where itemID='$temp_id[$x]'");
					//$fetch3 = mysqli_fetch_array($query3);
					while($row = mysqli_fetch_array($query3)){
						$sum += $row['total'];
					}
					if($temp != $temp_id[$x]){
						$chart_data .="{ item: '".$fetch2['item_name']."', qty: '".$sum."'},";
					}

					$temp = $temp_id[$x];
				}
			}
			else{
				$query = mysqli_query($connection,"SELECT * FROM item");
				$fetch = mysqli_fetch_array($query);
				$chart_data = '';
				$temp_id = array();
				$temp = null;
				$count = 0;
				while ($get_item_id = mysqli_fetch_assoc($query)){
					$temp_id[$count] = $get_item_id['itemID'];
					$count++;
				}
				$half = (sizeof($temp_id)/2);
				for($x = $half; $x < sizeof($temp_id); $x++){
					$sum = 0;
					$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$temp_id[$x]'");
					$fetch2 = mysqli_fetch_array($query2);
					$query3 = mysqli_query($connection,"SELECT SUM(qty) as total FROM item_stock where itemID='$temp_id[$x]'");
					while($row = mysqli_fetch_array($query3)){
						$sum += $row['total'];
					}
					if($temp != $temp_id[$x]){
						$chart_data .="{ item: '".$fetch2['item_name']."', qty: '".$sum."'},";
					}

					$temp = $temp_id[$x];
				}
			}
		}
?>
			<div id="myfirstchart">
				<script type="text/javascript">
					new Morris.Bar({
					  element: 'myfirstchart',
					  data: [<?php echo $chart_data;?>],
					  xkey: 'item',
					  ykeys: ['qty'],
					  labels: ['Total Quantity']
					});
				</script>
			</div>
			<div id="nextprevious">
<?php
				if(isset($_GET['page'])){
					if($_GET['page']==1){
?>
						<a href="controller.php?stats&stats=3&page=2" class="next">&#8250;</a>
<?php
					}else{
?>
						<a href="controller.php?stats&stats=3&page=1" class="previous">&#8249;</a>
<?php
					}
				}
?>
				
			</div>
		</div>
	</div>
</div>


<?php
	}
}
?>