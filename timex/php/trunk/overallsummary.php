<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Overall Summary Report';
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
		function periodEndingDate(){
			return date('Y-m-d',strtotime("next Sunday"));
		}
		// Check if the employee is login:
		if (isset($_SESSION['employeeId']) && $_SESSION['employeeType'] == 'E') {

			require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc

			// Query the database:
			// Make the query:
			$id = $_SESSION['id'];
			$q = "SELECT t.id,t.statuscode,t.periodEndingDate,e.name,e.managerId,e.id, (t.minutesMon+t.minutesTue+t.minutesWed+t.minutesThu+t.minutesFri+t.minutesSat+t.minutesSun)/60 as total
				FROM timesheet t, employee e WHERE t.employeeId=e.id AND t.statuscode != 'C' and t.periodEndingDate = '".periodEndingDate()."';";
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
								<td colspan="2"><br>
									<table border="0" align="center" cellpadding="8"
										cellspacing="20">
										<tr>
											<th scope="row">
												<div align="center" class="style18">Manager</div>
											</th>
											<th>
												<div align="center" class="style18">Hours For This Week</div>
											</th>
											<th>
												<div align="center" class="style18">Status</div>
											</th>
										</tr>

										<tr valign="top">
											<td scope="row">
												<div align="left" class="style18">Teresa Walker</div>
											</td>
											<td nowrap>
												<div align="right" class="style18">535.00</div>
											</td>
											<td nowrap><span class="style18">14 paid<br> 1 unpaid<br> 15
													approved<br> 0 disapproved </span></td>
										</tr>

										<tr bgcolor="#CCCCCC">
											<td scope="row">
												<div align="right" class="style18">Average Hours</div>
											</td>
											<td nowrap>
												<div align="right" class="style18">38.67</div>
											</td>
											<td nowrap>&nbsp;</td>
										</tr>
										<tr bgcolor="#CCCCCC">
											<td scope="row">
												<div align="right" class="style18">
													<strong>TOTAL</strong>
												</div>
											</td>
											<td nowrap>
												<div align="right" class="style18">
													<strong>1,315.0 </strong>
												</div>
											</td>
											<td nowrap>
												<div align="right" class="style18">
													<strong>34 </strong>
												</div>
											</td>
										</tr>
									</table>
									<p align="center">
										&nbsp;&nbsp; <input name="submit" type="button" id="submit"
											value="Print" onClick="window.print()"> &nbsp; <input
											name="reset" type="reset" id="reset" value="Cancel"
											onClick="javascript:window.location='timesheetlist.php'">
									</p></td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#C2DCEB">&nbsp;</td>
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
