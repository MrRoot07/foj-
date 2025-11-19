<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/../bootstrap/i18n.php';
$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<?php include 'pages/head.php'; ?>
<?php include 'admin.php'; ?>

<body>
    <div id="app">
        <?php include 'pages/sidebar.php'; ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3><?php __e('admin_payments'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_payments'); ?></p>
            </div>
            <div class="page-content">
                <?php
                $getall = getAllTracking();
                $total_paid = 0;
                $total_pending = 0;
                $total_failed = 0;
                $all_payments = [];
                
                while ($row = mysqli_fetch_assoc($getall)) {
                    $payment_status = $row['payment_status'] ?? 'pending';
                    $payment_method = $row['payment_method'] ?? 'cod';
                    $amount = floatval($row['total_fee']);

                    if ($payment_status == 'paid') {
                        $total_paid += $amount;
                    } else if ($payment_status == 'pending') {
                        $total_pending += $amount;
                    } else if ($payment_status == 'failed') {
                        $total_failed += $amount;
                    }
                    
                    $all_payments[] = $row;
                }
                ?>

                <section class="row">
                    <div class="col-12">
                        <div class="stats-cards">
                            <div class="stat-card stat-paid">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label"><?php __e('admin_total_paid'); ?></div>
                                    <div class="stat-value">$<?php echo number_format($total_paid, 2); ?></div>
                                </div>
                            </div>
                            <div class="stat-card stat-pending">
                                <div class="stat-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label"><?php __e('admin_total_pending'); ?></div>
                                    <div class="stat-value">$<?php echo number_format($total_pending, 2); ?></div>
                                </div>
                            </div>
                            <div class="stat-card stat-failed">
                                <div class="stat-icon">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label"><?php __e('admin_total_failed'); ?></div>
                                    <div class="stat-value">$<?php echo number_format($total_failed, 2); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="payments-list">
                                    <?php
                                    $has_payments = false;
                                    foreach ($all_payments as $row) {
                                        $has_payments = true;
                                        $payment_status = $row['payment_status'] ?? 'pending';
                                        $payment_method = $row['payment_method'] ?? 'cod';
                                        $amount = floatval($row['total_fee']);

                                        // Get status class
                                        $status_class = 'status-pending';
                                        if ($payment_status == 'paid') {
                                            $status_class = 'status-paid';
                                        } else if ($payment_status == 'failed') {
                                            $status_class = 'status-failed';
                                        }
                                        ?>
                                        <div class="payment-item">
                                            <div class="payment-item-header">
                                                <div class="payment-header-left">
                                                    <div class="payment-id-badge"><?php __e('admin_order'); ?> #<?php echo $row['request_id']; ?></div>
                                                    <div class="tracking-code"><?php __e('admin_tracking'); ?>: <?php echo htmlspecialchars($row['tracking_code']); ?></div>
                                                </div>
                                                <span class="payment-status-badge <?php echo $status_class; ?>">
                                                    <?php 
                                                    if ($payment_status == 'paid') {
                                                        __e('admin_paid');
                                                    } elseif ($payment_status == 'pending') {
                                                        __e('admin_pending');
                                                    } elseif ($payment_status == 'failed') {
                                                        __e('admin_failed');
                                                    } else {
                                                        echo ucfirst($payment_status);
                                                    }
                                                    ?>
                                                </span>
                                            </div>

                                            <div class="payment-item-body">
                                                <div class="payment-info-grid">
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_customer'); ?></label>
                                                        <div class="info-value"><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></div>
                                                    </div>
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_amount'); ?></label>
                                                        <div class="info-value amount">$<?php echo number_format($amount, 2); ?></div>
                                                    </div>
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_payment_method'); ?></label>
                                                        <div class="info-value">
                                                            <?php if ($payment_method == 'paypal'): ?>
                                                                <span class="badge badge-info"><?php __e('admin_paypal'); ?></span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary"><?php __e('admin_cod'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($row['paypal_transaction_id'])): ?>
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_transaction_id'); ?></label>
                                                        <div class="info-value transaction-id"><?php echo htmlspecialchars($row['paypal_transaction_id']); ?></div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_payment_date'); ?></label>
                                                        <div class="info-value">
                                                            <?php if (!empty($row['payment_date'])): ?>
                                                                <?php echo date('M d, Y H:i', strtotime($row['payment_date'])); ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="payment-info-item">
                                                        <label><?php __e('admin_date'); ?></label>
                                                        <div class="info-value"><?php echo date('M d, Y H:i', strtotime($row['date_updated'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="payment-item-footer">
                                                <a href="order_detail.php?order_id=<?php echo $row['request_id']; ?>" class="btn-view">
                                                    <i class="bi bi-eye"></i> <?php __e('admin_order_details'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    if (!$has_payments) {
                                        echo '<div class="empty-state">
                                            <i class="bi bi-credit-card" style="font-size: 48px; color: #6c757d; margin-bottom: 16px;"></i>
                                            <h4>' . __t('admin_no_payments') . '</h4>
                                            <p>' . __t('admin_no_payments_desc') . '</p>
                                        </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php include 'pages/footer.php'; ?>
    
    <style>
        .page-heading p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .stat-paid .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .stat-pending .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .stat-failed .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
        }

        .stat-paid .stat-value {
            color: #10b981;
        }

        .stat-pending .stat-value {
            color: #f59e0b;
        }

        .stat-failed .stat-value {
            color: #ef4444;
        }

        .payments-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .payment-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .payment-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-color: #d0d0d0;
            transform: translateY(-2px);
        }

        .payment-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .payment-header-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .payment-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            display: inline-block;
            width: fit-content;
        }

        .tracking-code {
            color: white;
            font-size: 13px;
            opacity: 0.95;
        }

        .payment-status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .payment-item-body {
            padding: 20px;
        }

        .payment-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .payment-info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .payment-info-item label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .payment-info-item .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #212529;
        }

        .payment-info-item .info-value.amount {
            font-size: 18px;
            color: #667eea;
            font-weight: 700;
        }

        .payment-info-item .info-value.transaction-id {
            font-family: monospace;
            font-size: 13px;
            color: #495057;
        }

        .text-muted {
            color: #6c757d;
            font-style: italic;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-info {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
        }

        .badge-secondary {
            background: rgba(0, 0, 0, 0.06);
            color: #495057;
        }

        .payment-item-footer {
            padding: 16px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h4 {
            margin: 16px 0 8px;
            color: #495057;
            font-size: 18px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }

            .payment-info-grid {
                grid-template-columns: 1fr;
            }

            .payment-item-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="../css/rtl.css">
    <?php endif; ?>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>

