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
		if (isset($_POST['submitted'])) {
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$fileName = $_FILES["file"]["name"];
			$fileparts = explode(".", $fileName);
			$extension = end($fileparts);
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 600000)
			&& in_array($extension, $allowedExts))
			{
				if ($_FILES["file"]["error"] > 0)
				{
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
				else
				{
					echo "Upload: " . $_FILES["file"]["name"] . "<br />";
					echo "Type: " . $_FILES["file"]["type"] . "<br />";
					echo "Size: " . ($_FILES["file"]["size"] / 1024) . " KB<br />";
					echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

					if (file_exists("images/uploads/" . $_FILES["file"]["name"]))
					{
						echo $_FILES["file"]["name"] . " already exists. ";
					}
					else{
						$fileName = $_FILES["file"]["name"] . "_" . strtotime("now") . "." . $extension;
						move_uploaded_file($_FILES["file"]["tmp_name"],"images/uploads/" . $fileName);
						echo "Stored in: " . "images/uploads/" . $fileName;
					}

				}
			}
			else
			{
				echo "Invalid file type";
			}
			require_once ('mysqli_connect.php'); // Connect to the db.

			// Make the query:
			$q = "INSERT INTO pictures (User_ID, Author_ID, Picture) VALUES ( $_SESSION[id], '1', '$fileName')";
			$r = @mysqli_query ($dbc, $q); // Run the query.
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
				echo '<h3>Your file has been uploaded.</h3>';
			} else { // If it did not run OK.
				echo '<p class="error">Your file could NOT be uploaded.</p>';
			}

			mysqli_close($dbc);
		}
		?>