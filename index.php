<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once ('includes/config.inc.php');
$page_title = 'Home Page';
$url = BASE_URL . 'signin.php'; // Define the URL:
ob_end_clean(); // Delete the buffer.
header("Location: $url");
exit(); // Quit the script.
?>