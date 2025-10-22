<?php
if (session_id() == '') {
    session_start();
}

if(!isset($_SESSION['auth'])){  // Use the same session variable throughout
    header("Location: login.php");
    exit(); // Always add exit after redirect
}else{
    $getall = getAllcustomerById($_SESSION['customer_id']); // Store customer ID separately
    $cus = mysqli_fetch_assoc($getall);
    $customer_id = $cus['customer_id']; 
}
?>