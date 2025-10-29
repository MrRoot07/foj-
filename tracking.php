<?php
session_start();
include 'pages/head.php';
include 'auth.php';
include 'conf.php';

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$companyName = "FOJ Express";
?>
<!DOCTYPE html>
<html lang="en">

<body>


    <style>
        :root {
            --bg: #ffffff;
            --panel: #f7f9fc;
            --muted: #556070;
            --text: #0b0d13;
            --brand: #2563eb;
            --brand-2: #06b6d4;
            --radius: 14px;
            --shadow: 0 8px 24px rgba(0, 0, 0, .12), 0 2px 8px rgba(0, 0, 0, .08);
            --grid-max: 1200px;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif
        }

        a {
            text-decoration: none;
            color: inherit
        }

        .container-ex {
            max-width: var(--grid-max);
            margin: 0 auto;
            padding: 0 20px
        }

        /* Header / Nav (same as other pages) */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, .08)
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800
        }

        .brand .logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2))
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, .12);
            padding: 10px 14px;
            border-radius: 12px;
            background: transparent;
            font-weight: 600;
            transition: .2s ease
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 0, 0, .22)
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            border: none;
            color: #fff;
            box-shadow: var(--shadow)
        }

        .btn.light {
            background: rgba(0, 0, 0, .06)
        }

        .hamburger {
            display: none;
            background: transparent;
            border: none
        }

        .mobile-menu {
            display: none
        }

        @media (max-width:980px) {

            .nav-links,
            .nav-cta {
                display: none
            }

            .hamburger {
                display: inline-flex;
                padding: 8px;
                border-radius: 10px
            }

            .mobile-menu {
                position: fixed;
                inset: 64px 12px auto 12px;
                background: var(--panel);
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 16px;
                box-shadow: var(--shadow);
                padding: 14px;
                display: none;
                flex-direction: column;
                gap: 10px
            }

            .mobile-menu.open {
                display: flex
            }
        }

        /* Page title wrapper */
        .page-header {
            padding: 24px 0;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            margin-bottom: 10px
        }

        .page-header h1 {
            margin: 0;
            font-size: 26px
        }

        /* Keep content width aligned with other pages */
        main {
            padding: 24px 0
        }
    </style>

    <header>
        <div class="container-ex nav">
            <a href="index.php" class="brand" aria-label="Home">
                <span class="logo"></span>
                <span><?php echo $companyName; ?></span>
            </a>

            <nav class="nav-links">
                <a href="index.php#services">Services</a>
                <a href="index.php#about">About</a>
                <a href="index.php#gallery">Gallery</a>
                <a href="index.php#contact">Contact</a>
            </nav>

            <?php if (isset($_SESSION['auth'])): ?>
                <div class="nav-cta">
                    <a class="btn" href="tracking.php">Tracking</a>
                    <a class="btn primary" href="request.php">Request</a>
                    <a class="btn light" href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <div class="nav-cta">
                    <a class="btn light" href="login.php">Log in</a>
                    <a class="btn primary" href="register.php">Register</a>
                </div>
            <?php endif; ?>

            <button class="hamburger" aria-label="Open menu" onclick="toggleMenu()">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu container-ex" role="dialog" aria-modal="true">
            <a href="index.php#services" onclick="toggleMenu(false)">Services</a>
            <a href="index.php#about" onclick="toggleMenu(false)">About</a>
            <a href="index.php#gallery" onclick="toggleMenu(false)">Gallery</a>
            <a href="index.php#contact" onclick="toggleMenu(false)">Contact</a>

            <?php if (isset($_SESSION['auth'])): ?>
                <a class="btn primary" href="request.php">Request</a>
                <a class="btn light" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn light" href="login.php">Log in</a>
                <a class="btn primary" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="page-header">
        <div class="container-ex">
            <h1>Tracking</h1>
        </div>
    </div>

    <main>
        <div class="container-ex">

            <?php
            $getall = getAllTrackingByCUS($_SESSION['auth']);
            while ($row = mysqli_fetch_assoc($getall)) {
                $request_id = $row['request_id'];
                ?>
                <!-- KEEPING THE ORIGINAL BOX/STYLES BELOW -->
                <article class="card mt-5" style="border: 2px solid #2c3e50">
                    <header class="card-header text-white" style="background-color: #2c3e50; border-radius: 0px;">
                        Orders / Tracking
                    </header>
                    <div class="card-body">
                        <h6>Traking ID: #<?php echo $row['request_id']; ?></h6>

                        <article class="card">
                            <div class="card-body row">

                                <div class="col"> <strong>Shipping Address:</strong>
                                    <br><?php echo $row['red_address']; ?>
                                </div>
                                <div class="col"> <strong>Recever Mobile:</strong>
                                    <br><?php echo $row['res_phone']; ?>
                                </div>
                                <div class="col"> <strong>Current Status:</strong>
                                    <br>
                                    <?php
                                    if ($row['tracking_status'] == 1) {
                                        echo 'Order Pending';
                                    } else if ($row['tracking_status'] == 2) {
                                        echo 'Prepare Order';
                                    } else if ($row['tracking_status'] == 3) {
                                        echo 'Shipped Order';
                                    } else if ($row['tracking_status'] == 4) {
                                        echo 'Deliverd';
                                    } else if ($row['tracking_status'] == 5) {
                                        echo 'Canceled';
                                    }
                                    ?>
                                </div>
                                <div class="col"> <strong>Requested Date:</strong>
                                    <br><?php echo $row['date_updated']; ?>
                                </div>
                            </div>

                            <div class="card-body row">
                                <div class="col"> <strong>Weight:</strong>
                                    <br><?php echo $row['weight']; ?>
                                </div>
                                <div class="col"> <strong>Sender Mobile:</strong>
                                    <br><?php echo $row['sender_phone']; ?>
                                </div>
                                <div class="col"> <strong>Send Location</strong>
                                    <br>
                                    <?php
                                    $getLocation = getAllAreabyID($row['send_location']);
                                    $row2 = mysqli_fetch_assoc($getLocation);
                                    echo $row2['area_name'];
                                    ?>
                                </div>
                                <div class="col"> <strong>End Location</strong>
                                    <br>
                                    <?php
                                    $getLocation = getAllAreabyID($row['end_location']);
                                    $row2 = mysqli_fetch_assoc($getLocation);
                                    echo $row2['area_name'];
                                    ?>
                                </div>
                            </div>
                        </article>

                        <?php if ($row['tracking_status'] != 5) { ?>
                            <div class="track">
                                <div class="step <?php if (in_array($row['tracking_status'], [1, 2, 3, 4]))
                                    echo 'active'; ?>">
                                    <span class="icon"><i class="fa-solid fa-clock"></i></span>
                                    <span class="text">Order Pending</span>
                                </div>

                                <div class="step <?php if (in_array($row['tracking_status'], [2, 3, 4]))
                                    echo 'active'; ?>">
                                    <span class="icon"><i class="fa-solid fa-box-open"></i></span>
                                    <span class="text">Prepare Order</span>
                                </div>

                                <div class="step <?php if (in_array($row['tracking_status'], [3, 4]))
                                    echo 'active'; ?>">
                                    <span class="icon"><i class="fa-solid fa-truck-fast"></i></span>
                                    <span class="text">Shipped Order</span>
                                </div>

                                <div class="step <?php if ($row['tracking_status'] == 4)
                                    echo 'active'; ?>">
                                    <span class="icon"><i class="fa-solid fa-circle-check"></i></span>
                                    <span class="text">Delivered</span>
                                </div>
                            </div>

                        <?php } ?>

                        <hr>

                        <div class="row">
                            <?php if ($row['tracking_status'] == "1") { ?>
                                <div class="col-md-3">
                                    <label for="tracking_status" class="form-label">Order Cancel</label>
                                    <select
                                        onchange='updateDataFromHome(this, "<?php echo $request_id; ?>","tracking_status", "request", "request_id")'
                                        id="tracking_status <?php echo $request_id; ?>" class='form-control norad tx12'
                                        name="tracking_status" type='text'>
                                        <option value="1">Please Select</option>
                                        <option value="5" <?php if ($row['tracking_status'] == "5")
                                            echo "selected"; ?>>Canceled
                                        </option>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- <div class="row mt-3">
                            <a href="admin/getbill.php?customer_id=<?php echo $_SESSION['auth']; ?>"
                                class="btn btn-darkblue">Print <i class="fa-solid fa-file-pdf"></i></a>
                        </div> -->

                    </div>
                </article>
            <?php } ?>

        </div>
    </main>

    <?php include 'pages/footer.php'; ?>

    <script>
        function toggleMenu(force) {
            const el = document.getElementById('mobileMenu');
            const isOpen = typeof force === 'boolean' ? force : !el.classList.contains('open');
            el.classList.toggle('open', isOpen);
        }
    </script>

    <!-- Preserve the original box/track styling exactly as your previous page -->
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        /* Keep these styles as-is for the classic tracking boxes */
        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.10rem
        }

        .card-header:first-child {
            border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1)
        }

        .track {
            position: relative;
            background-color: #e5e7eb;
            height: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 50px 0 60px;
            border-radius: 4px;
        }

        .track {
            position: relative;
            background-color: #e5e7eb;
            height: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 50px 0 60px;
            border-radius: 4px;
        }

        .track .step {
            flex: 1;
            position: relative;
            text-align: center;
        }

        .track .step::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            height: 8px;
            width: 100%;
            background: #e5e7eb;
            z-index: 0;
        }

        .track .step.active::before {
            background: #2c3e50;
        }

        .track .icon {
            z-index: 1;
            background: #e5e7eb;
            width: 42px;
            height: 42px;
            line-height: 42px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #6b7280;
            font-size: 18px;
            margin-bottom: 6px;
        }

        .track .step.active .icon {
            background: #2c3e50;
            color: #fff;
        }

        .track .text {
            display: block;
            font-size: 14px;
            color: #374151;
            margin-top: 6px;
        }

        .track .step.active .text {
            font-weight: 600;
            color: #111827;
        }


        .itemside {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .itemside .aside {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px
        }

        ul.row,
        ul.row-sm {
            list-style: none;
            padding: 0
        }

        .itemside .info {
            padding-left: 15px;
            padding-right: 7px
        }

        .itemside .title {
            display: block;
            margin-bottom: 5px;
            color: #212529
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .btn-warning {
            color: #ffffff;
            background-color: #2c3e50;
            border-color: #2c3e50;
            border-radius: 1px
        }

        .btn-warning:hover {
            color: #ffffff;
            background-color: #ff2b00;
            border-color: #ff2b00;
            border-radius: 1px
        }
    </style>

</body>

</html>