<?php
session_start();
include 'pages/head.php';
include('auth.php');
include('conf.php');

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$companyName = "FOJ Express";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        /* Same design tokens as the landing page (light theme) */
        :root {
            --bg: #ffffff;
            --panel: #f7f9fc;
            --muted: #556070;
            --text: #0b0d13;
            --brand: #2563eb;
            --brand-2: #06b6d4;
            --ok: #10b981;
            --warn: #f59e0b;
            --danger: #ef4444;
            --ring: 0 0 0 3px rgba(37, 99, 235, .25);
            --radius: 14px;
            --shadow: 0 8px 24px rgba(0, 0, 0, .12), 0 2px 8px rgba(0, 0, 0, .08);
            --shadow-soft: 0 6px 18px rgba(0, 0, 0, .08), inset 0 1px 0 rgba(255, 255, 255, .6);
            --grid-max: 1200px;
        }

        * {
            box-sizing: border-box
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
            color: inherit;
            text-decoration: none
        }

        .container {
            max-width: var(--grid-max);
            margin: 0 auto;
            padding: 0 20px
        }

        /* Header / Nav copied from landing page */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .85);
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
            font-weight: 800;
            letter-spacing: .2px
        }

        .brand .logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: grid;
            place-items: center;
            box-shadow: var(--shadow-soft)
        }

        .brand .logo svg {
            width: 22px;
            height: 22px
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px
        }

        .nav-cta {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, .12);
            padding: 10px 14px;
            border-radius: 12px;
            background: transparent;
            color: var(--text);
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
            color: white;
            box-shadow: var(--shadow)
        }

        .btn.light {
            background: rgba(0, 0, 0, .06)
        }

        .hamburger {
            display: none;
            background: transparent;
            border: none;
            color: var(--text)
        }

        .mobile-menu {
            display: none
        }

        @media (max-width:980px) {
            .nav-links {
                display: none
            }

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

            .mobile-menu a,
            .mobile-menu .btn {
                display: flex
            }

            .mobile-menu.open {
                display: flex
            }
        }

        /* Page layout */
        main {
            display: grid;
            place-items: center;
            min-height: calc(100vh - 64px);
            padding: 40px 0
        }

        .auth-card {
            width: 100%;
            max-width: 560px;
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            padding: 22px
        }

        h1 {
            margin: 4px 0 6px;
            font-size: 28px
        }

        p.lead {
            margin: 0 0 18px;
            color: var(--muted)
        }

        /* Form */
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px
        }

        .field input {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none
        }

        .field input:focus {
            box-shadow: var(--ring);
            border-color: transparent
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px
        }

        .helper {
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            margin-top: 12px
        }

        /* Footer */
        footer {
            border-top: 1px solid rgba(0, 0, 0, .08);
            padding: 28px 0;
            color: var(--muted)
        }
    </style>

</head>

<body>

    <header>
        <div class="container nav">
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

            <div class="nav-cta">
                <a class="btn primary" href="request.php">Request</a>
                <a class="btn light" href="logout.php">Logout</a>
            </div>

            <button class="hamburger" onclick="toggleMenu()">
                <svg width="26" height="26" viewBox="0 0 24 24">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu container">
            <a href="index.php#services" onclick="toggleMenu(false)">Services</a>
            <a href="index.php#about" onclick="toggleMenu(false)">About</a>
            <a href="index.php#gallery" onclick="toggleMenu(false)">Gallery</a>
            <a href="index.php#contact" onclick="toggleMenu(false)">Contact</a>
            <a class="btn primary" href="tracking.php">Tracking</a>
            <a class="btn primary" href="request.php">Request</a>
            <a class="btn light" href="logout.php">Logout</a>
        </div>
    </header>

    <main
        style="min-height: calc(100vh - 64px); display: flex; justify-content: center; align-items: flex-start; padding: 40px 0;">
        <div class="auth-card" style="max-width:900px; width:100%;">

            <h1 class="mb-1">Create Delivery Request</h1>
            <p class="lead">Fill in the shipment & receiver information below.</p>

            <form method="POST" action="#" onsubmit="event.preventDefault();">

                <h3 class="mt-2">Sender Details</h3>
                <div class="grid">
                    <div class="field">
                        <label>Phone Number</label>
                        <input type="text" name="sender_phone" id="sender_phone" required>
                    </div>
                    <div class="field">
                        <label>Weight (kg)</label>
                        <input type="number" name="weight" id="weight" required>
                    </div>
                    <div class="field">
                        <label>Sending Location</label>
                        <select name="send_location" id="send_location" required>
                            <option value="">Select</option>
                            <?php $getall = getAllArea();
                            while ($row = mysqli_fetch_assoc($getall)) { ?>
                                <option value="<?= $row['area_id']; ?>"><?= $row['area_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Pick Up Location</label>
                        <select name="end_location" id="end_location" onchange="calculation(this)" required>
                            <option value="">Select</option>
                            <?php $getall = getAllArea();
                            while ($row = mysqli_fetch_assoc($getall)) { ?>
                                <option value="<?= $row['area_id']; ?>"><?= $row['area_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Price (Auto Calculated)</label>
                    <input type="text" disabled id="total">
                    <input type="hidden" name="total_fee" id="total_fee">
                    <input type="hidden" name="customer_id" value="<?= $_SESSION['auth']; ?>">
                </div>

                <h3 class="mt-4">Receiver Details</h3>
                <div class="grid">
                    <div class="field">
                        <label>Receiver Name</label>
                        <input type="text" name="res_name" id="res_name" required>
                    </div>
                    <div class="field">
                        <label>Receiver Phone</label>
                        <input type="text" name="res_phone" id="res_phone" required>
                    </div>
                </div>

                <div class="field">
                    <label>Receiver Address</label>
                    <textarea name="red_address" id="red_address" required rows="4"></textarea>
                </div>

                <div style="margin-top:16px; display:flex; gap:12px;">
                    <button type="button" class="btn primary" onclick="addRequest(this.form)">Send Request</button>
                    <a class="btn light" href="index.php">Cancel</a>
                </div>

            </form>
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

</body>

</html>