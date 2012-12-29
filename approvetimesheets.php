<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Approve Timesheets';
include ('includes/header.html');
?>
</head>
<body>
<?php
include ('includes/menus.html');
?>
	<div id="page">
		<div id="content">
		<?php
		$errors = array(); // Initialize an error array.
		require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc
		// Check if the employee is login:
		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
			if (isset($_POST['Send'])) { //Approve/Disapprove were submitted
				foreach ($_POST as $key => $value) {
					if (is_numeric($key)) {
						// Make the query:
						$q = "UPDATE timesheet set statuscode='".($value == 'Yes' ? "A" : "D")."' where id = ".$key.";";
						// Update the database:
						$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
						if ($r) { // Updated the one timesheet for this employee
						} else {
							echo "<td colspan='2'><p>Timesheet could not be updated.</p></td>";
						}
					}
				}
				mysqli_close($dbc);
				$url = BASE_URL . 'timesheetlist.php'; // Define the URL:
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.
			} else {
				// Make the query:
				$q = "SELECT t.id, t.statuscode, t.periodEndingDate, (t.minutesMon/60+t.minutesTue/60+t.minutesWed/60+t.minutesThu/60+t.minutesFri/60+t.minutesSat/60+t.minutesSun/60) as hrTotal,
				 e.name FROM timesheet t, employee e where t.employeeId = e.id and e.managerid=$id and t.statuscode in ('S','A','D') order by t.employeeid,periodEndingDate ASC;";
				// Query the database:
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				if (@mysqli_num_rows($r) > 0) { // Found the one timesheet for this employee
					$found = TRUE;  //Timesheets found.
				} else { // No match was made.
					echo "<p class='error'>No Timesheets found from your employees.</p>";
					$found = FALSE; //No Timesheets found.
				}
			}
		} else { // Not logged in so login first
			mysqli_close($dbc);
			$url = BASE_URL . 'signin.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
			
		?>
			<form method="post">
				<table align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table width="100%" border="0" align="center" cellpadding="60"
								cellspacing="5">
								<tr valign="middle">
									<td width="90%" height="60" valign="middle">
										<h1 class="title">
										<?php echo $page_title;?>
										</h1> <br />
									</td>
									<td align="right" nowrap="nowrap"><a href="logout.php">Sign out</a>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div align="center">
											Employees reporting to : <span class="style25"><?php echo $_SESSION['name'];?>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<table align="center" cellpadding="60" cellspacing="10">
											<tr>
												<th scope="row">
													<div align="center" class="style30">Employee, Period</div>
												</th>
												<th>
													<div align="center" class="style30">Hours For Week</div>
												</th>
												<th>
													<div align="center" class="style30">Status</div>
												</th>
												<th>
													<div align="center" class="style30">Approve</div>
												</th>
											</tr>
											<?php
											if ($found){
												$gTotal=0.0;$name="";
												$numrecs = @mysqli_num_rows($r); // departments found
												for ($i = 0; $i < $numrecs; $i++) {
													$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
													$gTotal = $gTotal + $t['hrTotal'];
													echo "<tr><td scope='row'><div align='right' class='style30'>
														<a href='printhours.php?tid=".$t['id']."'> ".($name == $t['name'] ? "" : $t['name'].",")." ".$t['periodEndingDate']." </a></div></td>";
													$name = $t['name'];
													echo "<td nowrap><div align='right' class='style30'>".number_format($t['hrTotal'],2)."</div></td>";
													echo "<td align='center' title='P: pending
															A: approved
															C: paid
															S: submitted
															D: disapproved'>".$t['statuscode']."</td>";
													echo "<td nowrap><div align='left' class='style30'>
														<input name='".$t['id']."' type='radio' value='Yes' ".( $t['statuscode'] == 'A' ? "checked" : "")."> Yes&nbsp;&nbsp;
														<input name='".$t['id']."' type='radio' value='No'  ".( $t['statuscode'] == 'D' ? "checked" : "")."> No&nbsp;&nbsp;
													    </div></td></tr>";
												}
												mysqli_free_result($r);
												echo "<tr bgcolor='#CCCCCC'>
													<td scope='row'><span class='style30'><strong>TOTAL</strong></span></td>
													<td colspan='3'><div align='center' class='style30'><strong>".number_format($gTotal,2)."&nbsp;&nbsp;&nbsp;&nbsp;Hours </strong></div></td>
												</tr>";
											} else {
												echo "<tr><td colspan='4'>No Timesheets found</td></tr>";
											}
											?>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<div align="center">
					<input name="Send" type="submit" value="Send" /> <input
						name="Reset" type="button" value="Reset"
						onclick="javascript:document.forms[0].reset()" /> <input
						name='cancel' type='button' value='Cancel'
						onClick='javascript:window.location="timesheetlist.php"'>
				</div>
			</form>
		</div>
		<!-- end #content -->
		<?php
		include ('includes/sidebar_enterhours.html');
		?>
		<div style="clear: both; height: 1px;"></div>
	</div>
	<!-- end #page -->
	<?php
	include ('includes/footer.html');
	?>

</body>
</html>
