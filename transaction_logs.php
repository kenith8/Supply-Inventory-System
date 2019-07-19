<?php
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}
?>
				<div id="transact-logs-wrapper">
					<div id="transact-logs-filter">
						<div id="go">
							<form method="post">
								<input type="date" name="date_filter">
									<input type="submit" name="bong" value="Go">
								</form>
						</div>
						<div id="clear">
							<form action="controller.php?transaction_logs" method="post">
								<input type="submit" name="clear" value="Clear Filter">
							</form>
						</div>
						<div id="search">
							<form action="controller.php?transaction_logs" method="post">
								<input id="textfield" type="text" name="valueToSearch" placeholder="User ID and ICS No.">
								<input id="searchbtn" type="submit" name="search" value="Search">
							</form>
						</div>
					</div>
					<div id="information">
						<div id="ics">
							<h4>
							<?php 
								if(isset($_GET['ics'])){
									$uidquery = mysqli_query($connection,"SELECT * FROM out_inventory where icsNO='$_GET[ics]'");
									$uidfetch = mysqli_fetch_array($uidquery);
									echo "<label>ICS No.:</label>";
									echo $_GET['ics']."";
						 	?>
						 	</h4>
						</div>
						<div id="uid">
							<h4>
						 	<?php
						 			echo "<label>UserID:</label>";
						 			echo $uidfetch['userID'];		
						 	?>
							</h4>
						</div>
						<div id="date_out">
							<h4>
						 	<?php
						 			echo "<label>Date:</label>";
						 			echo $uidfetch['date_out'];
						 			echo "     ";
						 			echo $uidfetch['time_out'];	
						 		}	
						 	?>
							</h4>
						</div>
					</div>
						<div id="admin-function">
							<div id="table-wrapper">
								<?php
									if(isset($_POST['search'])){
								?>
										<table id="process-manager-table">
											<tr>
												<th>ICS No.</th>
												<th>User ID</th>
												<th>Date</th>
												<th>Time</th>
											</tr>
									<?php
											$searchresult=$_POST['valueToSearch'];
											$results= mysqli_query($connection,"SELECT * FROM out_inventory WHERE CONCAT(icsNO,userID) LIKE '%".$searchresult."%'");
											if(mysqli_num_rows($results)==NULL){
												echo "DATA NOT FOUND";
											}
											else{
												$temp="";
												while($row = mysqli_fetch_assoc($results)){
													if($row['icsNO'] != $temp){	
									?>
														<tr style="border: 1px solid black;">
															<td style="border: 1px solid black;"><a href="controller.php?transaction_logs&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
															<td style="border: 1px solid black;"><?php echo $row['userID']; ?></td>
															<td style="border: 1px solid black;"><?php echo $row['date_out']; ?></td>
															<td style="border: 1px solid black;"><?php echo $row['time_out'] ; ?></td>
														</tr>
									<?php		
													}
													$temp = $row['icsNO'];
												}
											}
									?>
											</table>
									<?php
										}
										else if(isset($_POST['bong'])){
									?>
											<table id="process-manager-table">
												<tr>
													<th>ICS No.</th>
													<th>User ID</th>
													<th>Date</th>
													<th>Time</th>
												</tr>
									<?php
											$results = mysqli_query($connection,"SELECT * FROM out_inventory where date_out='$_POST[date_filter]'");
											if(mysqli_num_rows($results)<=0){
													echo "<script type='text/javascript'>
														window.onload = function(){
														alert('No DATA found');
														location = 'controller.php?transaction_logs';}
														</script>";
											}
											$temp="";
											while($row = mysqli_fetch_assoc($results)){
												if($row['icsNO'] != $temp){	
									?>
												<tr style="border: 1px solid black;">
													<td style="border: 1px solid black;"><a href="controller.php?transaction_logs&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
													<td style="border: 1px solid black;"><?php echo $row['userID']; ?></td>
													<td style="border: 1px solid black;"><?php echo $row['date_out']; ?></td>
													<td style="border: 1px solid black;"><?php echo $row['time_out'] ; ?></td>
												</tr>
									<?php
												}
												$temp = $row['icsNO'];
											}
									?>
											</table>
									<?php
										}
										else if(isset($_GET['ics'])){
											$out_inventoryquery = mysqli_query($connection,"SELECT * FROM out_inventory where icsNO='$_GET[ics]'");
									?>
											<table id="process-manager-table">
													<tr>
														<th>Item ID</th>
														<th>Item Name</th>
														<th>Qty.</th>
													</tr>
									<?php
												while ($out_inventoryqueryfetch = mysqli_fetch_array($out_inventoryquery)){
													$itemquery = mysqli_query($connection, "SELECT * FROM item where itemID='$out_inventoryqueryfetch[itemID]'");
													$itemfetch = mysqli_fetch_array($itemquery);
									?>
													<tr style="border: 1px solid black;">
														<td style="border: 1px solid black;"><?php echo $out_inventoryqueryfetch['itemID']; ?></td>
														<td style="border: 1px solid black;"><?php echo $itemfetch['item_name']; ?></td>
														<td style="border: 1px solid black;"><?php echo $out_inventoryqueryfetch['qty']; ?></td>
													</tr>
									<?php
												}
									?>
											</table>
									<?php
										}
										else{
									?>
											<table id="process-manager-table">
												<tr>
													<th>ICS No.</th>
													<th>User ID</th>
													<th>Date</th>
													<th>Time</th>
												</tr>
									<?php
											$results = mysqli_query($connection,"SELECT * FROM out_inventory");
											$temp="";
											while($row = mysqli_fetch_assoc($results)){
												if($row['icsNO'] != $temp){	
									?>
													<tr style="border: 1px solid black;">
														<td style="border: 1px solid black;"><a href="controller.php?transaction_logs&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
														<td style="border: 1px solid black;"><?php echo $row['userID']; ?></td>
														<td style="border: 1px solid black;"><?php echo $row['date_out']; ?></td>
														<td style="border: 1px solid black;"><?php echo $row['time_out'] ; ?></td>
													</tr>
									<?php
												}
												$temp = $row['icsNO'];	
											}
									?>
											</table>
									<?php
										}
									?>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- center clossing tag -->
		</div> <!-- sidebar-container closing tag -->