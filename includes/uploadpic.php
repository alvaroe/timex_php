<?php

	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$filename = mysqli_real_escape_string($dbc, $_FILES["file"]["name"]);
	$fileparts = explode(".", $filename);
	$extension = end($fileparts);
	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/jpg"))
	&& ($_FILES["file"]["size"] < 600000)
	&& in_array($extension, $allowedExts))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			$errors[] = "Return Code: " . $_FILES["file"]["error"];
		}
		else
		{
			#echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			#echo "Type: " . $_FILES["file"]["type"] . "<br />";
			#echo "Size: " . ($_FILES["file"]["size"] / 1024) . " KB<br />";
			#echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

			if (file_exists("images/uploads/" . $_FILES["file"]["name"]))
			{
				$errors[] = $_FILES["file"]["name"] . " already exists. ";
			}
			else{
				$filename = $_FILES["file"]["name"] . "_" . strtotime("now") . "." . $extension;
				move_uploaded_file($_FILES["file"]["tmp_name"],"images/uploads/" . $filename);
				#echo "Stored in: " . "images/uploads/" . $filename;
			}

		}
	}
	else
	{
		$errors[] = 'Invalid file type';
	}

?>