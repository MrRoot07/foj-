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
<?php checkEmployeeAccess(['payments.php']); ?>

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
                                    <div class="stat-value">SAR<?php echo number_format($total_paid, 2); ?></div>
                                </div>
                            </div>
                            <div class="stat-card stat-pending">
                                <div class="stat-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label"><?php __e('admin_total_pending'); ?></div>
                                    <div class="stat-value">SAR<?php echo number_format($total_pending, 2); ?></div>
                                </div>
                            </div>
                            <div class="stat-card stat-failed">
                                <div class="stat-icon">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label"><?php __e('admin_total_failed'); ?></div>
                                    <div class="stat-value">SAR<?php echo number_format($total_failed, 2); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="search-container">
                                    <div class="search-wrapper">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" 
                                               id="paymentSearch" 
                                               class="search-input" 
                                               placeholder="<?php __e('admin_search_payments'); ?>">
                                        <button type="button" class="search-clear" id="clearPaymentSearch" style="display: none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="search-results-info" id="paymentSearchResultsInfo" style="display: none;">
                                        <span id="paymentResultsCount">0</span> <?php __e('admin_payments_found'); ?>
                                    </div>
                                </div>
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
                                        <div class="payment-item"
                                             data-tracking="<?php echo strtolower(htmlspecialchars($row['tracking_code'])); ?>"
                                             data-customer="<?php echo strtolower(htmlspecialchars($row['name'] ?? '')); ?>"
                                             data-status="<?php echo strtolower($payment_status); ?>">
                                            <div class="payment-item-header">
                                                <div class="payment-header-left">
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
                                                        <div class="info-value amount">SAR<?php echo number_format($amount, 2); ?></div>
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

        .search-container {
            margin-bottom: 20px;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            color: #6c757d;
            font-size: 18px;
            z-index: 1;
        }

        .search-input {
            width: 100%;
            padding: 12px 45px 12px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-clear {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-clear:hover {
            background: #e9ecef;
            color: #495057;
        }

        .search-results-info {
            margin-top: 12px;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        .payment-item.hidden {
            display: none;
        }

        .payments-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 16px;
            max-height: calc(100vh - 400px);
            overflow-y: auto;
            padding-right: 8px;
        }

        .payments-list::-webkit-scrollbar {
            width: 8px;
        }

        .payments-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .payments-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .payments-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .payment-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .payment-item:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            border-color: #d0d0d0;
            transform: translateY(-1px);
        }

        .payment-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .payment-header-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .payment-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            display: inline-block;
            width: fit-content;
        }

        .tracking-code {
            color: white;
            font-size: 11px;
            opacity: 0.95;
        }

        .payment-status-badge {
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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
            padding: 12px 14px;
            flex: 1;
        }

        .payment-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 16px;
        }

        .payment-info-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .payment-info-item label {
            font-size: 10px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .payment-info-item .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #212529;
        }

        .payment-info-item .info-value.amount {
            font-size: 16px;
            color: #667eea;
            font-weight: 700;
        }

        .payment-info-item .info-value.transaction-id {
            font-family: monospace;
            font-size: 11px;
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
            padding: 10px 14px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 1px 4px rgba(102, 126, 234, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
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

        @media (max-width: 1200px) {
            .payments-list {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .payments-list {
                grid-template-columns: 1fr;
                max-height: none;
            }

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const searchInput = $('#paymentSearch');
            const clearButton = $('#clearPaymentSearch');
            const resultsInfo = $('#paymentSearchResultsInfo');
            const resultsCount = $('#paymentResultsCount');
            const paymentItems = $('.payment-item');

            // Show/hide clear button
            searchInput.on('input', function() {
                if ($(this).val().length > 0) {
                    clearButton.show();
                } else {
                    clearButton.hide();
                    resultsInfo.hide();
                }
            });

            // Clear search
            clearButton.on('click', function() {
                searchInput.val('');
                $(this).hide();
                resultsInfo.hide();
                filterPayments('');
            });

            // Search functionality
            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterPayments(searchTerm);
            });

            function filterPayments(searchTerm) {
                let visibleCount = 0;

                if (searchTerm === '') {
                    paymentItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }

                paymentItems.each(function() {
                    const $item = $(this);
                    const tracking = $item.data('tracking') || '';
                    const customer = $item.data('customer') || '';
                    const status = $item.data('status') || '';

                    const searchableText = (tracking + ' ' + customer + ' ' + status).toLowerCase();

                    if (searchableText.includes(searchTerm)) {
                        $item.removeClass('hidden');
                        visibleCount++;
                    } else {
                        $item.addClass('hidden');
                    }
                });

                resultsCount.text(visibleCount);
                resultsInfo.show();
            }
        });
    </script>
</body>

</html>

