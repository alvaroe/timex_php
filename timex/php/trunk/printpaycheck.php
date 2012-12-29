<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Paycheck Report';
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
		if (isset($_SESSION['id'])) {
			$id = $_SESSION['id'];
			extract($_GET); //Get the timesheet id to print
			require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc
			if (isset($tid)) {  //Get if timesheet id entered
				// Make the query:
				$q = "SELECT p.regularRate, p.overtimeRate, p.taxPercent, p.netPay, t.statuscode, t.periodEndingDate, d.name as departmentName, e.name as employeeName, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
					FROM timesheet t, department d, employee e, payment p  where t.id=$tid and t.departmentcode=d.departmentcode and t.id=p.timesheetId and t.employeeid=e.id and (t.employeeid=$id or e.managerId=$id);";
			} else { //Get with period ending date and employee id.
				$q = "SELECT p.regularRate, p.overtimeRate, p.taxPercent, p.netPay, t.statuscode, t.periodEndingDate, d.name as departmentName, e.name as employeeName, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
					FROM timesheet t, department d, employee e, payment p where t.statuscode = 'C' and t.departmentcode=d.departmentcode and t.id=p.timesheetId and t.employeeid=e.id and (t.employeeid=$id or e.managerId=$id) order by t.periodEndingDate desc limit 1;";
				//echo $q;
			}
			// Query the database:
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
			?>
			<table align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table border="0" align="center" cellpadding="4" cellspacing="0"
							bordercolor="#CCCCCC">
							<tr valign="middle">
								<td width="90%" height="60" valign="middle">
									<h1 class="title">
									<?php echo $page_title;?>
									</h1> <br />
								</td>
								<div align="left">
									<a href="javascript:history.go(-1)">Previous page</a>
								</div>
								<td align="right" nowrap="nowrap"><a href="logout.php">Sign out</a>
								</td>
							</tr>
							<?php
							if (@mysqli_num_rows($r) == 1) { // Found the one timesheet for this employee
								$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
								?>
							<tr>
								<td colspan="2">
									<div align="center">
										Employee: <span class="style25"><?php echo $t['employeeName'];?>
										</span>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Period
										Ending: <span class="style25"><?php echo $t['periodEndingDate'];?>
										</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status:
										<span class="style25"><?php echo $t['statuscode'];?> </span> <br>
									</div>
									<table border="0" align="center" cellpadding="8"
										cellspacing="15">
										<tr>
											<th scope="row"><span class="style31">Department</span></th>
											<th></th>
											<th></th>
											<th><span class="style31">Mo</span></th>
											<th><span class="style31">Tu</span></th>
											<th><span class="style31">We</span></th>
											<th><span class="style31">Th</span></th>
											<th><span class="style31">Fr</span></th>
											<th><span class="style31">Sa</span></th>
											<th><span class="style31">Su</span></th>
											<th><span class="style31">Total</span></th>
										</tr>
										<?php
										echo "<tr><td scope='row' colspan='3'><div align='center' class='style25'>".$t['departmentName']."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrMon'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrTue'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrWed'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrThu'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrFri'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrSat'],2)."</div></td>";
										echo "<td><div align='center' class='style25'>".number_format($t['hrSun'],2)."</div></td>";
										$totalHrs = $t['hrMon']+$t['hrTue']+$t['hrWed']+$t['hrThu']+$t['hrFri']+$t['hrSat']+$t['hrSun'];
										echo "<td><div align='center' class='style25'>".number_format($totalHrs,2)."</div></td></tr>";
										echo "<tr><td scope='row' colspan='3'><div align='center' class='style25'>Regular Rate</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate'],2)."</div></td><td></td>";
										echo "<tr><td colspan='11'><hr></td></tr>";
										echo "<tr><td scope='row' colspan='3'><div align='center' class='style25'>Regular Pay</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrMon'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrTue'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrWed'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrThu'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrFri'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSat'],2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSun'],2)."</div></td>";
										$totalRegPay = $totalHrs * $t['regularRate'];
										echo "<td><div align='right' class='style25'>".number_format($totalRegPay,2)."</div></td></tr>";
										echo "<tr><td scope='row' colspan='3'><div align='center' class='style25'>Taxes</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrMon']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrTue']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrWed']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrThu']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrFri']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSat']*$t['taxPercent']/100,2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSun']*$t['taxPercent']/100,2)."</div></td>";
										$totalTax = $totalRegPay * $t['taxPercent']/100;
										echo "<td><div align='right' class='style25'>".number_format($totalTax,2)."</div></td></tr>";
										echo "<tr><td colspan='11'><hr></td></tr>";
										echo "<tr><td scope='row' colspan='3'><div align='center' class='style25'>Net Pay</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrMon']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrTue']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrWed']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrThu']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrFri']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSat']*(1-$t['taxPercent']/100),2)."</div></td>";
										echo "<td><div align='right'>".number_format($t['regularRate']*$t['hrSun']*(1-$t['taxPercent']/100),2)."</div></td>";
										$total = $totalRegPay - $totalTax;
										echo "<td><div align='right' class='style25'>".number_format($total,2)."</div></td></tr>";
							} else { // No match was made.
								echo "<td colspan='9'><p>No Timesheet was found for this employee.</p></td>";
							}
							?>
									</table>
									<p align="center">
										&nbsp;&nbsp; <input name="submit" type="button" id="submit"
											value="Print Paycheck" onClick="window.print()">
									</p>
								</td>
							</tr>
							<tr>
								<td bgcolor="#C2DCEB" colspan="11" align="center">C&nbsp;&nbsp;O&nbsp;&nbsp;N&nbsp;&nbsp;F&nbsp;&nbsp;I&nbsp;&nbsp;D&nbsp;&nbsp;E&nbsp;&nbsp;N&nbsp;&nbsp;T&nbsp;&nbsp;I&nbsp;&nbsp;A&nbsp;&nbsp;L&nbsp;&nbsp;</td>
							</tr>
						</table></td>
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
		<div id="sidebar">
			<div id="news" class="boxed1">
				<h2 class="title">News &amp; Updates</h2>
				<div class="content">
					<ul>
						<li>
							<h3>
								February 14, 2012: <a href="#">TIMEX Training</a>
							</h3>
							<p>
								We are having a 2 hr training session on TIMEX. Please <a
									href="#">register here&hellip;</a>
							</p>
						</li>
						<li>
							<h3>
								March 31, 2012: <a href="#">End of Quarter Reports</a>
							</h3>
							<p>
								Make sure you submit your timesheets before this date for end of
								the quarter reports. <a href="#">Read more&hellip;</a>
							</p>
						</li>
						<li>
							<h3>
								June 30, 2012: <a href="#">End of Quarter Reports</a>
							</h3>
							<p>
								Make sure you submit your timesheets before this date for end of
								the quarter reports. <a href="#">Read more&hellip;</a>
							</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both; height: 1px;"></div>
	</div>
	<!-- end #page -->
	<?php
	include ('includes/footer.html');
	?>
</body>
</html>
