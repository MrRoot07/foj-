<?php

if (session_id() == '') {
    session_start();
}

// Set headers to prevent caching of authenticated pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Helper functions for role checking
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

function isEmployee() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'employee';
}

function getUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

// Check if employee is trying to access restricted page
function checkEmployeeAccess($allowed_pages = []) {
    if (isEmployee()) {
        $current_page = basename($_SERVER['PHP_SELF']);
        if (!in_array($current_page, $allowed_pages)) {
            header("Location: index.php");
            exit();
        }
    }
}
