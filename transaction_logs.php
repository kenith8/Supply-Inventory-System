<?php
	ob_start();
	include_once("dbconnection.php");

	if(!(isset($_SESSION["userID"]))){
		HEADER("location:index.php");
	}


	if(isset($_GET['logid'])){
?>
	<div class="bg-form">
<?php
		if(($_GET['logid'])==1){
?>
		<div id="logid-1-bg-wrapper">
			<div>
				<h4 style="color:#2b580c;text-align: center;">Out Transactions</h4>
			</div>
			<div id="transact_logs">
				<div id="go" class="form-group">
					<form class="form-inline" method="post">
						<label>From: </label>
						<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter">
						<label>To: </label>
						<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter2">
						<button class="btn" style="background-color:#68a225;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width:30px;">&nbspDate&nbsp</button>
					</form>
				</div>
				<div id="clear" class="form-group">
					<form action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
						<button class="btn" style="background-color:#68a225;" type="submit" name="clear" ><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width:30px;">&nbspClear Filter&nbsp</button>
					</form>
				</div>
				<div id="search" class="form-group">
					<form class="form-inline" action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
						<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="User ID OR Transaction Out ID.">
						<button class="btn" style="background-color:#68a225;" type="submit" name="search"><img src="images/icons/search.png" style="background-color:#68a225;height:30px;width:30px;">&nbspSearch&nbsp</button>
					</form>
				</div>
				<div id="indiv" class="form-group">
					<form action="controller.php?individual" method="post">
						<button class="btn" style="background-color:#68a225; padding:10%; margin-top:5%;" type="submit" name="clear">&nbspIndividual&nbsp</button>
					</form>
				</div>
			</div>
			<div id="date-output-wrapper">
		<?php
			if(isset($_POST['bong'])){
		?>
				<div>
					<h4 style="text-align: center;">
		<?php
					$date1 = date('F j, Y h:i:sa',strtotime($_POST['date_filter']));
					echo $date1;
		?>
					TO
		<?php
					$date2 = date('F j, Y h:i:sa',strtotime($_POST['date_filter2']." 23:59:59"));
					echo $date2;
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
				if(isset($_GET['ics'])){
					if(isset($_POST['bong']) || isset($_POST['search'])){
						$_GET['ics']=NULL;
					}else{
						$uidquery = mysqli_query($connection,"SELECT * FROM out_inventory where icsNO='$_GET[ics]'");
						$uidfetch = mysqli_fetch_array($uidquery);
						$name = mysqli_query($connection,"SELECT * FROM user where userID='$uidfetch[userID]' AND accountType='admin'");
						$fetchname = mysqli_fetch_array($name);
						echo "<label>Transaction Out ID:&nbsp&nbsp$_GET[ics]</label>";
						?>
					</h4>
				</div>
				<div id="uid">
					<h4>
						<?php
						echo "<label>Name:&nbsp&nbsp$fetchname[name]</label>";
						?>
					</h4>
				</div>
				<div id="date_time">
					<h4>
				<?php
						echo "<label>Date:&nbsp&nbsp$uidfetch[date_time]</label>";
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
								<th>Transaction Out ID</th>
								<th>User ID</th>
								<th>Date / Time</th>

							</tr>
							<?php
							$searchresult=$_POST['valueToSearch'];
							$results= mysqli_query($connection,"SELECT * FROM out_inventory WHERE CONCAT(icsNO,userID) LIKE '%".$searchresult."%'");
							if(mysqli_num_rows($results)==NULL){
								?>
								<script type='text/javascript'>
									window.onload = function(){
										alert('No DATA found');
										location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>";
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
											<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
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
					else if(isset($_POST['bong'])){
						if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
							?>
							<script type='text/javascript'>
								window.onload = function(){
									alert('Fill up the 2 date');
									location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>";
								}
							</script>
							<?php
						}else{
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
								$results = mysqli_query($connection,"SELECT * FROM out_inventory where date_time BETWEEN '$date1' AND '$date2' ORDER BY date_time DESC");
								if(mysqli_num_rows($results)==NULL){
									?>
									<script type='text/javascript'>
										window.onload = function(){
											alert('No DATA found');
											location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
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
											<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
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
					else if(isset($_GET['ics'])){
						$_POST['date_filter']=NULL;
						$_POST['date_filter2']=NULL;
						$out_inventoryquery = mysqli_query($connection,"SELECT * FROM out_inventory where icsNO='$_GET[ics]' ORDER BY date_time DESC");
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
	?>
						<table id="process-manager-table">
							<tr>
								<th>#</th>
								<th>Transaction Out ID</th>
								<th>User ID</th>
								<th colspan="2">
									Date / Time
								</th>
							</tr>
	<?php
							$results = mysqli_query($connection,"SELECT * FROM out_inventory ORDER BY date_time DESC");
							$temp="";
							$counter=1;
							while($row = mysqli_fetch_assoc($results)){
								if($row['icsNO'] != $temp){	
									$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
	?>
									<tr style=" ">
										<td><?php echo $counter; ?></td>
										<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&ics=<?php echo $row['icsNO']; ?>"><?php echo $row['icsNO']; ?></a></td>
										<td style=" "><?php echo $row['userID']; ?></td>
										<td style=" "><?php echo $date; ?></td>
									</tr>
	<?php
									$counter++;
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
		</div> <!-- logid-1-bg-wrapper -->
	

	<?php
	}else if($_GET['logid']==2){ //logid=2
		?>
		
			<div id="logid-2-bg-wrapper">
				<div>
					<h4 style="color:#2b580c;text-align: center;">In Transactions</h4>
				</div>
				<div id="transact_logs">
					<div id="go" class="form-group">
						<form class="form-inline" method="post">
							<label>From:</label>
							<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter">
							<label>To:</label>
							<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter2">
							<button class="btn" style="background-color:#68a225;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspDate&nbsp</button>
						</form>
					</div>
					<div id="clear" class="form-group">
						<form action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
							<button class="btn" style="background-color:#68a225;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspClear Filter&nbsp</button>
						</form>
					</div>
					<div id="search" class="form-group">
						<form class="form-inline" action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
							<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="User ID OR Transaction Out ID.">
							<button class="btn" style="background-color:#68a225;" type="submit" name="search"><img src="images/icons/search.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspSearch&nbsp</button>
						</form>
					</div>
				</div>
				<div id="date-output-wrapper">
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
				<div id="information">
					<div id="ics">
						<h4>
					<?php 
					if(isset($_GET['risNO'])){
						if(isset($_POST['bong'])|| isset($_POST['search'])){
							$_GET['risNO']=NULL;
						}else{
							$uidquery = mysqli_query($connection,"SELECT * FROM in_inventory where risNO='$_GET[risNO]'");
							$uidfetch = mysqli_fetch_array($uidquery);
							$name = mysqli_query($connection,"SELECT * FROM user WHERE userID = '$uidfetch[userID]'");
							$fetchname = mysqli_fetch_array($name);
							echo "<label>ICS/RIS No.:&nbsp&nbsp$uidfetch[risNO]</label>";
							?>
						</h4>
					</div>
					<div id="uid">
						<h4>
							<?php
							echo "<label>Name:&nbsp&nbsp$fetchname[name]</label>";
							?>
						</h4>
					</div>
					<div id="date_in">
						<h4>
					<?php
							echo "<label>Date:&nbsp&nbsp$uidfetch[date_in]</label>";
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
							?>
							<table id="process-manager-table">
								<tr>
									<th>#</th>
									<th>ICS/RIS No.</th>
									<th>User ID</th>
									<th colspan="2">Date</th>
								</tr>
								<?php
								$searchresult=$_POST['valueToSearch'];
								$results = mysqli_query($connection,"SELECT * FROM in_inventory where CONCAT(risNO,userID) LIKE '%".$searchresult."%'");
								if(mysqli_num_rows($results)==NULL){
									?>
									<script type='text/javascript'>
										window.onload = function(){
											alert('No DATA found');
											location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>";
										}
									</script>
									<?php
								}else{
									$temp="";
									$counter=1;
									while($row = mysqli_fetch_assoc($results)){
										if($row['risNO'] != $temp){	
											?>
											<tr style=" ">
												<td><?php echo $counter; ?></td>
												<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&risNO=<?php echo $row['risNO']; ?>"><?php echo $row['risNO']; ?></a></td>
												<td style=" "><?php echo $row['userID']; ?></td>
												<td style=" text-align: right;"><?php echo $row['date_in']; ?></td>
												<td style=" text-align: left;"><?php echo $row['time_in']; ?></td>
											</tr>
											<?php
											$counter++;
										}
										$temp = $row['risNO'];
									}
								}
								?>
							</table>
							<?php
						}else if(isset($_POST['bong'])){
							$_GET['risNO'] = "";
							?>
							<table id="process-manager-table">
								<tr>
									<th>#</th>
									<th>ICS/RIS No.</th>
									<th>User ID</th>
									<th colspan="2">Date</th>

								</tr>
								<?php
								$results = mysqli_query($connection,"SELECT * FROM in_inventory where date_in BETWEEN '$_POST[date_filter]' AND '$_POST[date_filter2]' ORDER BY date_in DESC");
								if(mysqli_num_rows($results)==NULL){
									?>
									<script type='text/javascript'>
										window.onload = function(){
											alert('No DATA found');
											location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
										}
									</script>
									<?php
								}
								$temp="";
								$counter=1;
								while($row = mysqli_fetch_assoc($results)){
									if($row['risNO'] != $temp){	
										$date = date('F j, Y',strtotime($row['date_in']));
										?>
										<tr style=" ">
											<td><?php echo $counter; ?></td>
											<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&risNO=<?php echo $row['risNO']; ?>"><?php echo $row['risNO']; ?></a></td>
											<td style=" "><?php echo $row['userID']; ?></td>
											<td style=""><?php echo $date; ?></td>
										</tr>
										<?php
										$counter++;
									}
									$temp = $row['risNO'];
								}
								?>
							</table>
							<?php
						}else if(isset($_GET['risNO'])){
							$in_inventoryquery = mysqli_query($connection,"SELECT * FROM in_inventory where risNO='$_GET[risNO]' ORDER BY date_in DESC");
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
								while ($in_inventoryfetch = mysqli_fetch_array($in_inventoryquery)){
									$query = mysqli_query($connection,"SELECT * FROM item where itemID='$in_inventoryfetch[itemID]'");
									$fetch = mysqli_fetch_array($query);
									?>
									<tr style=" ">
										<td><?php echo $counter; ?></td>
										<td style=" "><?php echo $in_inventoryfetch['itemID']; ?></td>
										<td style=" "><?php echo $fetch['item_name']; ?></td>
										<td style=" "><?php echo $in_inventoryfetch['qty']; ?></td>
									</tr>
									<?php
									$counter++;
								}
								?>
							</table>
							<?php
						}else{
							?>
							<table id="process-manager-table">
								<tr>
									<th>#</th>
									<th>ICS/RIS No.</th>
									<th>User ID</th>
									<th colspan="2">
										Date
									</th>
								</tr>
								<?php
								$results = mysqli_query($connection,"SELECT * FROM in_inventory ORDER BY date_in DESC");
								$temp="";
								$counter=1;
								while($row = mysqli_fetch_assoc($results)){
									if($row['risNO'] != $temp){	
										$date = date('F j, Y',strtotime($row['date_in']));
										?>
										<tr style=" ">
											<td><?php echo $counter; ?></td>
											<td style=" "><a href="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&risNO=<?php echo $row['risNO']; ?>"><?php echo $row['risNO']; ?></a></td>
											<td><?php echo $row['userID']; ?></td>
											<td><?php echo $date; ?></td>
										</tr>
										<?php
										$counter++;
									}
									$temp = $row['risNO'];	
								}
								?>
							</table>
							<?php
						}

						?>		
					</div>
				</div>
			</div> <!-- logid-2-bg-wrapper -->
		</div> <!--bg-form closing tag-->
	<?php
	}else if($_GET['logid']==3){ //logid=3
		echo "<div id=\"logid-3-bg-wrapper\">";
		if(isset($_GET['category'])){
			$query = mysqli_query($connection,"SELECT * FROM category where catID='$_GET[category]'");
			$fetch = mysqli_fetch_array($query);
			?>
			<div>
				<h3 style="color:#2b580c;text-align:center; line-height: 1.5em;"><?php echo $fetch['supplycat'];?>&nbspLogs</h3>
			</div>
			<div id="transact_logs" class="form-group">
				<div id="go">
				<form class="form-inline" method="post">
					<label>From:</label>
					<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter">
					<label>To:</label>
					<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter2">
					<button class="btn" style="background-color:#68a225;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspDate&nbsp</button>
				</form>
			</div>
			<div id="category" class="form-group">
					<select class="form-control mb-2 mr-sm-2" onchange="location =  this.value">
						<option>Category</option>
						<?php
						$query = mysqli_query($connection,"SELECT * FROM category");
						while($row = mysqli_fetch_array($query)){

							?>
							<option value="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&category=<?php echo $row['catID'];?>"><?php echo $row['supplycat'];?></option>
							<?php
						}
						?>
					</select>
			</div>
			<div id="clear" class="form-group">
					<form action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
						<button class="btn" style="background-color:#68a225;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspClear Filter&nbsp</button>
					</form>
				</div>
				<div id="search" class="form-group">
					<form class="form-inline" action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&category=<?php echo $_GET['category'];?>" method="post">
						<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="Item Name">
						<input type="hidden" name="category" value="<?php echo $fetch['supplycat'];?>">
						<button class="btn" style="background-color:#68a225;" type="submit" name="search"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspSearch&nbsp</button>
					</form>
				</div>
			</div>
			<div id="date-output-wrapper">
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
								<th>Transaction Out ID</th>
								<th>ICS/RIS No.</th>
								<th>Category</th>
								<th>Sub-Category</th>
								<th>Item Name</th>
								<th>Qty.</th>
								<th>User</th>
								<th>Distributed to:</th>
								<th colspan="2">
									Date / Time
								</th>
							</tr>
							<?php
							$searchresult = $_POST['valueToSearch'];
							$results = mysqli_query($connection,"SELECT * FROM item_logs");
							if(mysqli_num_rows($results) == NULL){
								?>
								<script type='text/javascript'>
									window.onload = function(){
										alert('No DATA found');
										location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>";
									}
								</script>
								<?php
							}else{
								$query3 = "SELECT * FROM item WHERE catID = '$_GET[category]' AND CONCAT(itemID,catID,subcatID,item_name) LIKE '%".$searchresult."%'";
								$result3 = mysqli_query($connection, $query3);
								if(mysqli_num_rows($result3) == NULL){
								?>
								<script type='text/javascript'>
									window.onload = function(){
										alert('<?php echo $searchresult;?> item is not in this Category');
										location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&category=<?php echo $_GET['category'];?>";
									}
								</script>
								<?php
								}
								else{
									$counter=1;
									while($get_itemID = mysqli_fetch_assoc($result3)){
										$query = "SELECT supplycat FROM category WHERE catID = '$get_itemID[catID]'";
										$result = mysqli_query($connection, $query);
										$get_cat = mysqli_fetch_assoc($result);
										$query4 = "SELECT * FROM item_logs WHERE itemID = '$get_itemID[itemID]' ORDER BY date_time DESC";
										$result4 = mysqli_query($connection, $query4);
										while($get_ics = mysqli_fetch_assoc($result4)){
											$query2 = mysqli_query($connection,"SELECT * FROM category where catID='$_GET[category]'");
											$fetch2 =mysqli_fetch_array($query2);
											$query5 = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$get_itemID[subcatID]'");
											$fetch5 =mysqli_fetch_array($query5);
											$date = date('F j, Y h:i:sa',strtotime($get_ics['date_time']));
											?>
											<tr style=" ">
												<td><?php echo $counter; ?></td>
												<td style=" "><?php echo $get_ics['icsNO']; ?></td>
												<td style=" "><?php echo $get_ics['ics_ris']; ?></td>
												<td style=" "><?php echo $get_cat['supplycat']; ?></td>
												<td style=" "><?php echo $fetch5['subcatName']; ?></td>
												<td style=" "><?php echo $get_itemID['item_name']; ?></td>
												<td style=" "><?php echo $get_ics['qty']; ?></td>
												<td style=" "><?php echo $get_ics['userID']; ?></td>
												<td style=" "><?php echo $get_ics['dis_to']; ?></td>
												<td style=" "><?php echo $date; ?></td>
											</tr>
											<?php
											$counter++;
										}
									}
								}
							}
							?>
						</table>
						<?php
					}else if(isset($_POST['bong'])){
						if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
							?>
							<script type='text/javascript'>
								window.onload = function(){
									alert('Fill up the 2 date');
									location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
								}
							</script>
							<?php
						}else{
							$catname = mysqli_query($connection,"SELECT * FROM category where catID='$_GET[category]'");
							$catnamefetch = mysqli_fetch_array($catname);
							$tableID = str_replace(" ","_",$catnamefetch['supplycat']);
							?>
							<table id="<?php echo $tableID;?>">
								<tr>
									<th>#</th>
									<th>Transaction Out ID</th>
									<th>ICS/RIS No.</th>
									<th>Category</th>
									<th>Sub-Category</th>
									<th>Item Name</th>
									<th>Qty.</th>
									<th>User</th>
									<th>Distributed to:</th>
									<th colspan="2">
										Date / Time
									</th>
								</tr>
								<?php
								$date1 = $_POST['date_filter'];
								$date2 = $_POST['date_filter2']." 23:59:59";
								$results = mysqli_query($connection,"SELECT * FROM item_logs where date_time BETWEEN '$date1' AND '$date2' ORDER BY date_time DESC");
								if(mysqli_num_rows($results)==NULL){
									?>
									<script type='text/javascript'>
										window.onload = function(){
											alert('No DATA found');
											location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
										}
									</script>
									<?php
								}
								$temp="";
								$counter=1;
								while($row = mysqli_fetch_assoc($results)){
									$query = mysqli_query($connection, "SELECT * FROM item where itemID='$row[itemID]' and catID='$_GET[category]'");
									while($row2 = mysqli_fetch_array($query)){
										$query2 = mysqli_query($connection, "SELECT * FROM category where catID='$row2[catID]'");
										$fetch2 = mysqli_fetch_array($query2);
										$query3 = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$row2[subcatID]'");
										$fetch3 = mysqli_fetch_array($query3);
										$query4 = mysqli_query($connection, "SELECT * FROM item_logs where itemID='$row2[itemID]' and icsNO='$row[icsNO]'");
										$fetch4 = mysqli_fetch_array($query4);
										$date = date('F j, Y h:i:sa',strtotime($fetch4['date_time']));
										?>
										<tr>
											<td><?php echo $counter; ?></td>
											<td><?php echo $row['icsNO']; ?></td>
											<td><?php echo $row['ics_ris']; ?></td>
											<td><?php echo $fetch2['supplycat']; ?></td>
											<td><?php echo $fetch3['subcatName']; ?></td>
											<td style=" text-align:left;"><?php echo $row2['item_name']; ?></td>
											<td><?php echo $row['qty']; ?></td>
											<td style=" "><?php echo $row['userID']; ?></td>
											<td style=" "><?php echo $row['dis_to']; ?></td>
											<td style=" "><?php echo $date; ?></td>
										</tr>
										<?php
										$counter++;
									}
								}
								?>
							</table>
							<script>
								$("<?php echo '#'.$tableID;?>").tableExport();
							</script>
							<?php		
						}
					}else{
						$results = mysqli_query($connection,"SELECT * FROM item_logs ORDER BY date_time DESC");
						$query6 = mysqli_query($connection, "SELECT * FROM category where catID='$_GET[category]'");
						$fetch6 = mysqli_fetch_array($query6);
						$name = str_replace(" ","_",$fetch6['supplycat']);
						?>

						<table id="<?php echo $name;?>">
							<tr>
								<th>#</th>
								<th>Transaction Out ID</th>
								<th>ICS/RIS No.</th>
								<th>Category</th>
								<th>Sub-Category</th>
								<th>Item Name</th>
								<th>Qty.</th>
								<th>User</th>
								<th>Distributed to:</th>
								<th colspan="2">
									Date / Time
								</th>
							</tr>
							<?php
							$counter=1;
							$ics=0;
							while($row = mysqli_fetch_assoc($results)){
								$query = mysqli_query($connection, "SELECT * FROM item where itemID='$row[itemID]' and catID='$_GET[category]'");
								while($row2 = mysqli_fetch_array($query)){
									$query2 = mysqli_query($connection, "SELECT * FROM category where catID='$row2[catID]'");
									$fetch2 = mysqli_fetch_array($query2);
									$query3 = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$row2[subcatID]'");
									$fetch3 = mysqli_fetch_array($query3);
									$query4 = mysqli_query($connection, "SELECT * FROM item_logs where itemID='$row2[itemID]' and icsNO='$row[icsNO]'");
									$fetch4 = mysqli_fetch_array($query4);
									$date = date('F j, Y h:i:sa',strtotime($fetch4['date_time']));
									?>
									<tr>
										<td><?php echo $counter; ?></td>
										<td><?php echo $row['icsNO']; ?></td>
										<td><?php echo $row['ics_ris']; ?></td>
										<td><?php echo $fetch2['supplycat']; ?></td>
										<td><?php echo $fetch3['subcatName']; ?></td>
										<td style=" text-align:left;"><?php echo $row2['item_name']; ?></td>
										<td><?php echo $row['qty']; ?></td>
										<td style=" "><?php echo $row['userID']; ?></td>
										<td style=" "><?php echo $row['dis_to']; ?></td>
										<td style=" "><?php echo $date; ?></td>
									</tr>
									<?php
									$counter++;
								}
							}
							?>
						</table>
						<script>
							$("#<?php echo $name;?>").tableExport();
						</script>
						<?php
					}

					?>		
				</div>
			</div>
		<?php					
			}
			else{ //category
				?>
				<div>
					<h3 style="color:#2b580c;text-align:center; line-height: 1.5em;">Logs</h3>
				</div>
				<div id="transact_logs">
					<div id="go" class="form-group">
						<form class="form-inline" method="post">
							<label>From:</label>
							<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter">
							<label>To:</label>
							<input class="form-control mb-2 mr-sm-2" type="date" name="date_filter2">
							<button class="btn" style="background-color:#68a225;" type="submit" name="bong"><img src="images/icons/date.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspDate&nbsp</button>
						</form>
					</div>
					<div id="category" class="form-group">
						<select class="form-control mb-2 mr-sm-2" onchange="location =  this.value">
							<option>Category</option>
							<?php
							$query = mysqli_query($connection,"SELECT * FROM category");
							while($row = mysqli_fetch_array($query)){

								?>
								<option value="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>&category=<?php echo $row['catID'];?>"><?php echo $row['supplycat'];?></option>
								<?php
							}
							?>
						</select>
					</div>
					<div id="clear" class="form-group">
						<form action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
							<button class="btn" style="background-color:#68a225;" type="submit" name="clear"><img src="images/icons/clear.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspClear&nbsp</button>
						</form>
					</div>
					<div id="search" class="form-group">
						<form class="form-inline" action="controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>" method="post">
							<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="Item Name">
							<button class="btn" style="background-color:#68a225;" type="submit" name="search"><img src="images/icons/search.png" style="background-color:#68a225;height:30px;width: 30px;">&nbspSearch&nbsp</button>
						</form>
					</div>
				</div>
				<div id="date-output-wrapper">
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
									<th>Transaction Out ID</th>
									<th>ICS/RIS No.</th>
									<th>Category</th>
									<th>Sub-Category</th>
									<th>Item Name</th>
									<th>Qty.</th>
									<th>User</th>
									<th>Distributed to:</th>
									<th colspan="2">
										Date / Time
									</th>
								</tr>
								<?php
								$searchresult=$_POST['valueToSearch'];
								$results = mysqli_query($connection,"SELECT * FROM item_logs");
								if(mysqli_num_rows($results)==NULL){
									?>
									<script type='text/javascript'>
										window.onload = function(){
											alert('No DATA found');
											location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>";
										}
									</script>
									<?php
								}else{
									$counter=1;
									$query3 = "SELECT * FROM item WHERE CONCAT(itemID,catID,subcatID,item_name) LIKE '%".$searchresult."%'";
									$result3 = mysqli_query($connection, $query3);
									while($get_itemID = mysqli_fetch_assoc($result3)){
										$query4 = "SELECT * FROM item_logs WHERE itemID = '$get_itemID[itemID]' ORDER BY date_time DESC";
										$result4 = mysqli_query($connection, $query4);
										while($get_ics = mysqli_fetch_assoc($result4)){
											$query2 = mysqli_query($connection,"SELECT * FROM category where catID='$get_itemID[catID]'");
											$fetch2 =mysqli_fetch_array($query2);
											$query5 = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$get_itemID[subcatID]'");
											$fetch5 =mysqli_fetch_array($query5);
											$date = date('F j, Y h:i:sa',strtotime($get_ics['date_time']));
											?>
											<tr style=" ">
												<td><?php echo $counter; ?></td>
												<td style=" "><?php echo $get_ics['icsNO']; ?></td>
												<td style=" "><?php echo $get_ics['ics_ris']; ?></td>
												<td style=" "><?php echo $fetch2['supplycat']; ?></td>
												<td style=" "><?php echo $fetch5['subcatName']; ?></td>
												<td style=" text-align: left;"><?php echo $get_itemID['item_name']; ?></td>
												<td style=" "><?php echo $get_ics['qty']; ?></td>
												<td style=" "><?php echo $get_ics['userID']; ?></td>
												<td style=" "><?php echo $get_ics['dis_to']; ?></td>
												<td><?php echo $date; ?></td>
											</tr>
											<?php
											$counter++;
										}
									}
								}
?>
							</table>
<?php
						}else if(isset($_POST['bong'])){
							if(empty($_POST['date_filter']) || empty($_POST['date_filter2'])){
?>
								<script type='text/javascript'>
									window.onload = function(){
										alert('Fill up the 2 date');
										location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
									}
								</script>
<?php
							}else{
								$date1 = $_POST['date_filter'];
								$date2 = $_POST['date_filter2']." 23:59:59";
								$tableID = '';
?>
								<table id="<?php echo $tableID;?>">
									<tr>
										<th>#</th>
										<th>Transaction Out ID</th>
										<th>ICS/RIS No.</th>
										<th>Category</th>
										<th>Sub-Category</th>
										<th>Item Name</th>
										<th>Qty.</th>
										<th>User</th>
										<th>Distributed to:</th>
										<th colspan="2">
											Date / Time
										</th>
									</tr>
<?php
									$results = mysqli_query($connection,"SELECT * FROM item_logs where date_time BETWEEN '$date1' AND '$date2' ORDER BY date_time DESC");
									if(mysqli_num_rows($results)==NULL){
?>
										<script type='text/javascript'>
											window.onload = function(){
												alert('No DATA found');
												location = "controller.php?transaction_logs&logid=<?php echo $_GET['logid'];?>	";
											}
										</script>
<?php
									}
									$temp="";
									$counter=1;
									while($row = mysqli_fetch_assoc($results)){
										$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
										$query = mysqli_query($connection,"SELECT * FROM item WHERE itemID='$row[itemID]'");
										$fetch = mysqli_fetch_array($query);
										$query3 = mysqli_query($connection,"SELECT * FROM subcat WHERE subcatID='$fetch[subcatID]'");
										$fetch3 = mysqli_fetch_array($query3);
										$query2 = mysqli_query($connection,"SELECT * FROM category WHERE catID='$fetch[catID]'");
										$fetch2 = mysqli_fetch_array($query2);
?>
										<tr style=" ">
											<td><?php echo $counter; ?></td>
											<td style=" "><?php echo $row['icsNO'];?></td>
											<td style=" "><?php echo $row['ics_ris']; ?></td>
											<td style=" "><?php echo $fetch2['supplycat'];?></td>
											<td style=" "><?php echo $fetch3['subcatName'];?></td>
											<td style=" text-align: left;"><?php echo $fetch['item_name']; ?></td>
											<td style=" "><?php echo $row['qty'];?></td>
											<td style=" "><?php echo $row['userID']; ?></td>
											<td style=" "><?php echo $row['dis_to']; ?></td>
											<td><?php echo $date;?></td>
										</tr>
							<?php
										$counter++;
									}
								}
							?>
							</table>
							<script>
								$('<?php echo "#".$tableID;?>').tableExport();
							</script>
<?php		
						}else{
							$results = mysqli_query($connection,"SELECT * FROM item_logs ORDER BY date_time DESC");
?>
							<table id="ItemLogs">
								<tr>
									<th>#</th>
									<th>Transaction Out ID</th>
									<th>ICS/RIS No.</th>
									<th>Category</th>
									<th>Sub-Category</th>
									<th>Item Name</th>
									<th>Qty.</th>
									<th>User ID</th>
									<th>Distributed to:</th>
									<th colspan="2">
										Date / Time
									</th>
								</tr>
								<?php
								$counter=1;
								$ics=0;
								while($row = mysqli_fetch_assoc($results)){
									$date = date('F j, Y h:i:sa',strtotime($row['date_time']));
									$query2 = mysqli_query($connection,"SELECT * FROM item where itemID='$row[itemID]'");
									$fetch2 = mysqli_fetch_array($query2);
									$query3 = mysqli_query($connection,"SELECT * FROM category where catID='$fetch2[catID]'");
									$fetch3 = mysqli_fetch_array($query3);
									$query5 = mysqli_query($connection,"SELECT * FROM subcat WHERE subcatID='$fetch2[subcatID]'");
									$fetch5 = mysqli_fetch_array($query5);
									?>
									<tr>
										<td><?php echo $counter; ?></td>
										<td><?php echo $row['icsNO']; ?></td>
										<td><?php echo $row['ics_ris']; ?></td>
										<td><?php echo $fetch3['supplycat']; ?></td>
										<td><?php echo $fetch5['subcatName']; ?></td>
										<td style=" text-align:left;"><?php echo $fetch2['item_name']; ?></td>
										<td><?php echo $row['qty']; ?></td>
										<td><?php echo $row['userID']; ?></td>
										<td><?php echo $row['dis_to']; ?></td>
										<td><?php echo $date; ?></td>
									</tr>
									<?php
									$counter++;
								}
								?>
							</table>
							<?php
						}

						?>		
						<script>
							$('#ItemLogs').tableExport();
						</script>
					</div>
				</div>
			<?php
		}
		
		?>
	</div> <!-- logid-3-bg-wrapper -->
<?php
	}
?>
</div> <!-- bg-form closing tag -->
<?php	
}
else{ // item LOgs
	?>
	<div class="bg-form">
		<div id="transaction-bg-wrapper">
	<?php
	if(isset($_GET['transaction'])){
		if($_GET['transaction']==1){
			?>
			<div>
				<h4 style="text-align: center;">
					Item Logs (Out Transactions)
				</h4>
			</div>
			<?php
		}else if($_GET['transaction']==2){
			?>
			<div>
				<h4 style="text-align: center;">
					Item Logs (In Transactions)
				</h4>
			</div>
			<?php
		}else{
			?>				
			<div>
				<h4 style="text-align: center;">
					Item Logs
				</h4>
			</div>
			<?php
		}
	}
	?>				
	
		
			<div id="filter">
				<div id="subcatdropdrown">
					<select class="form-control mb-2 mr-sm-2" onchange="location = this.value">
						<?php 
						if(isset($_GET['value'])){
							$value = $_GET['value'];
							$select = mysqli_query($connection,"SELECT * FROM subcat where subcatID='$value'");
							$selectquery = mysqli_fetch_array($select);
							$results = mysqli_query($connection, "SELECT * FROM subcat");
							?>
							<option value="controller.php?transaction_logs&transaction=0&value=<?php echo $value;?>" select="selected"><?php echo $selectquery['subcatName']?></option>
							<?php
							while($row = mysqli_fetch_array($results)){
								?>
								<option value="controller.php?transaction_logs&transaction=0&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
								<?php 
							}
						}else{
							$value = 0;
							$results = mysqli_query($connection, "SELECT * FROM subcat");
							?>
							<option value="novalue" select="selected">Supply</option>
							<?php	
							while($row = mysqli_fetch_array($results)){
								?>
								<option value="controller.php?transaction_logs&transaction=0&value=<?php echo $row['subcatID'];?>"><?php echo $row['subcatName'];?></option>
								<?php
							}
						}
						?>
					</select>
				</div>
				<div id="clear">
					<form action="controller.php?transaction_logs&transaction=0" method="post">
						<input class="btn btn-info" type="submit" name="clear" value="Clear Filter">
					</form>
				</div>
				<div id="search">
				<?php
					if(isset($_GET['transaction']) && isset($_GET['view'])){
				?>
					<form class="form-inline" action="controller.php?transaction_logs&transaction=<?php echo $_GET['transaction'];?>&view=<?php echo $_GET['view'];?>" method="post">
				<?php
					}else{
				?>
					<form class="form-inline" action="controller.php?transaction_logs&transaction=0" method="post">
				<?php
					}
				?>
						<input class="form-control mb-2 mr-sm-2" id="textfield" type="text" name="valueToSearch" placeholder="item ID and Item Name">
						<input style="margin-bottom: 3%;" class="btn btn-info" id="searchbtn" type="submit" name="search" value="Search">
					</form>
				</div>
			</div>
			<div id="transaction_title">
				<h4 style="margin-left: 5%;">
			<?php
			if(isset($_GET['view'])){
				$query = mysqli_query($connection,"SELECT * FROM item where itemID='$_GET[view]'");
				$fetch = mysqli_fetch_array($query);
				echo "Item ID: &nbsp".$fetch['itemID'];
			?>
				</h4>
			</div>
			<div id="transaction_title2">
				<h4>
				<?php
					echo "Item Name: &nbsp".$fetch['item_name'];
				?>
				</h4>
			</div>
			<div id="transaction_title3">
				<?php
				if(isset($_GET['transaction'])){
					if($_GET['transaction']==1){
						$totalqty=0;
						$query = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$_GET[view]'");
						while($row = mysqli_fetch_array($query)){
							$totalqty+=$row['qty'];
						}
						?>
						<h4>
							Total: &nbsp
							<?php
							echo $totalqty;
							?>
						</h4>				
						<?php			
					}else if($_GET['transaction']==2){
						$totalqty=0;
						$query = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]'");
						while($row = mysqli_fetch_array($query)){
							$totalqty+=$row['qty'];
						}
						?>
						<h4>
							Total: &nbsp
							<?php
							echo $totalqty;
							?>
						</h4>
						<?php													
					}else{}
				}
				?>
			</div>
			<div id="transaction_title4" style="float:right;">
				<?php
				if(isset($_GET['transaction'])){
					if($_GET['transaction']==1){
						$results = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$_GET[view]'");
							?>
						<button class="btn btn-default">
							<a href="controller.php?transaction_logs&transaction=2&view=<?php echo $_GET['view'];?>">Change to In Transaction</a>
						</button>
					<?php									
					}else{
						$results = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]'");
							?>									
						<button class="btn btn-default">
							<a href="controller.php?transaction_logs&transaction=1&view=<?php echo $_GET['view'];?>">Change to Out Transactions</a>
						</button>
		<?php									
					}
				}
			}
		?>
			</div>
			<div id="admin-function">
				<div id="table-wrapper">
					<table id="process-manager-table">
						<?php
						if(isset($_POST['search'])){
							if(isset($_GET['view']) && isset($_GET['transaction'])){
								if($_GET['transaction']==1){
									$searchresult=$_POST['valueToSearch'];
									$results = mysqli_query($connection,"SELECT * FROM out_inventory WHERE CONCAT(icsNO) LIKE '%".$searchresult."%' AND itemID='$_GET[view]'");
									if(mysqli_num_rows($results)==NULL){
										echo "DATA NOT FOUND out";
									}
								}
								if($_GET['transaction']==2){
									$searchresult=$_POST['valueToSearch'];
									$results = mysqli_query($connection,"SELECT * FROM in_inventory WHERE CONCAT(risNO,userID) LIKE '%".$searchresult."%' AND itemID='$_GET[view]'");
									if(mysqli_num_rows($results)==NULL){
										echo "DATA NOT FOUND in";
									}
								}

							}else{
								$searchresult=$_POST['valueToSearch'];
								$results= mysqli_query($connection,"SELECT * FROM item WHERE CONCAT(itemID,item_name) LIKE '%".$searchresult."%'");
								if(mysqli_num_rows($results)==NULL){
									echo "DATA NOT FOUND all";
								}
							}
						}else{
							$results = mysqli_query($connection,"SELECT * FROM item");
						}
						if($value<=0){
							if(isset($_GET['view']) && isset($_GET['transaction'])){
								if($_GET['transaction']==1){
									$results = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$_GET[view]' ORDER BY date_time DESC");
									$results2 = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]' ORDER BY date_in DESC");
									if(mysqli_num_rows($results)==NULL && mysqli_num_rows($results2)==NULL){
										?>
										<script type='text/javascript'>
											window.onload = function(){
												alert('No DATA found');
												location = "controller.php?transaction_logs&transaction=0";
											}
										</script>
										<?php
									}else{
										$counter=1;
										?>
										<table id="process-manager-table">
											<tr>
												<th>#</th>
												<th>Transaction Out ID</th>
												<th>ICS/RIS No.</th>
												<th>Qty
												<th colspan="2">
													Date
												</th>
												</tr>
												<?php
												while($row = mysqli_fetch_array($results)){
													$date = date('F j, Y',strtotime($row['date_time']));
													?>

													<tr style=" ">
														<td><?php echo $counter; ?></td>
														<td style=" "><?php echo $row['icsNO']; ?></td>
														<td style=" "><?php echo $row['ics_ris']; ?></td>
														<td style=" "><?php echo $row['qty']; ?></td>
														<td style=" "><?php echo $date; ?></td>
													</tr>
													<?php
													$counter++;
														}//while
													}//else numrows
								}
								else{
									$results = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$_GET[view]' ORDER BY date_time DESC");
									$results2 = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]' ORDER BY date_in DESC");
									if(mysqli_num_rows($results)==NULL && mysqli_num_rows($results2)==NULL){
									?>
										<script type='text/javascript'>
											window.onload = function(){
												alert('No DATA found');
												location = "controller.php?transaction_logs&transaction=0";
											}
										</script>
									<?php
										}else{
											$counter=1;
									?>
											<table id="process-manager-table">
												<tr>
													<th>#</th>
													<th>RIS/ICS No.</th>
													<th>Qty.</th>
													<th>														
														Date
													</th>
													</tr>
												<?php
													while($row = mysqli_fetch_array($results2)){
														$date = date('F j, Y',strtotime($row['date_in']));
												?>
														<tr style=" ">
															<td><?php echo $counter; ?></td>
															<td style=" "><?php echo $row['risNO']; ?></td>
															<td><?php echo $row['qty']; ?></td>
															<td ><?php echo $date; ?></td>
														</tr>
						<?php
														$counter++;
													}
										}
								}
													
							}
							else{
						?>
								<tr>
									<th>Item ID</th>
									<th>Item Name</th>
									<th>Qty.</th>
								</tr>
								<?php
									while ($row = mysqli_fetch_array($results)) { 
										$subcat3 = $row['subcatID'];
										$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
										$subcat2= mysqli_fetch_array($subcat);
										$category3 = $row['catID'];
										$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
										$category2= mysqli_fetch_array($category);
								?>
										<tr style=" ">
											<td style=" "><?php echo $row['itemID'];?></td>
											<td style="text-align: left; "><a href="controller.php?transaction_logs&transaction=1&view=<?php echo $row['itemID'];?>"><?php echo $row['item_name'];?></td>
											<td style=" "><?php echo  $row['qty']; ?></td>
										</tr>
					<?php
									}
							}
									}else{
										if(isset($_GET['view']) && isset($_GET['transaction'])){
											if($_GET['transaction']==1){
												$results = mysqli_query($connection,"SELECT * FROM item_logs where itemID='$_GET[view]' ORDER BY date_time DESC");
												$results2 = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]' ORDER BY date_in DESC");
												if(mysqli_num_rows($results)==NULL && mysqli_num_rows($results2)==NULL){
					?>
													<script type='text/javascript'>
														window.onload = function(){
															alert('No DATA found');
															location = "controller.php?transaction_logs&transaction=0&transaction=0";
														}
													</script>
					<?php
												}else{
					?>
													<table id="process-manager-table">
														<tr>
															<th>Out ID</th>
															<th>Qty.</th>
															<th colspan="2">
															<?php
																for($x=1;$x<=18;$x++){
																	echo "&nbsp";
																}
															?>															
																Date / Time
															</th>
														</tr>
					<?php
													while($row = mysqli_fetch_array($results)){
					?>
														<tr style=" ">
															<td style=" "><?php echo $row['icsNO']; ?></td>
															<td style=" "><?php echo $row['qty']; ?></td>
															<td style=" "><?php echo $row['date_time']; ?></td>
														</tr>
					<?php
													}//while closing
												}//else numrows closing
											}else{
												$results = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]' ORDER BY date_in DESC");
												$results2 = mysqli_query($connection,"SELECT * FROM in_inventory where itemID='$_GET[view]'");
												if(mysqli_num_rows($results)==NULL && mysqli_num_rows($results2)==NULL){
					?>
													<script type='text/javascript'>
														window.onload = function(){
															alert('No DATA found');
															location = "controller.php?transaction_logs&transaction=0";
														}
													</script>
									<?php
										}else{
									?>
													<table id="process-manager-table">
														<tr>
															<th>RIS/ICS No.</th>
															<th>Qty.</th>
															<th colspan="2">
															<?php
																for($x=1;$x<=18;$x++){
																	echo "&nbsp";
																}
															?>
																Date / Time
															</th>
														</tr>
													<?php
														while($row = mysqli_fetch_array($results)){
													?>
															<tr style=" ">
																<td style=" "><?php echo $row['risNO']; ?></td>
																<td style=" "><?php echo $row['qty']; ?></td>
																<td style=" "><?php echo $row['date_in']; ?></td>
															</tr>
											<?php
														}
													}
												}	
											}else{
											?>
													<tr>
														<th>Item ID</th>
														<th>Item Name</th>
														<th>Qty.</th>
													</tr>
													<?php									
													$results = mysqli_query($connection, "SELECT * FROM item where subcatID='$value'");
													while ($row = mysqli_fetch_array($results)){ 
														$subcat3 = $row['subcatID'];
														$subcat = mysqli_query($connection, "SELECT * FROM subcat where subcatID='$subcat3'");
														$subcat2= mysqli_fetch_array($subcat);
														$category3 = $row['catID'];
														$category = mysqli_query($connection, "SELECT * FROM category where catID='$category3'");
														$category2= mysqli_fetch_array($category);
														?>
														<tr style=" ">
															<td style=" "><?php echo $row['itemID'];?></td>
															<td style="text-align: left; "><a href="controller.php?transaction_logs&transaction=1&view=<?php echo $row['itemID'];?>"><?php echo $row['item_name'];?></td>
																<td style=" "><?php echo  $row['qty']; ?></td>
															</tr>
															<?php
														}
													}
												}
												?>
											</table>
										</div>
									</div>               
								</div>
							</div>
		</div>
	</div>
						<?php
					}
					?>