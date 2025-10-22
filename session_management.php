<?php
// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user has been inactive for more than 30 minutes (1800 seconds)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Unset all session variables and destroy the session
    session_unset();
    session_destroy();
    
    // Redirect the user to the login page with a message
    header('Location: login.php?status=session_expired');
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>
