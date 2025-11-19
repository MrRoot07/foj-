<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/bootstrap/i18n.php';
include 'pages/head.php';
include 'auth.php';
include 'conf.php';

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

        .page-header {
            padding: 24px 0;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            margin-bottom: 10px
        }

        .page-header h1 {
            margin: 0;
            font-size: 26px
        }

        main {
            padding: 24px 0
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px
        }

        .order-card {
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            padding: 20px;
            transition: .2s ease;
            cursor: pointer
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow)
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px
        }

        .order-id {
            font-weight: 700;
            font-size: 18px;
            color: var(--brand)
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600
        }

        .status-pending {
            background: rgba(245, 158, 11, .1);
            color: var(--warn)
        }

        .status-preparing {
            background: rgba(37, 99, 235, .1);
            color: var(--brand)
        }

        .status-shipped {
            background: rgba(6, 182, 212, .1);
            color: var(--brand-2)
        }

        .status-delivered {
            background: rgba(16, 185, 129, .1);
            color: var(--ok)
        }

        .status-canceled {
            background: rgba(239, 68, 68, .1);
            color: var(--danger)
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 16px
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px
        }

        .info-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 500
        }

        .info-value {
            font-size: 14px;
            font-weight: 600
        }

        .order-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(0, 0, 0, .06)
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: .2s ease;
            border: none;
            cursor: pointer
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: white;
            box-shadow: var(--shadow)
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .15)
        }

        .btn-secondary {
            background: rgba(0, 0, 0, .06);
            color: var(--text)
        }

        .btn-secondary:hover {
            background: rgba(0, 0, 0, .12)
        }

        .btn-success {
            background: var(--ok);
            color: white
        }

        .btn-success:hover {
            background: #059669
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600
        }

        .badge-success {
            background: rgba(16, 185, 129, .1);
            color: var(--ok)
        }

        .badge-warning {
            background: rgba(245, 158, 11, .1);
            color: var(--warn)
        }

        .badge-danger {
            background: rgba(239, 68, 68, .1);
            color: var(--danger)
        }

        .badge-info {
            background: rgba(37, 99, 235, .1);
            color: var(--brand)
        }

        .badge-secondary {
            background: rgba(0, 0, 0, .06);
            color: var(--muted)
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted)
        }

        .empty-state h3 {
            margin-bottom: 8px;
            color: var(--text)
        }
    </style>
</head>

