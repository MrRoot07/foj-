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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        .tracking-card {
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
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
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 10px;
            padding: 16px;
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
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
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
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
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

    <main>
        <div class="container-ex">
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
                        <div class="tracking-card">
                            <div class="tracking-header">
                                <div class="tracking-title">
                                    <div class="tracking-id"><?php __e('tracking_id'); ?> #<?php echo $row['request_id']; ?></div>
                                    <div class="tracking-code-text">
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
                                    <div class="info-value" style="font-size: 18px; color: var(--brand);">$<?php echo number_format($amount, 2); ?></div>
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
                            // Get all statuses and determine which are active
                            $current_status = $row['tracking_status'];
                            $statuses = [
                                1 => ['icon' => 'fa-shopping-cart', 'text' => __t('tracking_status_placed'), 'date' => date('d M H:i', strtotime($row['date_updated']))],
                                2 => ['icon' => 'fa-box', 'text' => __t('tracking_status_preparing'), 'date' => ''],
                                3 => ['icon' => 'fa-hand-holding-box', 'text' => __t('tracking_status_dropoff'), 'date' => ''],
                                4 => ['icon' => 'fa-truck-pickup', 'text' => __t('tracking_status_picked'), 'date' => ''],
                                5 => ['icon' => 'fa-warehouse', 'text' => __t('tracking_status_sorting_arrived'), 'date' => ''],
                                6 => ['icon' => 'fa-truck', 'text' => __t('tracking_status_sorting_departed'), 'date' => ''],
                                7 => ['icon' => 'fa-building', 'text' => __t('tracking_status_hub_arrived'), 'date' => ''],
                                8 => ['icon' => 'fa-truck-fast', 'text' => __t('tracking_status_out_delivery'), 'date' => ''],
                                9 => ['icon' => 'fa-exclamation-triangle', 'text' => __t('tracking_status_delivery_failed'), 'date' => ''],
                                10 => ['icon' => 'fa-store', 'text' => __t('tracking_status_ready_collection'), 'date' => ''],
                                11 => ['icon' => 'fa-circle-check', 'text' => __t('tracking_status_delivered'), 'date' => ''],
                            ];
                            
                            // Map old statuses to new ones for backward compatibility
                            $status_mapping = [
                                1 => 1,  // Pending -> Order is placed
                                2 => 2,  // Prepare Order -> Seller is preparing
                                3 => 6,  // Shipped -> Departed from sorting
                                4 => 11, // Delivered -> Parcel has been delivered
                            ];
                            
                            if (isset($status_mapping[$current_status])) {
                                $current_status = $status_mapping[$current_status];
                            }
                            
                            if ($row['tracking_status'] != 5 && $row['tracking_status'] != 12) {
                            ?>
                                <div class="vertical-timeline-container">
                                    <h5 class="timeline-title"><?php __e('orders_tracking_progress'); ?></h5>
                                    <div class="vertical-timeline">
                                        <?php
                                        foreach ($statuses as $status_id => $status_info) {
                                            $is_active = $current_status >= $status_id;
                                            $is_current = $current_status == $status_id;
                                            ?>
                                            <div class="timeline-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $is_current ? 'current' : ''; ?>">
                                                <div class="timeline-dot">
                                                    <i class="fa <?php echo $status_info['icon']; ?>"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="timeline-date"><?php echo $status_info['date'] ?: date('d M H:i'); ?></div>
                                                    <div class="timeline-text"><?php echo $status_info['text']; ?></div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                        <?php } ?>

                            <div class="actions-section">
                                <?php if ($row['tracking_status'] == "1" || $row['tracking_status'] == "12") { ?>
                                    <div style="flex: 1; min-width: 200px;">
                                        <label for="tracking_status" class="form-label"><?php __e('tracking_order_cancel'); ?></label>
                                    <select
                                        onchange='updateDataFromHome(this, "<?php echo $request_id; ?>","tracking_status", "request", "request_id")'
                                            id="tracking_status <?php echo $request_id; ?>" 
                                            class='form-control'
                                            name="tracking_status">
                                            <option value="1"><?php __e('tracking_select_status'); ?></option>
                                            <option value="12" <?php if ($row['tracking_status'] == "12" || $row['tracking_status'] == "5") echo "selected"; ?>><?php __e('tracking_status_canceled'); ?></option>
                                    </select>
                                </div>
                            <?php } ?>
                                
                                <?php if ($payment_method == 'paypal' && $payment_status == 'pending'): ?>
                                    <div style="display: flex; align-items: flex-end;">
                                        <a href="payment.php?request_id=<?php echo $request_id; ?>" class="btn btn-success">
                                            <i class="bi bi-credit-card"></i> <?php __e('tracking_pay_now'); ?>
                                        </a>
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
                                <div class="tracking-id"><?php __e('tracking_id'); ?> #<?php echo $row['request_id']; ?></div>
                                <div class="tracking-code-text">
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

    <?php include 'pages/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
    </script>
</body>

</html>
