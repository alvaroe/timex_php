<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'New Timesheet';
include ('includes/header.html');
?>
<script type="text/javascript">
			function enabledButtons(name) {
				if (document.forms[0].elements[name].selectedIndex == 0) {
					document.getElementById('save').disabled = true;
					document.getElementById('saveAndEmail').disabled = true;
				} else {
					document.getElementById('save').disabled = false;
					document.getElementById('saveAndEmail').disabled = false;
				}
			}

			function selectAll(name) {
				document.forms[0].elements[name].value = document.forms[0].elements[name].value;
			}
			
			function checkMaxVal(name) {
				if (document.forms[0].elements[name].value > 16) {
					document.forms[0].elements[name].value = 0.0;
					alert('Allowed maximum is 16 hours per day');
				}
				var total = getTotal();
				document.getElementById('total').innerHTML = "" + total;
			}

			function getTotal() {
				var mon = new Number(document.forms[0].elements['hrMon'].value);
				var tue = new Number(document.forms[0].elements['hrTue'].value);
				var wed = new Number(document.forms[0].elements['hrWed'].value);
				var thu = new Number(document.forms[0].elements['hrThu'].value);
				var fri = new Number(document.forms[0].elements['hrFri'].value);
				var sat = new Number(document.forms[0].elements['hrSat'].value);
				var sun = new Number(document.forms[0].elements['hrSun'].value);
				return mon + tue + wed + thu + fri + sat + sun;
			}

			function validate() {
				if (getTotal() > 96) {
					alert('Maximum allowed total is 96');
					return false;
				} else if (document.forms[0].elements[name].selectedIndex == 0) {
					alert('Please select department');
					return false;
				} else {
					return true;
				}
			}
		</script>
