<?php
ob_start();
include_once("dbconnection.php");

if(!(isset($_SESSION["userID"]))){
	HEADER("location:index.php");
}
?>
				<div class="bg-form">
					<div id="individual-bg-wrapper">
						<div>
							<h4 style="color:#2b580c;text-align: center;">Out Transactions (Individual) </h4>
						</div>
						<div id="transact_logs">
							<div id="go" class="form-group">
								<form class="form-inline" method="post">
									<label>From</label>
									<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter">
									<label>To</label>
									<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter2">
									<button class="btn" style="background-color:#68a225;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspDate&nbsp</button>
								</form>
							</div>
							<div id="clear" class="form-group">
								<form action="controller.php?individual" method="post">
									<button class="btn" style="background-color:#68a225;" type="submit" name="clear" ><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspClear Filter&nbsp</button>
								</form>
							</div>
							<div id="search" class="form-group">
								<form class="form-inline" action="controller.php?individual" method="post">
									<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="User ID OR OUT ID No">
									<button class="btn" style="background-color:#68a225;" type="submit" name="search"><img src="images/icons/search.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspSearch&nbsp</button>
								</form>
							</div>
							<div id="indiv" class="form-group">
								<form action="controller.php?transaction_logs&logid=1" method="post">
									<button class="btn" style="background-color:#68a225; padding:20%; margin-top:5%;" type="submit" name="clear">&nbspOut&nbsp</button>
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
										$date = date('F j, Y h:i:sa',strtotime($_POST['date_filter2']));
										echo $date;
				?>
									</h4>
								</div>
				<?php
							}
				?>
						</div>
						<div id="information">
							<div id="ics">
								<h4>
									<?php 
									if(isset($_GET['view'])){
										if(isset($_POST['bong']) || isset($_POST['search'])){
											$_GET['view']=NULL;
										}else{
											$uidquery = mysqli_query($connection,"SELECT * FROM user_out_inventory where icsNO='$_GET[view]' ORDER BY date_time DESC");
											$uidfetch = mysqli_fetch_array($uidquery);
											$name = mysqli_query($connection,"SELECT * FROM user where userID='$uidfetch[userID]' AND accountType='user'");
											$fetchname = mysqli_fetch_array($name);
											echo "<label>Transaction Out ID:</label>";
											echo $_GET['view']."";
											?>
										</h4>
									</div>
									<div id="uid">
										<h4>
											<?php
											echo "<label>Name:</label>";
											echo $fetchname['name'];		
											?>
										</h4>
									</div>
									<div id="date_time">
										<h4>
									<?php
											echo "<label>Date:</label>";
											echo $uidfetch['date_time'];
										}
									}	
									?>
								</h4>
							</div>
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
											<th>Date / Time</th>

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
													$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
													?>
													<tr style=" ">
														<td><?php echo $counter;?></td>
														<td style=" "><a href="controller.php?individual&ind=1&view=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
														<td style=" "><?php echo $row['userID']; ?></td>
														<td style=" "><?php echo $date; ?></td>
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
								}
								else if(isset($_POST['bong'])) { //endsearch
									if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
				?>
										<script type='text/javascript'>
											window.onload = function(){
												alert('Fill up the 2 date');
												location = "controller.php?individual&logid=<?php echo $_GET['logid'];?>";
											}
										</script>
				<?php
									}
									else{
										$date1 = $_POST['date_filter'];
										$date2 = $_POST['date_filter2']." 23:59:59";
				?>
										<table id="process-manager-table">
											<tr>
												<th>#</th>
												<th>Transaction Out ID</th>
												<th>User ID</th>
												<th colspan="2">Date / Time</th>
											</tr>
				<?php
											$results = mysqli_query($connection,"SELECT * FROM user_out_inventory where date_time BETWEEN '$date1' AND '$date2' ORDER BY date_time DESC");
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
													$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
				?>
													<tr style=" ">
														<td><?php echo $counter; ?></td>
														<td style=" "><a href="controller.php?individual&view=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
														<td style=" "><?php echo $row['userID']; ?></td>
														<td style=" "><?php echo $date; ?></td>
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
							}
							else if (isset($_GET['view'])){
									$_POST['date_filter']=NULL;
									$_POST['date_filter2']=NULL;
									$out_inventoryquery = mysqli_query($connection,"SELECT * FROM user_out_inventory where icsNO='$_GET[view]' ORDER BY date_time DESC");
									$counter=1;
				?>
									<table id="process-manager-table">
										<tr>
											<th>#</th>
											<th>ICS/RIS No.</th>
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
												<td><?php echo $out_inventoryqueryfetch['ics_ris']; ?></td>
												<td><?php echo $out_inventoryqueryfetch['itemID']; ?></td>
												<td><?php echo $itemfetch['item_name']; ?></td>
												<td><?php echo $out_inventoryqueryfetch['qty']; ?></td>
											</tr>
											<?php    
											$counter++;
										}
										?>
									</table>
				<?php
							}
							else{
								$query6 = mysqli_query($connection,"SELECT * FROM user_out_inventory ORDER BY date_time DESC");
								$counter = 1;
								$temp = "";
								$counter = 1;
	?>
								<table id="process-manager-table">
									<tr>
										<th>#</th>
										<th>Transaction Out ID</th>
										<th>User ID</th>
										<th colspan="2">Date / Time</th>
									</tr>
	<?php
								while($row = mysqli_fetch_array($query6)){
									if($row['icsNO'] != $temp){
										$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
	?>
										<tr>
											<td><?php echo $counter;?></td>
											<td><a href="controller.php?individual&view=<?php echo $row['icsNO'];?>"><?php echo $row['icsNO'];?></a></td>
											<td><?php echo $row['userID'];?></td>
											<td><?php echo $date;?></td>
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
					</div> <!-- individual-bg-wrapper -->
				</div> <!-- bg-form closing tag -->
			</div>	<!-- center closing tag -->
		</div> <!-- sidebar-container closing tag -->