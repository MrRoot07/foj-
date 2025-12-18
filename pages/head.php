<head>
    <?php
    include 'server/api.php';
    include 'pages/assets.php';
    include('session_management.php');

    // Your protected page content here
    
    $setting = getAllSettings();
    $res = mysqli_fetch_assoc($setting);

    $header = $res['header_image'];
    $header_src = "server/uploads/settings/" . $header;

    $subheader = $res['sub_image'];
    $subheader_src = "server/uploads/settings/" . $subheader;

    $about = $res['about_image'];
    $about_src = "server/uploads/settings/" . $about;

    $background_image = $res['background_image'];
    $background_image_src = "server/uploads/settings/" . $background_image;


    ?>
    <title>FOJ Express</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <?php if (isset($_SESSION['auth'])): ?>
    <script>
        // Check session on page load and when navigating back (pageshow event)
        function checkSession() {
            if (typeof XMLHttpRequest !== 'undefined') {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'server/api.php?function_code=checkSession&_=' + new Date().getTime(), false);
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
        checkSession();
        // Check when page is shown (including from cache/back button)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // Page was loaded from cache
                checkSession();
            }
        });
    </script>
    <?php endif; ?>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,700,900|Display+Playfair:200,300,400,700">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">



    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

</head>