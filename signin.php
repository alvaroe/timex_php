<?php
session_start(); // start up your PHP session!
require_once ('includes/config.inc.php');
$page_title = 'Login';
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
		// Check if the form has been submitted:
		if (isset($_SESSION['employeeId'])) { //You are already login
			$empId = $_SESSION['employeeId'];
			echo "<p class='error'>You are already login as an employee with ID $empId";
			echo '</p>';
			echo '<p>Please Logout <a href="logout.php">here</a> if you are not this employee.</p></div>';
		}
		else {
			if (isset($_POST['submitted'])) {

				require_once ('mysqli_connect.php'); // Connect to the db and creates $dbc

				$errors = array(); // Initialize an error array.
				// Check for a first name:
				if (empty($_POST['username'])) {
					$errors[] = 'You forgot to enter your user-name. Usually it would be your SSN.';
				} else {
					$un = mysqli_real_escape_string($dbc, trim($_POST['username']));
				}
				// Check for a password:
				if (!empty($_POST['password'])) {
					$p = mysqli_real_escape_string($dbc, trim($_POST['password']));
				} else {
					$errors[] = 'You forgot to enter your password.';
				}

				if (empty($errors)) { // If everything's OK.
					// Query the database:

					// Make the query:
					$q = "SELECT id, employeeId, name, email, employeeType, managerId FROM employee
				      WHERE (employeeId='$un' AND password=SHA1('$p'))";
					$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

					if (@mysqli_num_rows($r) == 1) { // A match was made.

						// Register the values & redirect:
						$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC);
						mysqli_free_result($r);
						mysqli_close($dbc);

						$url = BASE_URL . 'timesheetlist.php'; // Define the URL:
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.

					} else { // No match was made.
						echo '<p class="error">Either the username and password do not match those on file or you have not registered yet.</p>';
						echo '<p>Please Register <a href="registration.php">here</a> or try Login again <a href="signin.php">here</a>.</p>';
					}

					mysqli_close($dbc); // Close the database connection.

					// Include the footer and quit the script:
					echo '</div>';
					include ('includes/sidebar_signin.html');
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

			<form method="post" action="signin.php">
				<table align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table align="center" cellpadding="4" cellspacing="0"
								bordercolor="#CCCCCC">
										<tr valign="middle">
											<td width="90%" valign="middle" height="60">
												<h1 class="title">Login</h1> <br>
											</td>
										</tr>
								<tr>
									<td colspan="2">
										<center>

											<!-- status messages -->
										</center>
										<div align="center">
											<p>Please provide your authentication information below.</p>
											<p>
												Employee Id: <input name='username' value='0' type="text"
													size="11" maxlength="11"> &nbsp;Password: <input
													name='password' value='' type="password" size="8"
													maxlength="10"> &nbsp; <input type="submit"
													name="submitted" value="Sign In">
											</p>
										</div>
										</form>
									</td>
								</tr>
								<tr>
									<td colspan="2">If you cannot remember your password please click <a href='forgot_password.php'>here</a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<!-- end #content -->
		<?php
		}
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
