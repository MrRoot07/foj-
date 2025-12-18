<?php
if (session_id() == '') {
    session_start();
}

// Set headers to prevent caching of authenticated pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['auth'])){  // Use the same session variable throughout
    header("Location: login.php");
    exit(); // Always add exit after redirect
}else{
    $getall = getAllcustomerById($_SESSION['customer_id']); // Store customer ID separately
    $cus = mysqli_fetch_assoc($getall);
    $customer_id = $cus['customer_id']; 
}
?>