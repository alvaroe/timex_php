<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Timesheets List';
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
		// Check if the employee is login:
		if (isset($_SESSION['employeeId'])) {

			require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc

			// Query the database:

			// Make the query:
			$id = $_SESSION['id'];
			$q = "SELECT t.id,t.statuscode,t.periodEndingDate,d.name as department, (t.minutesMon+t.minutesTue+t.minutesWed+t.minutesThu+t.minutesFri+t.minutesSat+t.minutesSun)/60 as total
				FROM timesheet t, department d WHERE t.employeeId='$id' AND t.departmentCode=d.departmentCode AND t.statuscode ";
			if (isset($_GET['paid'])){
				$q = $q."= 'C' ORDER BY t.periodEndingDate DESC";
			}else{
				$q = $q."!= 'C' ORDER BY t.periodEndingDate DESC";
			}
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
			?>

			<table align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table align="center" cellpadding="4" cellspacing="0"
							bordercolor="#CCCCCC">
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
								<td colspan="2">
									<p align="center">
										<!-- status messages -->

									</p> <br />
							
							
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>
									<div align="center">
										Employee :<span class="style25"><?php echo $_SESSION['name'];?>
											<p></p>
									
									</div></td>
							</tr>
							<p></p>
							<table border="0" align="center" cellpadding="8" cellspacing="14">
								<tr>
									<th><span class="style31">Period Ending 
									
									</th>
									<th><span class="style31">Hours 
									
									</th>
									<th><span class="style31">Department 
									
									</th>
									<th><span class="style31">Status 
									
									</th>
									<th><span class="style31">Timesheet Id 
									
									</th>
								</tr>
								<?php
								if (@mysqli_num_rows($r) > 0) { // Found timesheets for this employee
									$numrecs = @mysqli_num_rows($r);
									for ($i = 0; $i < $numrecs; $i++) {
										$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
										if (in_array($t['statuscode'],array('A','S')))
											echo "<tr><td align='center'><a href='printhours.php?tid=$t[id]'>$t[periodEndingDate] </a></td>";
										elseif (in_array($t['statuscode'],array('C')))
											echo "<tr><td align='center'><a href='printpaycheck.php?tid=$t[id]'>$t[periodEndingDate] </a></td>";
										else
											echo "<tr><td align='center'><a href='enterhours.php?tid=$t[id]'>$t[periodEndingDate] </a></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['total'],2)."</td>";
										echo "<td><div align='center' class='style25'>".$t['department']."</td>";
										echo "<td><div align='center' class='style25'>".$t['statuscode']."</td>";
										echo "<td><div align='center' class='style25'>".$t['id']."</td></tr>";
									}
								} else { // No match was made.
									echo "<td colspan='5'><p>No Timesheets were found for this employee.</p></td>";
								}
								?>

							</table>
							</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?php
			mysqli_close($dbc); // Close the database connection.

			// Include the footer and quit the script:
			echo '</div>';
			include ('includes/sidebar_signin.html');
			echo '<div style="clear: both; height: 1px;"></div></div><!-- end #page -->';
			include ('includes/footer.html');
			exit();

			mysqli_close($dbc); // Close the database connection.

		} // End of the main Submit conditional.
		else {
			$url = BASE_URL . 'signin.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.

		}
		?>
		</div>
		<!-- end #content -->
		<?php
		include ('includes/sidebar_signin.html');
		?>
		<div style="clear: both; height: 1px;"></div>
	</div>
	<!-- end #page -->
	<?php
	include ('includes/footer.html');
	?>
</body>
</html>