<body>
    <?php include 'pages/header.php'; ?>

    <div class="page-header">
        <div class="container-ex" style="display: flex; justify-content: space-between; align-items: center;">
            <h1><?php __e('orders_title'); ?></h1>
            <button type="button" class="btn btn-primary" onclick="openQRScanner()" style="display: inline-flex; align-items: center; gap: 8px;">
                <i class="bi bi-qr-code-scan"></i> <?php __e('orders_scan_qr'); ?>
            </button>
        </div>
    </div>

    <main>
        <div class="container-ex">
            <div class="orders-list">
                <?php
                $getall = getAllTrackingByCUS($_SESSION['customer_id']);
                $has_orders = false;

                while ($row = mysqli_fetch_assoc($getall)) {
                    $has_orders = true;
                    $request_id = $row['request_id'];
                    $payment_status = $row['payment_status'] ?? 'pending';
                    $payment_method = $row['payment_method'] ?? 'cod';
                    $amount = floatval($row['total_fee']);

                    // Get status text and class
                    $status_text = __t('orders_status_pending');
                    $status_class = 'status-pending';
                    if ($row['tracking_status'] == 2) {
                        $status_text = __t('orders_status_preparing');
                        $status_class = 'status-preparing';
                    } else if ($row['tracking_status'] == 3) {
                        $status_text = __t('orders_status_shipped');
                        $status_class = 'status-shipped';
                    } else if ($row['tracking_status'] == 4) {
                        $status_text = __t('orders_status_delivered');
                        $status_class = 'status-delivered';
                    } else if ($row['tracking_status'] == 5) {
                        $status_text = __t('orders_status_canceled');
                        $status_class = 'status-canceled';
                    }

                    // Get locations
                    $getLocation = getAllAreabyID($row['send_location']);
                    $locationRow = mysqli_fetch_assoc($getLocation);
                    $send_location = $locationRow['area_name'];

                    $getLocation = getAllAreabyID($row['end_location']);
                    $locationRow = mysqli_fetch_assoc($getLocation);
                    $end_location = $locationRow['area_name'];
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-id"><?php __e('orders_order_id'); ?><?php echo $row['request_id']; ?></div>
                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                                    <?php __e('orders_tracking'); ?> <?php echo htmlspecialchars($row['tracking_code']); ?>
                                </div>
                            </div>
                            <span class="order-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                        </div>

                        <div class="order-info">
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_amount'); ?></span>
                                <span class="info-value">$<?php echo number_format($amount, 2); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_payment_method'); ?></span>
                                <span class="info-value">
                                    <?php if ($payment_method == 'paypal'): ?>
                                        <span class="badge badge-info">PayPal</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?php __e('orders_payment_cod'); ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_payment_status'); ?></span>
                                <span class="info-value">
                                    <?php if ($payment_status == 'paid'): ?>
                                        <span class="badge badge-success"><?php __e('orders_payment_paid'); ?></span>
                                    <?php elseif ($payment_status == 'pending'): ?>
                                        <span class="badge badge-warning"><?php __e('orders_payment_pending'); ?></span>
                                    <?php elseif ($payment_status == 'failed'): ?>
                                        <span class="badge badge-danger"><?php __e('orders_payment_failed'); ?></span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_route'); ?></span>
                                <span class="info-value"><?php echo htmlspecialchars($send_location); ?> → <?php echo htmlspecialchars($end_location); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_weight'); ?></span>
                                <span class="info-value"><?php echo $row['weight']; ?> kg</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><?php __e('orders_date'); ?></span>
                                <span class="info-value"><?php echo date('M d, Y', strtotime($row['date_updated'])); ?></span>
                            </div>
                        </div>

                        <div class="order-actions">
                            <a href="tracking.php?order_id=<?php echo $request_id; ?>" class="btn btn-primary">
                                <i class="bi bi-eye"></i> <?php __e('orders_track'); ?>
                            </a>
                            <?php if ($payment_method == 'paypal' && $payment_status == 'pending'): ?>
                                <a href="payment.php?request_id=<?php echo $request_id; ?>" class="btn btn-success">
                                    <i class="bi bi-credit-card"></i> <?php __e('orders_pay_now'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }

                if (!$has_orders) {
                    ?>
                    <div class="empty-state">
                        <h3><?php __e('orders_no_orders'); ?></h3>
                        <p><?php __e('orders_no_orders_desc'); ?></p>
                        <a href="request.php" class="btn btn-primary" style="margin-top: 16px;">
                            <?php __e('orders_create_order'); ?>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </main>

    <?php include 'pages/footer.php'; ?>
    
    <!-- QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <!-- QR Scanner Modal -->
    <div id="qrScannerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%; position: relative;">
            <button onclick="closeQRScanner()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            <h3 style="margin-top: 0;">Scan QR Code</h3>
            <div id="qr-reader" style="width: 100%;"></div>
            <div id="qr-reader-results"></div>
        </div>
    </div>
    
    <script>
        let html5QrcodeScanner = null;
        
        function openQRScanner() {
            document.getElementById('qrScannerModal').style.display = 'flex';
            setTimeout(() => {
                startQRScanner();
            }, 100);
        }
        
        function closeQRScanner() {
            stopQRScanner();
            document.getElementById('qrScannerModal').style.display = 'none';
        }
        
        function startQRScanner() {
            if (html5QrcodeScanner) {
                return;
            }
            
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
            
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    handleQRCode(decodedText);
                },
                (errorMessage) => {
                    // Ignore errors
                }
            ).catch((err) => {
                console.error("Unable to start scanning", err);
                alert("Unable to access camera. Please ensure camera permissions are granted.");
                closeQRScanner();
            });
        }
        
        function stopQRScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    html5QrcodeScanner = null;
                }).catch((err) => {
                    console.error("Error stopping scanner", err);
                });
            }
        }
        
        function handleQRCode(url) {
            stopQRScanner();
            closeQRScanner();
            
            try {
                const urlObj = new URL(url);
                const orderId = urlObj.searchParams.get('order_id');
                
                if (orderId) {
                    window.location.href = 'tracking.php?order_id=' + orderId;
                } else {
                    alert('Invalid QR code. Could not find order ID.');
                }
            } catch (e) {
                alert('Invalid QR code format.');
            }
        }
    </script>
</body>

</html>