</head>
<body onLoad="javascript:enabledButtons('tDepartmentCode');">
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
			extract($_GET);
			require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc
			if (isset($tid)) {  //Get if timesheet id entered
				// Make the query:
				$q = "SELECT t.id, t.employeeId, t.statuscode, t.periodEndingDate, d.departmentCode, d.name, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
			FROM timesheet t, department d where t.id=$tid and t.departmentcode=d.departmentcode and t.employeeId=$id;";
			} else { //Get with period ending date and employee id.
				$q = "SELECT t.id, t.employeeId, t.statuscode, t.periodEndingDate, d.departmentCode, d.name, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
			FROM timesheet t, department d where t.periodEndingDate = '".periodEndingDate()."' and t.departmentcode=d.departmentcode and t.employeeId=$id;";
			}
			// Query the database:
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
			if (@mysqli_num_rows($r) == 1) { // Found the one timesheet for this employee
				$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
				mysqli_free_result($r);
				$found = TRUE;  //Timesheet found.
			} else { // No match was made.
				echo "<td colspan='2'><p>New Timesheet for this employee.</p></td>";
				$found = FALSE; //Timesheet NOT found.
			}
			if (isset($tid) && $t['employeeId'] != $id){  //Do not have access to someone else's timesheet
				mysqli_close($dbc);
				$url = BASE_URL . 'timesheetlist.php'; // Define the URL:
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.
			}
			if ($found && ($t['statuscode'] == "S" || $t['statuscode'] == "A")){  //Cannot modify this timesheet
				mysqli_close($dbc);
				$url = BASE_URL . 'printhours.php?tid='.$t['id']; // Define the URL:
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.
			}
			if ($found && (isset($_POST['save']) || isset($_POST['saveAndEmail']))) { //Hours were submitted on an existing timesheet
				// Make the query:
				$q = "UPDATE timesheet set statuscode='".( isset($_POST['saveAndEmail']) ? "S" : "P")."',periodEndingDate='".periodEndingDate()."',departmentCode='".$_POST['tDepartmentCode'].
				"',minutesMon=".($_POST['hrMon']*60).",minutesTue=".($_POST['hrTue']*60).",minutesWed=".($_POST['hrWed']*60).
				",minutesThu=".($_POST['hrThu']*60).",minutesFri=".($_POST['hrFri']*60).",minutesSat=".($_POST['hrSat']*60).",minutesSun=".($_POST['hrSun']*60).
				" where periodEndingDate = '".periodEndingDate()."' and employeeid=$id and statuscode in ('P','D');";
				// Query the database:
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				if ($r) { // Updated the one timesheet for this employee
					$q = "SELECT t.statuscode, t.periodEndingDate, d.departmentCode, d.name, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
			FROM timesheet t, department d where t.periodEndingDate = '".periodEndingDate()."' and t.departmentcode=d.departmentcode and t.employeeid=$id and t.statuscode in ('P','D');";
					$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
					if (@mysqli_num_rows($r) == 1) { // Found the one timesheet for this employee and still pending
						$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
						mysqli_free_result($r);
						echo "<td colspan='2'><p>Timesheet updated.</p></td>";
						$found = TRUE;
					} else { // No match was made because timesheet is now Submitted. Redirect to timesheet list.
						mysqli_close($dbc);
						$url = BASE_URL . 'timesheetlist.php'; // Define the URL:
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}
				} else {
					echo "<td colspan='2'><p>Timesheet could not be updated.</p></td>";
				}
			}
			if (!$found && (isset($_POST['save']) || isset($_POST['saveAndEmail']))) { //Hours were submitted on a new timesheet
				// Make the query:
				$q = "INSERT INTO timesheet (employeeId,statuscode,periodEndingDate,departmentCode,minutesMon,minutesTue,minutesWed,minutesThu,minutesFri,minutesSat,minutesSun)
				VALUES ($id,'".( isset($_POST['saveAndEmail']) ? "S" : "P")."','".periodEndingDate()."','".$_POST['tDepartmentCode']."',".($_POST['hrMon']*60).",".($_POST['hrTue']*60).",".($_POST['hrWed']*60).",".($_POST['hrThu']*60).",".($_POST['hrFri']*60).",".($_POST['hrSat']*60).",".($_POST['hrSun']*60).");";
				// Query the database:
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
				if ($r) { // Created the new timesheet for this employee
					$q = "SELECT t.statuscode, t.periodEndingDate, d.departmentCode, d.name, t.minutesMon/60 as hrMon, t.minutesTue/60 as hrTue, t.minutesWed/60 as hrWed, t.minutesThu/60 as hrThu, t.minutesFri/60 as hrFri, t.minutesSat/60 as hrSat, t.minutesSun/60 as hrSun
			FROM timesheet t, department d where t.periodEndingDate = '".periodEndingDate()."' and t.departmentcode=d.departmentcode and t.employeeid=$id and t.statuscode in ('P','D');";
					$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
					if (@mysqli_num_rows($r) == 1) { // Found the created timesheet for this employee
						$t = mysqli_fetch_array ($r, MYSQLI_ASSOC);
						mysqli_free_result($r);
						echo "<td colspan='2'><p>New Timesheet created.</p></td>";
						$found = TRUE;
					} else { // No match was made because timesheet is now Submitted. Redirect to timesheet list.
						mysqli_close($dbc);
						$url = BASE_URL . 'timesheetlist.php'; // Define the URL:
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}
				} else {
					echo "<td colspan='2'><p>New Timesheet could not be created.</p></td>";
				}
			}
			?>
			<form method='post' onSubmit='javascript:return validate();'>
				<table width="85%" border="1" align="center" cellpadding="0"
					cellspacing="0">
					<tr>
						<td>
							<table width="100%" border="0" align="center" cellpadding="60" cellspacing="5">
								<tr valign="middle">
									<td width="90%" height="60" valign="middle">
										<h1 class="title"><?php echo $page_title;?></h1> <br />
									</td>
									<td align="right" nowrap="nowrap"><a href="logout.php">Sign out</a>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div align="center">
											Employee: <span class="style25"><?php echo $_SESSION['name'];?>
											</span>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Period
											Ending: <span class="style25"> <?php if ($found) echo $t['periodEndingDate']; else echo periodEndingDate();?>
											</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status:
											<span class="style25"><?php if ($found) echo $t['statuscode']; else echo "P";?>
											</span> <br>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<table align="center" cellpadding="60" cellspacing="10">
											<tr>
												<th scope="row"><span class="style31">Department</span></th>
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
											echo "<tr><td><select name='tDepartmentCode' onChange='javascript:enabledButtons(this.name)'>";
											echo "<option value=''></option>";
											$q = "SELECT * from department;";
											// Query the database
											$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
											$numrecs = @mysqli_num_rows($r); // departments found
											for ($i = 0; $i < $numrecs; $i++) {
												$dpt = mysqli_fetch_array ($r, MYSQLI_ASSOC);
												echo "<option value='".$dpt['departmentCode']."' ".( $found && $t['departmentCode'] == $dpt['departmentCode'] ? "SELECTED" : "")." > ".$dpt['name']."</option>";
											}
											mysqli_free_result($r);
											echo "</select></td>";
											echo "<td><input name='hrMon' value='".( $found ? number_format($t['hrMon'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrTue' value='".( $found ? number_format($t['hrTue'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrWed' value='".( $found ? number_format($t['hrWed'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrThu' value='".( $found ? number_format($t['hrThu'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrFri' value='".( $found ? number_format($t['hrFri'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrSat' value='".( $found ? number_format($t['hrSat'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											echo "<td><input name='hrSun' value='".( $found ? number_format($t['hrSun'],2) : "0.0")."' type='text' size='4' maxlength='6' onBlur='checkMaxVal(this.name)' onClick='selectAll(this.name)'></td>";
											if ($found) $total = $t['hrMon']+$t['hrTue']+$t['hrWed']+$t['hrThu']+$t['hrFri']+$t['hrSat']+$t['hrSun']; else $total=0.0;
											echo "<td><div id='total' align='center' class='style25'>".number_format($total,2)."</div></td></tr>";
											echo "</table> <input type='hidden' id='sendEmail' name='sendEmail' value='no' /> <br> </br><p align='center'>";
											echo "<input name='save' id='save' type='submit' value='Save' disabled>";
											echo "<input name='saveAndEmail' id='saveAndEmail' type='submit' value='submitted' title='Submit and send an email to manager about timesheet'
													onClick='javascript:document.getElementById('sendEmail').value='yes';' disabled>";
											echo "<input name='cancel' type='button' value='Cancel' onClick='javascript:window.location=\"timesheetlist.php\"'>";
											echo "<br><br></p></td></tr>";
											?>
											<tr>
												<td colspan="2">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="2">&nbsp;</td>
											</tr>
										</table></td>
								</tr>
							</table>
							</form> <?php
		mysqli_close($dbc);
		} // End of the main Submit conditional.
		else {
			$url = BASE_URL . 'signin.php'; // Define the URL:
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
		?>
							</div> <!-- end #content --> <?php include ('includes/sidebar_enterhours.html');?>
							<div style="clear: both; height: 1px;"></div>
							</div> <!-- end #page --> <?php include ('includes/footer.html');?></body>
</html>
