<?php
session_start();
session_destroy();
require_once ('includes/config.inc.php');
$url = BASE_URL . 'signin.php'; // Define the URL:
ob_end_clean(); // Delete the buffer.
header("Location: $url");
exit(); // Quit the script.
?>