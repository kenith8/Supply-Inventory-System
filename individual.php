<?php
ob_start();
include_once("dbconnection.php");

if(!(isset($_SESSION["userID"]))){
	HEADER("location:index.php");
}
?>
		<div>
			<h4 style="color:#2b580c;text-align: center;">Out Transactions (Individual) </h4>
		</div>
		<div id="transact_logs">
			<div id="go">
				<form method="post">
					<label>From</label>
					<input type="date" name="date_filter">
					<label>To</label>
					<input type="date" name="date_filter2">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspDate&nbsp</button>
				</form>
			</div>
			<div id="clear">
				<form action="controller.php?individual" method="post">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="clear" ><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspClear Filter&nbsp</button>
				</form>
			</div>
			<div id="search" style="width: 30%;">
				<form action="controller.php?individual" method="post">
					<input id="textfield" type="text" name="valueToSearch" placeholder="User ID OR OUT ID No">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="search"><img src="images/icons/search.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspSearch&nbsp</button>
				</form>
			</div>
			<div id="clear">
				<form action="controller.php?transaction_logs&logid=1" method="post">
					<button style="background-color:#68a225; border:none;border-radius:4px;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspOut&nbsp</button>
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
		</div>
		<div id="admin-function">
			<div id="table-wrapper">
<?php
				if(isset($_POST['search'])){
					$_POST['date_filter']=NULL;
					$_POST['date_filter2']=NULL;
?>
					<table id="process-manager-table">
						<tr>
							<th>#</th>
							<th>Out ID</th>
							<th>User ID</th>
							<th>Date</th>
							<th>Time</th>

						</tr>
						<?php
						$searchresult=$_POST['valueToSearch'];
						$results= mysqli_query($connection,"SELECT * FROM user_out_inventory WHERE CONCAT(icsNO,userID) LIKE '%".$searchresult."%'");
						if(mysqli_num_rows($results)==NULL){
							?>
							<script type='text/javascript'>
								window.onload = function(){
									alert('No DATA found');
									location = "controller.php?individual";
								}
							</script>
							<?php
						}else{
							$temp="";
							$counter=1;
							while($row = mysqli_fetch_assoc($results)){
								if($row['icsNO'] != $temp){	
									$date = date('F j, Y',strtotime($row['date_out']));
									?>
									<tr style=" ">
										<td><?php echo $counter;?></td>
										<td style=" "><a href="controller.php?individual&ind=1&view=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
										<td style=" "><?php echo $row['userID']; ?></td>
										<td style=" "><?php echo $date; ?></td>
										<td style=" "><?php echo $row['time_out'] ; ?></td>
									</tr>
<?php		
									$counter++;
								}
								
								$temp = $row['icsNO'];
							}
						}
?>
					</table>

<?php
				}else if(isset($_POST['bong'])) { //endsearch
					if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
?>
						<script type='text/javascript'>
							window.onload = function(){
								alert('Fill up the 2 date');
								location = "controller.php?individual";
							}
						</script>
<?php
					}else{
?>
						<table id="process-manager-table">
							<tr>
								<th>#</th>
								<th>ICS No./Out ID</th>
								<th>User ID</th>
								<th colspan="2">Date / Time</th>
							</tr>
<?php
							$results = mysqli_query($connection,"SELECT * FROM user_out_inventory where date_out BETWEEN '$_POST[date_filter]' AND '$_POST[date_filter2]' ORDER BY date_out");
							if(mysqli_num_rows($results)==NULL){
?>
								<script type='text/javascript'>
									window.onload = function(){
										alert('No DATA found');
										location = "controller.php?individual";
									}
								</script>
<?php
							}
							$temp="";
							$counter=1;
							while($row = mysqli_fetch_assoc($results)){
								if($row['icsNO'] != $temp){	
									$date = date('F j, Y',strtotime($row['date_out']));
?>
									<tr style=" ">
										<td><?php echo $counter; ?></td>
										<td style=" "><a href="controller.php?individual&view=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
										<td style=" "><?php echo $row['userID']; ?></td>
										<td style=" "><?php echo $date; ?></td>
										<td style=" "><?php echo $row['time_out'] ; ?></td>
									</tr>
<?php
									$counter++;
								}
								$temp = $row['icsNO'];
							}
						}
?>
					</table>
<?php
			}elseif (isset($_GET['view'])){
					$_POST['date_filter']=NULL;
					$_POST['date_filter2']=NULL;
					$out_inventoryquery = mysqli_query($connection,"SELECT * FROM user_out_inventory where icsNO='$_GET[view]'");
					$counter=1;
?>
					<table id="process-manager-table">
						<tr>
							<th>#</th>
							<th>Item ID</th>
							<th>Item Name</th>
							<th>Qty.</th>
						</tr>
						<?php
						while ($out_inventoryqueryfetch = mysqli_fetch_array($out_inventoryquery)){
							$itemquery = mysqli_query($connection, "SELECT * FROM item where itemID='$out_inventoryqueryfetch[itemID]'");
							$itemfetch = mysqli_fetch_array($itemquery);
							?>
							<tr style=" ">
								<td><?php echo $counter;?></td>
								<td style=" "><?php echo $out_inventoryqueryfetch['itemID']; ?></td>
								<td style=" "><?php echo $itemfetch['item_name']; ?></td>
								<td style=" "><?php echo $out_inventoryqueryfetch['qty']; ?></td>
							</tr>
							<?php    
							$counter++;
						}
						?>
					</table>
<?php
			}else{

							$query6 = mysqli_query($connection,"SELECT * FROM user_out_inventory");
							$counter = 1;
							$temp="";
							$counter=1;
?>
							<table id="process-manager-table">
								<tr>
									<th>#</th>
									<th>Out ID</th>
									<th>User ID</th>
									<th colspan="2">Date / Time</th>
								</tr>
<?php
							while($row = mysqli_fetch_array($query6)){
								if($row['icsNO'] != $temp){
									$date = date('F j, Y',strtotime($row['date_out']));
?>
									<tr>
										<td><?php echo $counter;?></td>
										<td><a href="controller.php?individual&view=<?php echo $row['icsNO'];?>"><?php echo $row['icsNO'];?></a></td>
										<td><?php echo $row['userID'];?></td>
										<td><?php echo $row['date_out'];?></td>
										<td><?php echo $row['time_out'];?></td>
									</tr>
<?php
									$counter++;
								}
								$temp = $row['icsNO'];
							}
?>
						</table>
<?php
						
				}//end default table
?>
				</div>
		</div>
</div>