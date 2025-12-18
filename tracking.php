<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/bootstrap/i18n.php';
include 'conf.php';
include 'server/inc/get.php';
include 'auth.php';

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
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome for status icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="css/rtl.css">
    <?php endif; ?>

    <style>
        /* Same design tokens as the request page (light theme) */
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
            box-sizing: border-box;
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
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            margin-bottom: 20px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 26px
        }

        main {
            padding: 40px 0;
            min-height: calc(100vh - 64px);
        }

        .tracking-card {
            width: 100%;
            max-width: 1200px;
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            padding: 32px;
            margin-bottom: 24px;
        }

        .tracking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .tracking-title {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .tracking-id {
            font-weight: 700;
            font-size: 20px;
            color: var(--brand);
        }

        .tracking-code-text {
            font-size: 13px;
            color: var(--muted);
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .qr-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .qr-image-wrapper img {
            max-width: 140px;
            height: auto;
            border: 2px solid rgba(0, 0, 0, .08);
            border-radius: 8px;
            padding: 12px;
            background: white;
            transition: transform 0.2s;
        }

        .qr-image-wrapper:hover img {
            transform: scale(1.05);
        }

        .qr-actions {
            display: flex;
            gap: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-section {
            background: white;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            padding: 20px;
            transition: box-shadow 0.2s ease;
        }

        .info-section:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .info-section-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 500;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(245, 158, 11, .1);
            color: var(--warn);
        }

        .status-preparing {
            background: rgba(37, 99, 235, .1);
            color: var(--brand);
        }

        .status-shipped {
            background: rgba(6, 182, 212, .1);
            color: var(--brand-2);
        }

        .status-delivered {
            background: rgba(16, 185, 129, .1);
            color: var(--ok);
        }

        .status-canceled {
            background: rgba(239, 68, 68, .1);
            color: var(--danger);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(16, 185, 129, .1);
            color: var(--ok);
        }

        .badge-warning {
            background: rgba(245, 158, 11, .1);
            color: var(--warn);
        }

        .badge-danger {
            background: rgba(239, 68, 68, .1);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(37, 99, 235, .1);
            color: var(--brand);
        }

        .badge-secondary {
            background: rgba(0, 0, 0, .06);
            color: var(--muted);
        }

        .vertical-timeline-container {
            margin: 30px 0;
            padding: 24px;
            background: white;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
        }

        .timeline-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 24px;
            color: var(--text);
        }

        .vertical-timeline {
            position: relative;
            padding-left: 40px;
        }

        .vertical-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 32px;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        .timeline-item.active {
            opacity: 1;
        }

        .timeline-item.current .timeline-dot {
            background: var(--ok);
            border-color: var(--ok);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }

        .timeline-item.active .timeline-dot {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .timeline-item:not(.active) .timeline-dot {
            background: #e0e0e0;
            border-color: #e0e0e0;
            color: #999;
        }

        .timeline-dot {
            position: absolute;
            left: -32px;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e0e0;
            border: 3px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .timeline-dot i {
            font-size: 14px;
        }

        .timeline-content {
            padding-left: 20px;
        }

        .timeline-date {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
            font-weight: 500;
        }

        .timeline-item.active .timeline-date {
            color: var(--text);
        }

        .timeline-text {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.5;
        }

        .timeline-item.active .timeline-text {
            color: var(--text);
            font-weight: 500;
        }

        .timeline-item.current .timeline-text {
            color: var(--ok);
            font-weight: 600;
        }

        .actions-section {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(0, 0, 0, .06);
            flex-wrap: wrap;
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
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .15);
        }

        .btn-secondary {
            background: rgba(0, 0, 0, .06);
            color: var(--text);
        }

        .btn-secondary:hover {
            background: rgba(0, 0, 0, .12);
        }

        .btn-success {
            background: var(--ok);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: var(--text);
            background-color: white;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 8px;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: transparent;
            box-shadow: var(--ring);
        }

        .form-label {
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 13px;
            color: var(--text);
        }

        .alert {
            padding: 16px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 10px;
            background: rgba(239, 68, 68, .1);
            border-color: rgba(239, 68, 68, .2);
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .empty-state h3 {
            margin-bottom: 8px;
            color: var(--text);
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .tracking-header {
                flex-direction: column;
            }

            .qr-section {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include 'pages/header.php'; ?>

    <div class="page-header">
        <div class="container-ex">
            <h1><?php __e('tracking_title'); ?></h1>
        </div>
    </div>

    <main style="display: flex; justify-content: center; align-items: flex-start; padding: 40px 0;">
        <div class="container-ex" style="width: 100%; max-width: 1200px;">
            <div style="margin-bottom: 20px;">
                <a href="orders.php" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i> <?php __e('tracking_back_orders'); ?>
                </a>
            </div>

            <?php
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
            
            if ($order_id > 0) {
                // Show single order
                $getall = getAllTrackingByCUS($_SESSION['customer_id']);
                $order_found = false;
                
            while ($row = mysqli_fetch_assoc($getall)) {
                    if ($row['request_id'] == $order_id) {
                        $order_found = true;
                $request_id = $row['request_id'];
                        $payment_method = $row['payment_method'] ?? 'cod';
                        $payment_status = $row['payment_status'] ?? 'pending';
                        $amount = floatval($row['total_fee']);
                        $is_canceled = (intval($row['tracking_status']) == 12);

                        // Get status text and class
                        $status_text = __t('tracking_status_pending');
                        $status_class = 'status-pending';
                        if ($row['tracking_status'] == 2) {
                            $status_text = __t('tracking_status_preparing');
                            $status_class = 'status-preparing';
                                    } else if ($row['tracking_status'] == 3) {
                            $status_text = __t('tracking_status_shipped');
                            $status_class = 'status-shipped';
                                    } else if ($row['tracking_status'] == 4) {
                            $status_text = __t('tracking_status_delivered');
                            $status_class = 'status-delivered';
                                    } else if ($row['tracking_status'] == 5) {
                            $status_text = __t('tracking_status_canceled');
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
                        <div class="tracking-card" style="margin: 0 auto;">
                            <div class="tracking-header">
                                <div class="tracking-title">
                                    <div class="tracking-code-text" style="font-size: 18px; font-weight: 700; color: var(--brand); margin-bottom: 4px;">
                                        <?php __e('orders_tracking'); ?> <?php echo htmlspecialchars($row['tracking_code']); ?>
                                    </div>
                                    <div style="margin-top: 8px;">
                                        <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($row['qr_code_path'])): ?>
                                    <div class="qr-section">
                                        <div class="qr-image-wrapper">
                                            <a href="<?php echo htmlspecialchars($row['qr_code_path']); ?>" download="QR_Code_<?php echo htmlspecialchars($row['tracking_code']); ?>.png" title="<?php __e('tracking_download'); ?>">
                                                <img src="<?php echo htmlspecialchars($row['qr_code_path']); ?>" alt="QR Code">
                                            </a>
                                </div>
                                        <div class="qr-actions">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="downloadQRCode('<?php echo htmlspecialchars($row['qr_code_path']); ?>', '<?php echo htmlspecialchars($row['tracking_code']); ?>')">
                                                <i class="bi bi-download"></i> <?php __e('tracking_download'); ?>
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="printQRCode('<?php echo htmlspecialchars($row['qr_code_path']); ?>', '<?php echo htmlspecialchars($row['tracking_code']); ?>', '<?php echo $request_id; ?>')">
                                                <i class="bi bi-printer"></i> <?php __e('tracking_print'); ?>
                                            </button>
                                </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="info-grid">
                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_receiver_name'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['res_name']); ?></div>
                            </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_shipping_address'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['red_address']); ?></div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_receiver_mobile'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['res_phone']); ?></div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_sender_mobile'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($row['sender_phone']); ?></div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_weight'); ?></div>
                                    <div class="info-value"><?php echo $row['weight']; ?> kg</div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_send_location'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($send_location); ?></div>
                            </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_end_location'); ?></div>
                                    <div class="info-value"><?php echo htmlspecialchars($end_location); ?></div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_total_amount'); ?></div>
                                    <div class="info-value" style="font-size: 18px; color: var(--brand);">SAR<?php echo number_format($amount, 2); ?></div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_payment_method'); ?></div>
                                    <div class="info-value">
                                        <?php if ($payment_method == 'paypal'): ?>
                                            <span class="badge badge-info">PayPal</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary"><?php __e('orders_payment_cod'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_payment_status'); ?></div>
                                    <div class="info-value">
                                        <?php if ($payment_status == 'paid'): ?>
                                            <span class="badge badge-success"><?php __e('orders_payment_paid'); ?></span>
                                        <?php elseif ($payment_status == 'pending'): ?>
                                            <span class="badge badge-warning"><?php __e('orders_payment_pending'); ?></span>
                                        <?php elseif ($payment_status == 'failed'): ?>
                                            <span class="badge badge-danger"><?php __e('orders_payment_failed'); ?></span>
                                            <?php if (!empty($row['payment_failure_reason'] ?? '')): ?>
                                            <div style="margin-top: 8px; padding: 10px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px;">
                                                <strong style="font-size: 12px; color: var(--danger); display: block; margin-bottom: 4px;"><?php __e('tracking_payment_failure_reason'); ?>:</strong>
                                                <div style="font-size: 13px; color: var(--text);"><?php echo nl2br(htmlspecialchars($row['payment_failure_reason'])); ?></div>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (!$is_canceled): ?>
                                            <div style="margin-top: 12px;">
                                                <a href="payment.php?request_id=<?php echo $request_id; ?>" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-weight: 600;">
                                                    <i class="bi bi-credit-card-2-front"></i> <?php __e('tracking_pay_again'); ?>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                        <?php elseif ($payment_status == 'pending' && $payment_method == 'paypal' && !$is_canceled): ?>
                                            <div style="margin-top: 12px;">
                                                <a href="payment.php?request_id=<?php echo $request_id; ?>" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-weight: 600;">
                                                    <i class="bi bi-credit-card"></i> <?php __e('tracking_pay_now'); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                </div>
                            </div>

                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_requested_date'); ?></div>
                                    <div class="info-value"><?php echo date('M d, Y H:i', strtotime($row['date_updated'])); ?></div>
                                </div>

                                <?php if (!empty($row['paypal_transaction_id'])): ?>
                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_transaction_id'); ?></div>
                                    <div class="info-value" style="font-size: 13px; font-family: monospace;"><?php echo htmlspecialchars($row['paypal_transaction_id']); ?></div>
                                </div>
                                <?php endif; ?>

                                <?php if (!empty($row['payment_date'])): ?>
                                <div class="info-section">
                                    <div class="info-section-title"><?php __e('tracking_payment_date'); ?></div>
                                    <div class="info-value"><?php echo date('M d, Y H:i', strtotime($row['payment_date'])); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            // Get status history with timestamps - only show statuses that were actually reached
                            $status_history = [];
                            $history_result = getStatusHistory($request_id);
                            while ($history_row = mysqli_fetch_assoc($history_result)) {
                                $status_history[$history_row['status']] = [
                                    'date' => $history_row['status_date'],
                                    'timestamp' => strtotime($history_row['status_date'])
                                ];
                            }
                            
                            // Set default date for status 1 if no history exists (order creation)
                            if (!isset($status_history[1])) {
                                $status_history[1] = [
                                    'date' => $row['date_updated'],
                                    'timestamp' => strtotime($row['date_updated'])
                                ];
                            }
                            
                            // Define default statuses with their details (matching Admin/order_detail.php)
                            $default_statuses_def = [
                                1 => ['icon' => 'fa-shopping-cart', 'text' => __t('tracking_status_placed')],
                                2 => ['icon' => 'fa-box', 'text' => __t('tracking_status_preparing')],
                                3 => ['icon' => 'fa-hand-holding-box', 'text' => __t('tracking_status_dropoff')],
                                4 => ['icon' => 'fa-truck-pickup', 'text' => __t('tracking_status_picked')],
                                5 => ['icon' => 'fa-warehouse', 'text' => __t('tracking_status_sorting')],
                                6 => ['icon' => 'fa-truck', 'text' => __t('tracking_status_departed')],
                                7 => ['icon' => 'fa-building', 'text' => __t('tracking_status_hub')],
                                8 => ['icon' => 'fa-truck-fast', 'text' => __t('tracking_status_out_delivery')],
                                9 => ['icon' => 'fa-exclamation-triangle', 'text' => __t('tracking_status_unsuccessful')],
                                10 => ['icon' => 'fa-store', 'text' => __t('tracking_status_collection')],
                                11 => ['icon' => 'fa-circle-check', 'text' => __t('tracking_status_delivered')],
                                12 => ['icon' => 'fa-times-circle', 'text' => __t('tracking_status_canceled')],
                            ];
                            
                            // Get custom statuses
                            $custom_statuses_map = [];
                            $custom_statuses_result = getAllCustomStatuses();
                            $custom_start_id = 100;
                            if ($custom_statuses_result && mysqli_num_rows($custom_statuses_result) > 0) {
                                while ($custom_row = mysqli_fetch_assoc($custom_statuses_result)) {
                                    $custom_id = $custom_start_id + intval($custom_row['status_id']);
                                    $current_lang = get_current_lang();
                                    $status_text = ($current_lang === 'ar') ? $custom_row['status_name_ar'] : $custom_row['status_name_en'];
                                    $custom_statuses_map[$custom_id] = [
                                        'icon' => $custom_row['status_icon'],
                                        'text' => $status_text
                                    ];
                                }
                            }
                            
                            // Combine default and custom statuses
                            $all_statuses_def = array_merge($default_statuses_def, $custom_statuses_map);
                            
                            // Only show statuses that have been reached (have timestamps)
                            // Sort by timestamp to show in chronological order
                            $reached_statuses = [];
                            foreach ($status_history as $status_id => $history_data) {
                                if (isset($all_statuses_def[$status_id])) {
                                    $reached_statuses[$status_id] = [
                                        'info' => $all_statuses_def[$status_id],
                                        'date' => $history_data['date'],
                                        'timestamp' => $history_data['timestamp']
                                    ];
                                } else {
                                    // Status not found in definitions - try to get it from database (might be a custom status)
                                    // Check if it's a custom status (ID >= 100)
                                    if (intval($status_id) >= 100) {
                                        $custom_db_id = intval($status_id) - 100;
                                        $custom_status = getCustomStatusById($custom_db_id);
                                        if ($custom_status) {
                                            $current_lang = get_current_lang();
                                            $status_text = ($current_lang === 'ar') ? $custom_status['status_name_ar'] : $custom_status['status_name_en'];
                                            $reached_statuses[$status_id] = [
                                                'info' => [
                                                    'icon' => $custom_status['status_icon'],
                                                    'text' => $status_text
                                                ],
                                                'date' => $history_data['date'],
                                                'timestamp' => $history_data['timestamp']
                                            ];
                                        } else {
                                            // Custom status was deleted
                                            $reached_statuses[$status_id] = [
                                                'info' => ['icon' => 'fa-circle', 'text' => 'Status ' . $status_id],
                                                'date' => $history_data['date'],
                                                'timestamp' => $history_data['timestamp']
                                            ];
                                        }
                                    } else {
                                        // Default status not found - should not happen, but handle gracefully
                                        $reached_statuses[$status_id] = [
                                            'info' => ['icon' => 'fa-circle', 'text' => 'Status ' . $status_id],
                                            'date' => $history_data['date'],
                                            'timestamp' => $history_data['timestamp']
                                        ];
                                    }
                                }
                            }
                            
                            // Sort by timestamp (chronological order)
                            uasort($reached_statuses, function($a, $b) {
                                return $a['timestamp'] - $b['timestamp'];
                            });
                            
                            $current_status = intval($row['tracking_status']);
                            
                            if ($row['tracking_status'] != 5 && $row['tracking_status'] != 12) {
                            ?>
                                <div class="vertical-timeline-container">
                                    <h5 class="timeline-title"><?php __e('orders_tracking_progress'); ?></h5>
                                    <div class="vertical-timeline">
                                        <?php
                                        $status_count = 0;
                                        $total_statuses = count($reached_statuses);
                                        
                                        foreach ($reached_statuses as $status_id => $status_data) {
                                            $status_count++;
                                            $is_current = ($current_status == $status_id);
                                            $is_last = ($status_count == $total_statuses);
                                            
                                            $status_date = date('d M H:i', $status_data['timestamp']);
                                            ?>
                                            <div class="timeline-item active <?php echo $is_current ? 'current' : ''; ?> <?php echo $is_last ? 'last' : ''; ?>">
                                                <div class="timeline-dot">
                                                    <i class="fa <?php echo $status_data['info']['icon']; ?>"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="timeline-date"><?php echo $status_date; ?></div>
                                                    <div class="timeline-text"><?php echo $status_data['info']['text']; ?></div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        
                                        // If no statuses reached yet, show a message
                                        if (empty($reached_statuses)) {
                                            ?>
                                            <div class="timeline-item">
                                                <div class="timeline-dot">
                                                    <i class="fa fa-info-circle"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="timeline-text"><?php __e('admin_no_status_history'); ?></div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                        <?php } ?>

                            <?php
                            // Check for cancellation request
                            $cancellation_request = null;
                            $cancellation_result = getCancellationRequestByRequestId($request_id);
                            if ($cancellation_result && mysqli_num_rows($cancellation_result) > 0) {
                                $cancellation_request = mysqli_fetch_assoc($cancellation_result);
                            }
                            ?>
                            
                            <?php if ($is_canceled): ?>
                            <!-- Order is Canceled - Locked State -->
                            <div style="background: rgba(239, 68, 68, 0.1); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 24px; text-align: center; margin-top: 24px;">
                                <div style="font-size: 48px; color: var(--danger); margin-bottom: 16px;">
                                    <i class="bi bi-lock-fill"></i>
                                </div>
                                <h4 style="margin: 0 0 8px; color: var(--danger); font-weight: 700;">
                                    <?php __e('order_canceled_locked'); ?>
                                </h4>
                                <p style="margin: 0; color: var(--muted); font-size: 14px;">
                                    <?php __e('order_canceled_locked_desc'); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="actions-section">
                                <?php if (!$is_canceled && $row['tracking_status'] != "11"): ?>
                                    <?php if (!$cancellation_request || $cancellation_request['cancellation_status'] == 'rejected'): ?>
                                        <!-- Show cancellation request button if no pending request or if previous was rejected -->
                                        <div style="flex: 1; min-width: 200px;">
                                            <button type="button" class="btn" style="background: var(--danger); color: white;" onclick="showCancellationForm(<?php echo $request_id; ?>)">
                                                <i class="bi bi-x-circle"></i> <?php __e('cancellation_request_cancel'); ?>
                                            </button>
                                        </div>
                                    <?php elseif ($cancellation_request['cancellation_status'] == 'pending'): ?>
                                        <!-- Show pending cancellation status -->
                                        <div style="flex: 1; min-width: 200px; padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px;">
                                            <div style="font-size: 13px; font-weight: 600; color: var(--warn); margin-bottom: 4px;">
                                                <i class="bi bi-clock-history"></i> <?php __e('cancellation_request_pending'); ?>
                                            </div>
                                            <div style="font-size: 12px; color: var(--muted);">
                                                <?php __e('cancellation_request_waiting'); ?>
                                            </div>
                                        </div>
                                    <?php elseif ($cancellation_request['cancellation_status'] == 'approved'): ?>
                                        <!-- Show approved cancellation -->
                                        <div style="flex: 1; min-width: 200px; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px;">
                                            <div style="font-size: 13px; font-weight: 600; color: var(--danger);">
                                                <i class="bi bi-check-circle"></i> <?php __e('cancellation_request_approved'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (!$is_canceled && $payment_method == 'paypal' && ($payment_status == 'pending' || $payment_status == 'failed')): ?>
                                    <div style="display: flex; align-items: flex-end;">
                                        <a href="payment.php?request_id=<?php echo $request_id; ?>" class="btn btn-success">
                                            <i class="bi bi-credit-card"></i> <?php __e('tracking_pay_now'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Cancellation Request Form Modal -->
                            <div id="cancellationModal_<?php echo $request_id; ?>" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 10000; align-items: center; justify-content: center;">
                                <div style="background: white; padding: 24px; border-radius: 12px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                        <h3 style="margin: 0;"><?php __e('cancellation_request_title'); ?></h3>
                                        <button type="button" onclick="hideCancellationForm(<?php echo $request_id; ?>)" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--muted);">&times;</button>
                                    </div>
                                    <form id="cancellationForm_<?php echo $request_id; ?>" onsubmit="submitCancellationRequest(event, <?php echo $request_id; ?>)">
                                        <div style="margin-bottom: 20px;">
                                            <label class="form-label" style="display: block; font-weight: 600; margin-bottom: 8px;">
                                                <?php __e('cancellation_request_reason'); ?> <span style="color: var(--danger);">*</span>
                                            </label>
                                            <textarea name="cancellation_reason" id="cancellation_reason_<?php echo $request_id; ?>" 
                                                      class="form-control" rows="5" 
                                                      placeholder="<?php __e('cancellation_request_reason_placeholder'); ?>" 
                                                      required style="resize: vertical; width: 100%; padding: 12px; border: 2px solid rgba(0,0,0,.12); border-radius: 8px; font-family: inherit; font-size: 14px;"></textarea>
                                        </div>
                                        <div style="display: flex; gap: 12px;">
                                            <button type="submit" class="btn" style="flex: 1; background: var(--danger); color: white;">
                                                <i class="bi bi-send"></i> <?php __e('cancellation_request_submit'); ?>
                                            </button>
                                            <button type="button" onclick="hideCancellationForm(<?php echo $request_id; ?>)" class="btn btn-secondary" style="flex: 1;">
                                                <?php __e('cancel'); ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <?php if ($cancellation_request): ?>
                            <!-- Show cancellation request details -->
                            <div style="margin-top: 24px; padding: 16px; background: var(--panel); border: 1px solid rgba(0,0,0,.08); border-radius: 12px;">
                                <h4 style="margin: 0 0 12px; font-size: 16px; font-weight: 600;"><?php __e('cancellation_request_details'); ?></h4>
                                <div style="display: grid; gap: 8px; font-size: 14px;">
                                    <div>
                                        <strong><?php __e('cancellation_request_reason'); ?>:</strong>
                                        <div style="margin-top: 4px; color: var(--muted);"><?php echo nl2br(htmlspecialchars($cancellation_request['cancellation_reason'])); ?></div>
                                    </div>
                                    <div>
                                        <strong><?php __e('cancellation_request_status'); ?>:</strong>
                                        <span style="padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-left: 8px;
                                            <?php 
                                            if ($cancellation_request['cancellation_status'] == 'pending') {
                                                echo 'background: rgba(245, 158, 11, 0.1); color: var(--warn);';
                                            } elseif ($cancellation_request['cancellation_status'] == 'approved') {
                                                echo 'background: rgba(239, 68, 68, 0.1); color: var(--danger);';
                                            } else {
                                                echo 'background: rgba(0, 0, 0, 0.06); color: var(--muted);';
                                            }
                                            ?>">
                                            <?php 
                                            if ($cancellation_request['cancellation_status'] == 'pending') {
                                                __e('cancellation_request_pending');
                                            } elseif ($cancellation_request['cancellation_status'] == 'approved') {
                                                __e('cancellation_request_approved');
                                            } else {
                                                __e('cancellation_request_rejected');
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div>
                                        <strong><?php __e('cancellation_request_date'); ?>:</strong>
                                        <span style="color: var(--muted);"><?php echo date('M d, Y H:i', strtotime($cancellation_request['requested_date'])); ?></span>
                                    </div>
                                    <?php if ($cancellation_request['admin_response_comment']): ?>
                                    <div>
                                        <strong><?php __e('cancellation_request_admin_response'); ?>:</strong>
                                        <div style="margin-top: 4px; color: var(--muted);"><?php echo nl2br(htmlspecialchars($cancellation_request['admin_response_comment'])); ?></div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Show refund section if cancellation is approved and payment was made
                                    if ($cancellation_request['cancellation_status'] == 'approved' && 
                                        $payment_method == 'paypal' && 
                                        $payment_status == 'paid'): 
                                    ?>
                                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,.08);">
                                        <h5 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: var(--text);">
                                            <i class="bi bi-arrow-counterclockwise"></i> <?php __e('refund_title'); ?>
                                        </h5>
                                        
                                        <?php if ($cancellation_request['refund_status'] == 'completed'): ?>
                                            <!-- Refund completed -->
                                            <div style="padding: 12px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px;">
                                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                                    <i class="bi bi-check-circle-fill" style="color: var(--ok); font-size: 18px;"></i>
                                                    <strong style="color: var(--ok);"><?php __e('refund_completed'); ?></strong>
                                                </div>
                                                <?php if ($cancellation_request['refund_date']): ?>
                                                <div style="font-size: 12px; color: var(--muted);">
                                                    <?php __e('refund_date'); ?>: <?php echo date('M d, Y H:i', strtotime($cancellation_request['refund_date'])); ?>
                                                </div>
                                                <?php endif; ?>
                                                <?php if ($cancellation_request['refund_transaction_id']): ?>
                                                <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                                                    <?php __e('refund_transaction_id'); ?>: <?php echo htmlspecialchars($cancellation_request['refund_transaction_id']); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php elseif ($cancellation_request['refund_status'] == 'pending'): ?>
                                            <!-- Refund pending - show PayPal account form -->
                                            <?php if (empty($cancellation_request['customer_paypal_account'])): ?>
                                                <div style="padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; margin-bottom: 12px;">
                                                    <p style="margin: 0 0 8px; font-size: 13px; color: var(--warn);">
                                                        <?php __e('refund_paypal_required'); ?>
                                                    </p>
                                                    <form id="paypalAccountForm_<?php echo $cancellation_request['cancellation_id']; ?>" onsubmit="submitPayPalAccount(event, <?php echo $cancellation_request['cancellation_id']; ?>)">
                                                        <div style="display: flex; gap: 8px;">
                                                            <input type="email" 
                                                                   id="paypal_account_<?php echo $cancellation_request['cancellation_id']; ?>"
                                                                   placeholder="<?php __e('refund_paypal_placeholder'); ?>" 
                                                                   required
                                                                   style="flex: 1; padding: 10px; border: 2px solid rgba(0,0,0,.12); border-radius: 8px; font-size: 14px;">
                                                            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; white-space: nowrap;">
                                                                <i class="bi bi-send"></i> <?php __e('refund_submit_paypal'); ?>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <!-- PayPal account provided, waiting for refund -->
                                                <div style="padding: 12px; background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 8px;">
                                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                                        <i class="bi bi-clock-history" style="color: var(--brand); font-size: 18px;"></i>
                                                        <strong style="color: var(--brand);"><?php __e('refund_pending'); ?></strong>
                                                    </div>
                                                    <div style="font-size: 13px; color: var(--muted);">
                                                        <?php __e('refund_paypal_provided'); ?>: <strong><?php echo htmlspecialchars($cancellation_request['customer_paypal_account']); ?></strong>
                                                    </div>
                                                    <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">
                                                        <?php __e('refund_waiting_admin'); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if ($cancellation_request['refund_amount']): ?>
                                        <div style="margin-top: 8px; font-size: 13px; color: var(--muted);">
                                            <strong><?php __e('refund_amount'); ?>:</strong> SAR<?php echo number_format(floatval($cancellation_request['refund_amount']), 2); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Rating Section -->
                            <?php
                            $current_rating = isset($row['rating']) ? intval($row['rating']) : null;
                            $rating_comment = isset($row['rating_comment']) ? htmlspecialchars($row['rating_comment']) : '';
                            $rating_date = isset($row['rating_date']) ? $row['rating_date'] : null;
                            // Allow rating for all orders, not just delivered ones
                            ?>
                            <div id="rating" class="rating-section" style="margin-top: 30px; padding-top: 30px; border-top: 1px solid rgba(0,0,0,.08); scroll-margin-top: 20px;">
                                <h3 style="margin: 0 0 16px; font-size: 18px; font-weight: 600;"><?php __e('rating_title'); ?></h3>
                                <p style="margin: 0 0 20px; color: var(--muted); font-size: 14px;"><?php __e('rating_subtitle'); ?></p>
                                
                                <?php if ($current_rating): ?>
                                    <!-- Show existing rating -->
                                    <div class="rating-display" style="background: var(--panel); padding: 20px; border-radius: 12px; border: 1px solid rgba(0,0,0,.08);">
                                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                            <div class="rating-stars-display" style="display: flex; gap: 4px;">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <span style="font-size: 24px; color: <?php echo $i <= $current_rating ? '#fbbf24' : '#d1d5db'; ?>;">★</span>
                                                <?php endfor; ?>
                                            </div>
                                            <span style="font-weight: 600; color: var(--text);"><?php echo $current_rating; ?>/5 <?php __e('rating_stars'); ?></span>
                                        </div>
                                        <?php if ($rating_comment): ?>
                                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,.08);">
                                                <div style="font-size: 13px; color: var(--muted); margin-bottom: 4px;"><?php __e('rating_comment'); ?>:</div>
                                                <div style="color: var(--text);"><?php echo $rating_comment; ?></div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($rating_date): ?>
                                            <div style="margin-top: 8px; font-size: 12px; color: var(--muted);">
                                                <?php __e('rating_date'); ?>: <?php echo date('M d, Y H:i', strtotime($rating_date)); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Show rating form for all orders -->
                                    <div class="rating-form-container" id="ratingFormContainer" style="background: white; padding: 24px; border-radius: 12px; border: 2px solid rgba(37, 99, 235, 0.2); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                        <div style="margin-bottom: 20px; text-align: center;">
                                            <div style="font-size: 32px; margin-bottom: 8px;">⭐</div>
                                            <div style="font-weight: 600; color: var(--text); font-size: 16px; margin-bottom: 4px;">
                                                <?php __e('rating_subtitle'); ?>
                                            </div>
                                            <div style="color: var(--muted); font-size: 13px;">
                                                <?php __e('rating_comment_placeholder'); ?>
                                            </div>
                                        </div>
                                        
                                        <form id="ratingForm">
                                            <div style="margin-bottom: 24px;">
                                                <label class="form-label" style="display: block; font-weight: 600; margin-bottom: 12px; text-align: center;">
                                                    <?php __e('rating_required'); ?> <span style="color: var(--danger);">*</span>
                                                </label>
                                                <div class="rating-input" style="display: flex; gap: 12px; justify-content: center; margin-top: 12px; flex-wrap: wrap;">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <button type="button" class="rating-star-btn" data-rating="<?php echo $i; ?>" style="background: none; border: none; font-size: 48px; color: #d1d5db; cursor: pointer; padding: 4px; transition: all 0.2s; line-height: 1;" onmouseover="highlightStars(<?php echo $i; ?>)" onmouseout="resetStars()" onclick="selectRating(<?php echo $i; ?>)" title="<?php echo $i; ?> <?php echo $i == 1 ? 'star' : 'stars'; ?>">
                                                            ★
                                                        </button>
                                                    <?php endfor; ?>
                                                </div>
                                                <input type="hidden" name="rating" id="ratingValue" required>
                                                <div id="ratingError" style="color: var(--danger); font-size: 12px; margin-top: 12px; text-align: center; display: none;"></div>
                                                <div id="ratingSelected" style="text-align: center; margin-top: 8px; font-size: 14px; color: var(--muted); display: none;">
                                                    <span id="selectedRatingText"></span>
                                                </div>
                                            </div>
                                            
                                            <div style="margin-bottom: 24px;">
                                                <label for="rating_comment" class="form-label" style="display: block; font-weight: 600; margin-bottom: 8px;">
                                                    <i class="bi bi-chat-left-text"></i> <?php __e('rating_comment_label'); ?>
                                                </label>
                                                <textarea name="rating_comment" id="rating_comment" class="form-control" rows="5" placeholder="<?php __e('rating_comment_placeholder'); ?>" style="resize: vertical; width: 100%; padding: 12px; border: 2px solid rgba(0,0,0,.12); border-radius: 8px; font-family: inherit; font-size: 14px; transition: border-color 0.2s;"></textarea>
                                                <div style="font-size: 12px; color: var(--muted); margin-top: 6px;">
                                                    <?php __e('rating_comment_placeholder'); ?>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; font-weight: 600; background: linear-gradient(135deg, var(--brand), var(--brand-2)); border: none; border-radius: 10px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                                <i class="bi bi-star-fill"></i> <?php __e('rating_submit'); ?>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        break;
                    }
                }
                
                if (!$order_found) {
                    echo '<div class="alert">' . __t('tracking_order_not_found') . '</div>';
                }
            } else {
                // Show all orders (fallback)
                $getall = getAllTrackingByCUS($_SESSION['customer_id']);
                $has_orders = false;
                while ($row = mysqli_fetch_assoc($getall)) {
                    $has_orders = true;
                    $request_id = $row['request_id'];
                    ?>
                    <div class="tracking-card">
                        <div class="tracking-header">
                            <div class="tracking-title">
                                <div class="tracking-code-text" style="font-size: 18px; font-weight: 700; color: var(--brand);">
                                    <?php __e('orders_tracking'); ?> <?php echo htmlspecialchars($row['tracking_code']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="actions-section" style="border-top: none; padding-top: 0; margin-top: 0;">
                            <a href="tracking.php?order_id=<?php echo $request_id; ?>" class="btn btn-primary">
                                <i class="bi bi-eye"></i> <?php __e('tracking_view_details'); ?>
                            </a>
                        </div>
                    </div>
                    <?php
                }
                if (!$has_orders) {
                    echo '<div class="alert">' . __t('tracking_order_not_found') . '</div>';
                }
            }
            ?>
        </div>
    </main>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Auto-scroll to rating section if URL has #rating anchor
        window.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#rating') {
                setTimeout(function() {
                    const ratingSection = document.getElementById('rating');
                    if (ratingSection) {
                        ratingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        // Highlight the section briefly
                        ratingSection.style.transition = 'background-color 0.3s';
                        ratingSection.style.backgroundColor = 'rgba(251, 191, 36, 0.1)';
                        setTimeout(function() {
                            ratingSection.style.backgroundColor = '';
                        }, 2000);
                    }
                }, 300);
            }
        });
        
        function downloadQRCode(qrPath, trackingCode) {
            const link = document.createElement('a');
            link.href = qrPath;
            link.download = 'QR_Code_' + trackingCode + '.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        function printQRCode(qrPath, trackingCode, orderId) {
            const printWindow = window.open('', '_blank', 'width=600,height=800');
            const qrImageUrl = qrPath;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QR Code - ${trackingCode}</title>
                    <style>
                        @media print {
                            body { margin: 0; padding: 20px; }
                            .no-print { display: none; }
                        }
                        body {
                            font-family: Arial, sans-serif;
                            text-align: center;
                            padding: 40px 20px;
                        }
                        .qr-container {
                            margin: 20px auto;
                            padding: 20px;
                            border: 2px solid #000;
                            display: inline-block;
                        }
                        .qr-code {
                            max-width: 400px;
            width: 100%;
                            height: auto;
                            margin: 20px 0;
                        }
                        @media print {
                            .qr-code {
                                max-width: 500px;
                            }
                        }
                        .info {
                            margin-top: 20px;
            font-size: 14px;
                        }
                        .tracking-code {
            font-size: 18px;
                            font-weight: bold;
                            margin: 10px 0;
                        }
                        .order-id {
            font-size: 14px;
                            color: #666;
                        }
                        button {
                            margin: 20px 10px;
                            padding: 10px 20px;
                            font-size: 16px;
                            cursor: pointer;
                            background: #2563eb;
                            color: white;
                            border: none;
                            border-radius: 8px;
        }
    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <div class="tracking-code">Tracking Code: ${trackingCode}</div>
                        <div class="order-id">Order ID: #${orderId}</div>
                        <img src="${qrImageUrl}" alt="QR Code" class="qr-code" onload="setTimeout(() => window.print(), 500)">
                        <div class="info">
                            <p>Scan this QR code to view order details</p>
                            <p style="font-size: 12px; color: #666;">FOJ Express</p>
                        </div>
                    </div>
                    <div class="no-print">
                        <button onclick="window.print()">Print</button>
                        <button onclick="window.close()">Close</button>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        async function updateDataFromHome(element, id, field, table, id_field) {
            const value = element.value;
            const data = {
                id: id,
                field: field,
                value: value,
                id_fild: id_field,
                table: table
            };

            try {
                const response = await $.ajax({
                    method: "POST",
                    url: "server/api.php?function_code=updateData",
                    data: data
                });
                if (response) {
                    location.reload();
                } else {
                    alert("<?php __e('profile_update_error'); ?>");
                }
            } catch (error) {
                console.error(`Error updating data: ${error}`);
                alert("<?php __e('profile_update_error'); ?>");
            }
        }

        // Cancellation request functionality
        function showCancellationForm(request_id) {
            const modal = document.getElementById('cancellationModal_' + request_id);
            if (modal) {
                modal.style.display = 'flex';
            }
        }
        
        function hideCancellationForm(request_id) {
            const modal = document.getElementById('cancellationModal_' + request_id);
            if (modal) {
                modal.style.display = 'none';
                // Clear form
                const form = document.getElementById('cancellationForm_' + request_id);
                if (form) {
                    form.reset();
                }
            }
        }
        
        async function submitCancellationRequest(event, request_id) {
            event.preventDefault();
            
            const reason = document.getElementById('cancellation_reason_' + request_id).value.trim();
            
            if (!reason) {
                alert('<?php __e('cancellation_request_reason_required'); ?>');
                return;
            }
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php __e('admin_saving'); ?>...';
            
            try {
                const formData = new FormData();
                formData.append('request_id', request_id);
                formData.append('cancellation_reason', reason);
                
                const response = await fetch('server/api.php?function_code=requestCancellation', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('<?php __e('cancellation_request_submitted'); ?>');
                    location.reload();
                } else {
                    alert(result.error || '<?php __e('cancellation_request_error'); ?>');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error submitting cancellation request:', error);
                alert('<?php __e('cancellation_request_error'); ?>');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
        
        // PayPal account submission for refund
        async function submitPayPalAccount(event, cancellation_id) {
            event.preventDefault();
            
            const paypalAccount = document.getElementById('paypal_account_' + cancellation_id).value.trim();
            
            if (!paypalAccount) {
                alert('<?php __e('refund_paypal_required'); ?>');
                return;
            }
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(paypalAccount)) {
                alert('<?php __e('refund_paypal_invalid'); ?>');
                return;
            }
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php __e('admin_saving'); ?>...';
            
            try {
                const formData = new FormData();
                formData.append('cancellation_id', cancellation_id);
                formData.append('paypal_account', paypalAccount);
                
                const response = await fetch('server/api.php?function_code=updateCustomerPayPalAccount', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('<?php __e('refund_paypal_submitted'); ?>');
                    location.reload();
                } else {
                    alert(result.error || '<?php __e('refund_paypal_error'); ?>');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error submitting PayPal account:', error);
                alert('<?php __e('refund_paypal_error'); ?>');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
        
        // Rating functionality
        let selectedRating = 0;
        const starButtons = document.querySelectorAll('.rating-star-btn');
        
        function highlightStars(rating) {
            starButtons.forEach((btn, index) => {
                if (index < rating) {
                    btn.style.color = '#fbbf24';
                } else {
                    btn.style.color = '#d1d5db';
                }
            });
        }
        
        function resetStars() {
            starButtons.forEach((btn, index) => {
                if (index < selectedRating) {
                    btn.style.color = '#fbbf24';
                } else {
                    btn.style.color = '#d1d5db';
                }
            });
        }
        
        function selectRating(rating) {
            selectedRating = rating;
            document.getElementById('ratingValue').value = rating;
            starButtons.forEach((btn, index) => {
                if (index < rating) {
                    btn.style.color = '#fbbf24';
                    btn.style.transform = 'scale(1.1)';
                } else {
                    btn.style.color = '#d1d5db';
                    btn.style.transform = 'scale(1)';
                }
            });
            document.getElementById('ratingError').style.display = 'none';
            
            // Show selected rating text
            const ratingTexts = {
                1: 'Poor',
                2: 'Fair',
                3: 'Good',
                4: 'Very Good',
                5: 'Excellent'
            };
            const selectedText = document.getElementById('selectedRatingText');
            const ratingSelected = document.getElementById('ratingSelected');
            if (selectedText && ratingSelected) {
                selectedText.textContent = rating + '/5 - ' + ratingTexts[rating];
                ratingSelected.style.display = 'block';
            }
        }
        
        
        // Handle rating form submission
        const ratingForm = document.getElementById('ratingForm');
        if (ratingForm) {
            ratingForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const rating = document.getElementById('ratingValue').value;
                const comment = document.getElementById('rating_comment').value;
                const requestId = <?php echo isset($request_id) ? $request_id : 0; ?>;
                
                if (!rating || rating < 1 || rating > 5) {
                    document.getElementById('ratingError').textContent = '<?php __e('rating_required'); ?>';
                    document.getElementById('ratingError').style.display = 'block';
                    return;
                }
                
                const submitBtn = ratingForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php __e('admin_saving'); ?>...';
                
                try {
                    const formData = new FormData();
                    formData.append('request_id', requestId);
                    formData.append('rating', rating);
                    if (comment) {
                        formData.append('rating_comment', comment);
                    }
                    
                    const response = await fetch('server/api.php?function_code=saveOrderRating', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert('<?php __e('rating_saved'); ?>');
                        location.reload();
                    } else {
                        alert(result.error || '<?php __e('rating_error'); ?>');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Error saving rating:', error);
                    alert('<?php __e('rating_error'); ?>');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    </script>
</body>

</html>
