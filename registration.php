<?php
$page_title = 'Registration';
include ('includes/header.html');
?>
<script type="text/javascript">
			function enabledButtons(name1,name2,name3) {
				if (document.forms[0].elements[name1].selectedIndex == 0 ||
					document.forms[0].elements[name2].selectedIndex == 0 ||
					document.forms[0].elements[name3].selectedIndex == 0) {
					document.getElementById('register').disabled = true;
				} else {
					document.getElementById('register').disabled = false;
				}
			}

			function validate() {
				if (document.forms[0].elements['username'].value == "") {
					alert('Must input a User Name');
					return false;
				} else if (document.forms[0].elements['password'].value != document.forms[0].elements['reenterpassword'].value) {
					alert('Password and Re-enter Password must be the same');
					return false;
				} else if (document.forms[0].elements['employeeType'].selectedIndex == 0) {
					alert('Please select your Employee Role');
					return false;
				} else if (document.forms[0].elements['manager'].selectedIndex == 0) {
					alert('Please select your Manager name');
					return false;
				} else if (document.forms[0].elements['state'].selectedIndex == 0) {
					alert('Please select State');
					return false;
				} else {
					return true;
				}
			}
</script>
</head>
<body>
<?php
include ('includes/menus.html');
?>
	<div id="page">
		<div id="content">

		<?php
		// Check if the form has been submitted:
		if (isset($_POST['register'])) {

			require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc

			$errors = array(); // Initialize an error array.
			// Check for a user name:
			if (empty($_POST['username'])) {
				$errors[] = 'You forgot to enter your user-name. Without it you cannot login later on.';
			} else {
				$un = mysqli_real_escape_string($dbc, trim($_POST['username']));
				$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
				$q = "SELECT id FROM employee WHERE employeeId = '$un' OR email = '$e'";
				$r = @mysqli_query ($dbc, $q); // Run the query.
				if (mysqli_affected_rows($dbc) > 0) { // Meaning that username already exists.
					$errors[] = 'Your user-name and/or email address already exist in our database. Usually user-name (SSN) and email address must be unique.';
				}
			}

			// Check for a last name:
			if (empty($_POST['fullname'])) {
				$errors[] = 'You forgot to enter your full name.';
			} else {
				$fn = mysqli_real_escape_string($dbc, trim($_POST['fullname']));
			}

			// Check for an email address:
			if (empty($_POST['email'])) {
				$errors[] = 'You forgot to enter your email address. Without it you cannot receive updates via email.';
			} else {
				$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
			}

			// Check for a password and match against the confirmed password:
			if (!empty($_POST['password'])) {
				if ($_POST['password'] != $_POST['reenterpassword']) {
					$errors[] = 'Your password did not match the re-entered password.';
				} else {
					$p = mysqli_real_escape_string($dbc, trim($_POST['password']));
				}
			} else {
				$errors[] = 'You forgot to enter your password.';
			}
			$mid = mysqli_real_escape_string($dbc, trim($_POST['manager']));
			$emt = mysqli_real_escape_string($dbc, trim($_POST['employeetype']));
			$adr = mysqli_real_escape_string($dbc, trim($_POST['address']));
			$cit = mysqli_real_escape_string($dbc, trim($_POST['city']));
			$sta = mysqli_real_escape_string($dbc, trim($_POST['state']));
			$zip = mysqli_real_escape_string($dbc, trim($_POST['zipcode']));


			if (empty($errors)) { // If everything's OK.

				// Register the user in the database...

				// Make the query:
				$q = "INSERT INTO employee (employeeId, name, email, employeeType, password,       managerId, address, city,  state, zipcode, payrate, taxrate, registrationDate)
							 VALUES ('$un',     '$fn', '$e',  '$emt',       SHA1('$p'), $mid,      '$adr',  '$cit','$sta','$zip',  0.00,    0,       NOW() )";
				$r = @mysqli_query ($dbc, $q); // Run the query.
				if ($r) { // If it ran OK.

					// Print a message:
					echo '<h1>Thank you!</h1>
		<p>You are now registered as an Employee. You can start creating your timesheets now!</p><p>Click <a href=\'signin.php\'>here</a> to Login.<br /></p></div>';	

				} else { // If it did not run OK.

					// Public message:
					echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p></div>'; 

					// Debugging message:
					echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';

				} // End of if ($r) IF.

				mysqli_close($dbc); // Close the database connection.

				// Include the footer and quit the script:
				include ('includes/sidebar_registration.html');
				echo '<div style="clear: both; height: 1px;"></div></div><!-- end #page -->';
				include ('includes/footer.html');
				exit();

			} else { // Report the errors.

				echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
				foreach ($errors as $msg) { // Print each error.
					echo " - $msg<br />\n";
				}
				echo '</p><p>Please try again.</p><p><br /></p>';

			} // End of if (empty($errors)) IF.

			mysqli_close($dbc); // Close the database connection.

		} // End of the main Submit conditional.
		?>

			<form method='post' onSubmit='javascript:return validate();'
				action='registration.php'>
				<table width="85%" border="1" align="center" cellpadding="0"
					cellspacing="0">
					<tr>
						<td>
							<table width="100%" border="0" align="center" cellpadding="60"
								cellspacing="5">
								<tr valign="middle">
									<td width="90%" height="60" valign="middle">
										<h1 class="title"><?php echo $page_title;?></h1> <br />
									</td>
									<td align="right" nowrap="nowrap"></td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<center>

											<!-- status messages -->
										</center>
										<p>Please provide the following information:</p></td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<table align="center" cellpadding="20" cellspacing="10">
											<tr>
												<td>Employee Id :</td>
												<td><input name='username' id='username' type="text"
													size="11" maxlength="11"
													value="<?php echo (isset($_POST['username']) ? $_POST['username'] : "");?>" /> xxx-xx-xxxx</td>
											</tr>
											<tr>
												<td>Employee Full Name :</td>
												<td><input name='fullname' id='fullname' type="text"
													size="30" maxlength="30"
													value="<?php echo (isset($_POST['fullname']) ? $_POST['fullname'] : "");?>" /> firstname lastname </td>
												</td>
											</tr>
											<tr>
												<td>Password :</td>
												<td><input name='password' id='password' type="password"
													size="15" maxlength="15" />
												</td>
											</tr>
											<tr>
												<td>Re-enter Password :</td>
												<td><input name='reenterpassword' id='reenterpassword'
													type="password" size="15" maxlength="15" />
												</td>
											</tr>
											<tr>
												<td>Email :</td>
												<td><input name='email' id='email' type="text" size="25"
													maxlength="25"
													value="<?php echo (isset($_POST['email']) ? $_POST['email'] : "");?>" /> name@domain.com </td>
												</td>
											</tr>
											<tr>
												<td>Employee Type :</td>
												<td><select name='employeetype' id='employeetype'
													onChange='javascript:enabledButtons("employeetype","manager", "state")' />
													<option value=''  <?php echo (!isset($_POST['employeetype']) ? "SELECTED" : "");?>></option>
													<option value='H' <?php echo ((isset($_POST['employeetype']) && $_POST['employeetype'] == 'H') ? "SELECTED" : "");?>>Hourly</option>
													<option value='M' <?php echo ((isset($_POST['employeetype']) && $_POST['employeetype'] == 'M') ? "SELECTED" : "");?>>Manager</option>
													<option value='E' <?php echo ((isset($_POST['employeetype']) && $_POST['employeetype'] == 'E') ? "SELECTED" : "");?>>Executive</option>
													<option value='A' <?php echo ((isset($_POST['employeetype']) && $_POST['employeetype'] == 'A') ? "SELECTED" : "");?>>Administrative</option> </select>
												</td>
											</tr>
											<tr>
												<td>Manager :</td>
												<td><select name='manager' id='manager'
													onChange='javascript:enabledButtons("employeetype","manager", "state")' />
													<option value=''  <?php echo (!isset($_POST['manager']) ? "SELECTED" : "");?>></option>
													<option value='3' <?php echo ((isset($_POST['manager']) && $_POST['manager'] == '3') ? "SELECTED" : "");?>>Teresa Walker</option>
													<option value='4' <?php echo ((isset($_POST['manager']) && $_POST['manager'] == '4') ? "SELECTED" : "");?>>Tom Brady</option>
													<option value='5' <?php echo ((isset($_POST['manager']) && $_POST['manager'] == '5') ? "SELECTED" : "");?>>Alvaro Escobar</option> </select>
												</td>
											</tr>
										</table>
										<table align="center" cellpadding="20" cellspacing="10">
											<tr>
												<td colspan=4>
													<hr>
												</td>
											</tr>
											<tr>
												<td>Address :</td>
												<td colspan=3><input name='address' id='address' type="text"
													size="40" maxlength="40" value="<?php echo (isset($_POST['address']) ? $_POST['address'] : "");?>"/>
												</td>
											</tr>

											<tr>
												<td>City :</td>
												<td colspan=3><input name='city' id='city' type="text"
													size="20" maxlength="20" value="<?php echo (isset($_POST['city']) ? $_POST['city'] : "");?>" />
												</td>
											</tr>

											<tr>
												<td>State :</td>
												<td><select name='state' id='state'
													onChange='javascript:enabledButtons("employeetype","manager", "state")' />
													<option value=''   <?php echo (!isset($_POST['state']) ? "SELECTED" : "");?>></option>
													<option value='FL' <?php echo ((isset($_POST['state']) && $_POST['state'] == 'FL') ? "SELECTED" : "");?>>Florida</option>
													<option value='GA' <?php echo ((isset($_POST['state']) && $_POST['state'] == 'GA') ? "SELECTED" : "");?>>Georgia</option>
													<option value='NY' <?php echo ((isset($_POST['state']) && $_POST['state'] == 'NY') ? "SELECTED" : "");?>>New York</option>
													<option value='CA' <?php echo ((isset($_POST['state']) && $_POST['state'] == 'CA') ? "SELECTED" : "");?>>California</option> </select></td>
												<td>Zipcode :</td>
												<td><input name='zipcode' id='zipcode' type="text" size="5"
													maxlength="5"  value="<?php echo (isset($_POST['zipcode']) ? $_POST['zipcode'] : "");?>"/>
												</td>
											</tr>
										</table> <br> </br>
										<p align="center">
											<input name="register" id="register" type="submit"
												value="Register" disabled> <br> <br>
										</p></td>
								</tr>
							</table></td>
					</tr>
				</table>
			</form>
		</div>
		<!-- end #content -->
		<?php
		include ('includes/sidebar_registration.html');
		?>
		<div style="clear: both; height: 1px;"></div>
	</div>
	<!-- end #page -->
	<?php
	include ('includes/footer.html');
	?>
</body>
</html>
