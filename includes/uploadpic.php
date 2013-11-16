<?php

	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$filename = mysqli_real_escape_string($dbc, $_FILES["picfile"]["name"]);
	$fileparts = explode(".", $filename);
	$extension = end($fileparts);
	if ((($_FILES["picfile"]["type"] == "image/gif")
	|| ($_FILES["picfile"]["type"] == "image/jpeg")
	|| ($_FILES["picfile"]["type"] == "image/png")
	|| ($_FILES["picfile"]["type"] == "image/pjpeg"))
	&& ($_FILES["picfile"]["size"] < 600000)
	&& in_array($extension, $allowedExts))
	{
		if ($_FILES["picfile"]["error"] > 0)
		{
			$errors[] = "Return Code: " . $_FILES["picfile"]["error"];
		}
		else
		{
			#echo "Upload: " . $_FILES["picfile"]["name"] . "<br />";
			#echo "Type: " . $_FILES["picfile"]["type"] . "<br />";
			#echo "Size: " . ($_FILES["picfile"]["size"] / 1024) . " KB<br />";
			#echo "Temp file: " . $_FILES["picfile"]["tmp_name"] . "<br />";

			if (file_exists("images/uploads/" . $_FILES["picfile"]["name"]))
			{
				$errors[] = $_FILES["picfile"]["name"] . " already exists. ";
			}
			else{
				$filename = $_FILES["picfile"]["name"] . "_" . strtotime("now") . "." . $extension;
				move_uploaded_file($_FILES["picfile"]["tmp_name"],"images/uploads/" . $filename);
				#echo "Stored in: " . "images/uploads/" . $filename;
			}

		}
	}
	else
	{
		$errors[] = 'Invalid file type';
	}

?>