<?php # Script 16.8 - login.php
// This is the login page for the site.

require_once ('includes/config.inc.php');
require_once ('mysqli_connect.php'); // Connect to the db.

// Validate the email address:
if (!empty($_GET['u'])) {
	$u = mysqli_real_escape_string ($dbc, $_GET['u']);
} else {
	$u = FALSE;
	echo '<p class="error">You forgot to enter your employee id!';
			echo '<a href="register.php", class="welcome"> Register </a>';
			echo '<a href="forgotPassword.php", class="welcome">Forgot Password</a>';
}

// Validate the password:
if (!empty($_GET['p'])) {
	$p = mysqli_real_escape_string ($dbc, $_GET['p']);
} else {
	$p = FALSE;
	echo '<p class="error">You forgot to enter your password!';
			echo '<a href="register.php", class="welcome"> Register </a>';
			echo '<a href="forgotPassword.php", class="welcome">Forgot Password</a>';
}

if ($u && $p) { // If everything's OK.

	// Query the database:
	$q = "SELECT id, employeeId, name, email, employeeType, managerId FROM employee
				      WHERE (employeeId='$u' AND password=SHA1('$p'))";
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

	if (@mysqli_num_rows($r) == 1) { // A match was made.
			
		session_start();

		// Put user in the session and send back a Welcome message
		$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC);
		mysqli_free_result($r);
		echo '<p class="welcome">Welcome ' . $_SESSION['name'] . '</p>';
		echo '<a href="logout.php", class="welcome">Logout<br></br></a>';
				
	} else { // No match was made.  Send back error message and login form.
		echo '<p class="error">invalid employee id and/or password.</p>';
		echo "<form><div align='left'>";
		echo "Employee Id:<input id='username' name='username' value='0' type='text' size='11' maxlength='11'><br/>";
		echo "Password: &nbsp;&nbsp;&nbsp;&nbsp;<input id='password' name='password' value='' type='password' size='11' maxlength='11'><br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type='button' name='submitted' value='Sign In' onclick='loginUser();'></div></form>";		
	}

} else { // If everything wasn't OK.
		echo "<form><div align='left'>";
		echo "Employee Id:<input id='username' name='username' value='0' type='text' size='11' maxlength='11'><br/>";
		echo "Password: &nbsp;&nbsp;&nbsp;&nbsp;<input id='password' name='password' value='' type='password' size='11' maxlength='11'><br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type='button' name='submitted' value='Sign In' onclick='loginUser();'></div></form>";		
	}

mysqli_close($dbc);
?>