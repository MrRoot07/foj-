<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Set headers to prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page with a logout success message
session_start();
$_SESSION['status'] = "You have been logged out successfully.";
header("Location: login.php");
exit();
?>
