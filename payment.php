<?php
session_start();
include 'pages/head.php';
include('auth.php');
include('conf.php');

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$request_id = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;

if ($request_id <= 0) {
    header("Location: request.php");
    exit;
}

// Get request details
$request_result = getRequestById($request_id);
$request = mysqli_fetch_assoc($request_result);

if (!$request || $request['customer_id'] != $_SESSION['customer_id']) {
    header("Location: request.php");
    exit;
}

// Allow payment for:
// 1. PayPal orders with pending or failed status
// 2. Any order with failed status (to allow switching to PayPal)
if ($request['payment_status'] == 'failed') {
    // Allow payment for any failed payment, regardless of original method
    // This allows switching from COD to PayPal if payment failed
} elseif ($request['payment_method'] == 'paypal' && $request['payment_status'] == 'pending') {
    // Allow payment for pending PayPal orders
} else {
    // Otherwise, redirect
    header("Location: tracking.php?order_id=" . $request_id);
    exit;
}

// Prevent payment if order is canceled
if ($request['tracking_status'] == 12) {
    header("Location: tracking.php?order_id=" . $request_id);
    exit;
}

// Prevent payment if order is already paid
if ($request['payment_status'] == 'paid') {
    header("Location: tracking.php?order_id=" . $request_id);
    exit;
}

$companyName = "FOJ Express";
$amount = floatval($request['total_fee']);
$paypal_client_id = 'ATF3NgqnXDgojMU7vjwdjYMENojiNMUdKDJb2npC8J6H0QThG8yfNUJUx8QTz9ILnf-7f57ys82pQssS';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        (function() {
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
                            return false;
                        }
                    } catch(e) {
                        window.location.replace('login.php');
                        return false;
                    }
                }
                return true;
            }
            
            // Prevent back navigation by replacing history
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function(event) {
                    window.history.pushState(null, null, window.location.href);
                    checkSession();
                });
            }
            
            // Check immediately on page load (before DOM is ready)
            if (!checkSession()) {
                return; // Stop execution if not authenticated
            }
            
            // Check when page is shown (including from cache/back button)
            window.addEventListener('pageshow', function(event) {
                checkSession();
            });
            
            // Check when page becomes visible (tab switch, window focus)
            if (document.addEventListener) {
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        checkSession();
                    }
                });
            }
            
            // Check when window gains focus
            window.addEventListener('focus', function() {
                checkSession();
            });
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
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

        main {
            display: grid;
            place-items: center;
            min-height: calc(100vh - 64px);
            padding: 40px 0
        }

        .payment-card {
            width: 100%;
            max-width: 600px;
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            padding: 32px
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px
        }

        p.lead {
            margin: 0 0 24px;
            color: var(--muted)
        }

        .payment-info {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px
        }

        .payment-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, .06)
        }

        .payment-info-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 18px
        }

        .payment-info-label {
            color: var(--muted)
        }

        .payment-info-value {
            font-weight: 600
        }

        #paypal-button-container {
            margin-top: 24px;
            min-height: 50px
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: var(--muted)
        }

        .error-message {
            background: rgba(239, 68, 68, .1);
            border: 1px solid var(--danger);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            color: var(--danger)
        }

        .success-message {
            background: rgba(16, 185, 129, .1);
            border: 1px solid var(--ok);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            color: var(--ok)
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
            transition: .2s ease;
            cursor: pointer
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 0, 0, .22)
        }

        .btn.light {
            background: rgba(0, 0, 0, .06)
        }
    </style>

</head>

<body>

    <?php include 'pages/header.php'; ?>

    <main>
        <div class="payment-card">
            <h1>Complete Payment</h1>
            <p class="lead">Pay securely with PayPal</p>

            <div class="payment-info">
                <div class="payment-info-row">
                    <span class="payment-info-label">Tracking Code:</span>
                    <span class="payment-info-value"><?= htmlspecialchars($request['tracking_code']); ?></span>
                </div>
                <div class="payment-info-row">
                    <span class="payment-info-label">Amount:</span>
                    <span class="payment-info-value">SAR<?= number_format($amount, 2); ?></span>
                </div>
            </div>

            <div id="error-message" class="error-message" style="display: none;"></div>
            <div id="success-message" class="success-message" style="display: none;"></div>

            <div id="paypal-button-container"></div>

            <div style="margin-top: 24px; text-align: center;">
                <a href="request.php" class="btn light">Cancel</a>
            </div>
        </div>
    </main>

    <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&currency=USD"></script>
    <script>
        const requestId = <?= $request_id; ?>;
        const amount = <?= $amount; ?>;

        function showError(message) {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            document.getElementById('success-message').style.display = 'none';
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('success-message');
            successDiv.textContent = message;
            successDiv.style.display = 'block';
            document.getElementById('error-message').style.display = 'none';
        }

        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch('server/api.php?function_code=createPayPalOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'request_id=' + requestId
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        return data.orderID;
                    } else {
                        throw new Error(data.error || 'Failed to create order');
                    }
                });
            },
            onApprove: function(data, actions) {
                return fetch('server/api.php?function_code=capturePayPalOrder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'orderID=' + data.orderID + '&request_id=' + requestId
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        showSuccess('Payment successful! Redirecting to tracking page...');
                        setTimeout(function() {
                            window.location.href = 'tracking.php';
                        }, 2000);
                    } else {
                        showError(data.error || 'Payment capture failed');
                    }
                })
                .catch(function(error) {
                    showError('An error occurred: ' + error.message);
                });
            },
            onError: function(err) {
                showError('PayPal error: ' + err);
            },
            onCancel: function(data) {
                showError('Payment was cancelled');
            }
        }).render('#paypal-button-container');
    </script>

</body>

</html>

