<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page with a logout success message
session_start();
$_SESSION['status'] = "You have been logged out successfully.";
header("Location: login.php");
exit();
?>
