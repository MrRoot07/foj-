<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/bootstrap/i18n.php';
include 'pages/head.php';
include('auth.php');
include('conf.php');

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="css/rtl.css">
    <?php endif; ?>

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

        .field input,
        .field select,
        .field textarea {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none;
            font-family: inherit;
            font-size: inherit;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            box-shadow: var(--ring);
            border-color: transparent
        }

        .field textarea {
            resize: vertical;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .mt-2 {
            margin-top: 16px;
        }

        .mt-4 {
            margin-top: 24px;
        }

        .mb-1 {
            margin-bottom: 8px;
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

    <?php include 'pages/header.php'; ?>

    <main
        style="min-height: calc(100vh - 64px); display: flex; justify-content: center; align-items: flex-start; padding: 40px 0;">
        <div class="auth-card" style="max-width:900px; width:100%;">

            <h1 class="mb-1"><?php __e('request_title'); ?></h1>
            <p class="lead"><?php __e('request_desc'); ?></p>

            <form method="POST" action="#" onsubmit="event.preventDefault();">

                <h3 class="mt-2"><?php __e('request_sender_details'); ?></h3>
                <div class="grid">
                    <div class="field">
                        <label><?php __e('request_sender_phone'); ?></label>
                        <input type="text" name="sender_phone" id="sender_phone" required>
                    </div>
                    <div class="field">
                        <label><?php __e('request_weight'); ?></label>
                        <input type="number" name="weight" id="weight" required>
                    </div>
                    <div class="field">
                        <label><?php __e('request_send_location'); ?></label>
                        <select name="send_location" id="send_location" required>
                            <option value="">Select</option>
                            <?php $getall = getAllArea();
                            while ($row = mysqli_fetch_assoc($getall)) { ?>
                                <option value="<?= $row['area_id']; ?>"><?= $row['area_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="field">
                        <label><?php __e('request_pickup_location'); ?></label>
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
                    <label><?php __e('request_price'); ?></label>
                    <input type="text" disabled id="total">
                    <input type="hidden" name="total_fee" id="total_fee">
                    <input type="hidden" name="customer_id" value="<?= $_SESSION['customer_id']; ?>">
                </div>

                <h3 class="mt-4"><?php __e('request_payment_method'); ?></h3>
                <div class="field">
                    <label><?php __e('request_payment_select'); ?></label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="cod"><?php __e('request_payment_cod'); ?></option>
                        <option value="paypal"><?php __e('request_payment_paypal'); ?></option>
                    </select>
                </div>

                <h3 class="mt-4"><?php __e('request_receiver_details'); ?></h3>
                <div class="grid">
                    <div class="field">
                        <label><?php __e('request_receiver_name'); ?></label>
                        <input type="text" name="res_name" id="res_name" required>
                    </div>
                    <div class="field">
                        <label><?php __e('request_receiver_phone'); ?></label>
                        <input type="text" name="res_phone" id="res_phone" required>
                    </div>
                </div>

                <div class="field">
                    <label><?php __e('request_receiver_address'); ?></label>
                    <textarea name="red_address" id="red_address" required rows="4"></textarea>
                </div>

                <div style="margin-top:16px; display:flex; gap:12px;">
                    <button type="button" class="btn primary" onclick="addRequest(this.form)"><?php __e('request_send'); ?></button>
                    <a class="btn light" href="index.php"><?php __e('cancel'); ?></a>
                </div>

            </form>
        </div>
    </main>

    <?php include 'pages/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function calculation(ele) {
            var send_location = document.getElementById("send_location").value;
            var end_location = document.getElementById(ele.id).value;
            var weight = document.getElementById("weight").value;

            var data = {
                send_location: send_location,
                end_location: end_location,
            };

            if (weight.trim() != "") {
                $.ajax({
                    method: "POST",
                    url: "server/api.php?function_code=checkArea",
                    data: data,
                    success: function ($data) {
                        if ($data > 0) {
                            var sum = parseInt(weight) * parseInt($data);
                            document.getElementById("total").value = "$" + sum;
                            document.getElementById("total_fee").value = sum;
                        } else {
                            alert("This area is not in our service area");
                        }
                    },
                    error: function (error) {
                        console.log(`Error ${error}`);
                    },
                });
            } else {
                alert("Please enter Weight");
            }
        }

        function addRequest(form) {
            var formData = new FormData(form);
            var payment_method = formData.get("payment_method");

            if (formData.get("sender_phone").trim() != "") {
                if (formData.get("weight").trim() != "") {
                    if (formData.get("total_fee").trim() != "") {
                        if (formData.get("res_phone").trim() != "") {
                            if (formData.get("red_address").trim() != "") {
                                $.ajax({
                                    method: "POST",
                                    url: "server/api.php?function_code=addRequest",
                                    data: formData,
                                    success: function ($data) {
                                        try {
                                            var response = typeof $data === 'string' ? JSON.parse($data) : $data;
                                            if (response.success) {
                                                if (payment_method === 'paypal') {
                                                    // Redirect to payment page for PayPal
                                                    window.location.href = "payment.php?request_id=" + response.request_id;
                                                } else {
                                                    // COD - redirect to tracking
                                                    alert("Request submitted successfully!");
                                                    window.location.href = "tracking.php";
                                                }
                                            } else {
                                                alert("Failed to submit request");
                                            }
                                        } catch (e) {
                                            console.error("Error parsing response:", e, $data);
                                            alert("An error occurred. Please try again.");
                                        }
                                    },
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    error: function (error) {
                                        console.log(`Error ${error}`);
                                        alert("An error occurred. Please try again.");
                                    },
                                });
                            } else {
                                alert("Please Enter Receiver Address");
                            }
                        } else {
                            alert("Please Enter Receiver Phone Number");
                        }
                    } else {
                        alert("Please Enter Locations to get Price");
                    }
                } else {
                    alert("Please Enter Parcel Weight");
                }
            } else {
                alert("Please Enter Your Phone Number");
            }
        }
    </script>

</body>

</html>