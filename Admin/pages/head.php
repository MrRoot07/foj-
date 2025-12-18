<?php
// Include i18n bootstrap
require_once __DIR__ . '/../../bootstrap/i18n.php';
$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
<head>
    <?php include 'pages/assets.php'; ?>
    <?php include '../server/api.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dashboard - <?php echo $companyName; ?></title>
    <?php if (isset($_SESSION['admin'])): ?>
    <script>
        // Check session on page load and when navigating back (pageshow event)
        function checkAdminSession() {
            if (typeof XMLHttpRequest !== 'undefined') {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '../server/api.php?function_code=checkAdminSession&_=' + new Date().getTime(), false);
                xhr.send();
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (!response.authenticated) {
                        window.location.replace('login.php');
                    }
                } catch(e) {
                    window.location.replace('login.php');
                }
            }
        }
        // Check immediately
        checkAdminSession();
        // Check when page is shown (including from cache/back button)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // Page was loaded from cache
                checkAdminSession();
            }
        });
    </script>
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.jpg" type="image/x-icon">
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>