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
                <h3><?php __e('admin_orders'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_orders'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="orders-header">
                                    <div class="header-actions">
                                        <a href="add_courier.php" class="btn-action btn-register">
                                            <i class="bi bi-person-plus"></i> <?php __e('admin_register_customer'); ?>
                                        </a>
                                        <a href="add_request.php" class="btn-action btn-add-order">
                                            <i class="bi bi-plus-circle"></i> <?php __e('admin_add_order'); ?>
                                        </a>
                                        <button type="button" class="btn-action btn-scan" onclick="openQRScanner()">
                                            <i class="bi bi-qr-code-scan"></i> <?php __e('admin_scan_qr'); ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="orders-list">
                                    <?php
                                    $getall = getAllTracking();
                                    $has_orders = false;

                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $has_orders = true;
                                        $payment_status = $row['payment_status'] ?? 'pending';
                                        $payment_method = $row['payment_method'] ?? 'cod';
                                        $amount = floatval($row['total_fee']);

                                        // Get status text and class
                                        $status_text = __t('orders_status_pending');
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
                                        } else if ($row['tracking_status'] == 5 || $row['tracking_status'] == 12) {
                                            $status_text = __t('tracking_status_canceled');
                                            $status_class = 'status-canceled';
                                        }
                                        ?>
                                        <div class="order-item">
                                            <div class="order-item-header">
                                                <div class="order-header-left">
                                                    <div class="order-id-badge"><?php __e('admin_order'); ?> #<?php echo $row['request_id']; ?></div>
                                                    <div class="tracking-code"><?php __e('admin_tracking'); ?>: <?php echo htmlspecialchars($row['tracking_code']); ?></div>
                                                </div>
                                                <span class="order-status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                            </div>

                                            <div class="order-item-body">
                                                <div class="order-info-grid">
                                                    <div class="order-info-item">
                                                        <label><?php __e('admin_customer'); ?></label>
                                                        <div class="info-value"><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></div>
                                                    </div>
                                                    <div class="order-info-item">
                                                        <label><?php __e('admin_amount'); ?></label>
                                                        <div class="info-value amount">$<?php echo number_format($amount, 2); ?></div>
                                                    </div>
                                                    <div class="order-info-item">
                                                        <label><?php __e('admin_payment_method'); ?></label>
                                                        <div class="info-value">
                                                            <?php if ($payment_method == 'paypal'): ?>
                                                                <span class="badge badge-info"><?php __e('admin_paypal'); ?></span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary"><?php __e('admin_cod'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="order-info-item">
                                                        <label><?php __e('admin_payment_status'); ?></label>
                                                        <div class="info-value">
                                                            <?php if ($payment_status == 'paid'): ?>
                                                                <span class="badge badge-success"><?php __e('admin_paid'); ?></span>
                                                            <?php elseif ($payment_status == 'pending'): ?>
                                                                <span class="badge badge-warning"><?php __e('admin_pending'); ?></span>
                                                            <?php elseif ($payment_status == 'failed'): ?>
                                                                <span class="badge badge-danger"><?php __e('admin_failed'); ?></span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary"><?php echo ucfirst($payment_status); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="order-info-item">
                                                        <label><?php __e('admin_date'); ?></label>
                                                        <div class="info-value"><?php echo date('M d, Y H:i', strtotime($row['date_updated'])); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="order-item-footer">
                                                <a href="order_detail.php?order_id=<?php echo $row['request_id']; ?>" class="btn-view">
                                                    <i class="bi bi-eye"></i> <?php __e('admin_view_details'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                    if (!$has_orders) {
                                        echo '<div class="empty-state">
                                            <i class="bi bi-inbox" style="font-size: 48px; color: #6c757d; margin-bottom: 16px;"></i>
                                            <h4>' . __t('admin_no_orders') . '</h4>
                                            <p>' . __t('admin_no_orders_desc') . '</p>
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
    
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="../css/rtl.css">
    <?php endif; ?>
    <style>
        .page-heading p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .orders-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-add-order {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-add-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-scan {
            background: #10b981;
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .btn-scan:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .order-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-color: #d0d0d0;
            transform: translateY(-2px);
        }

        .order-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-header-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-id-badge {
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

        .order-status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-preparing {
            background: rgba(37, 99, 235, 0.2);
            color: #2563eb;
            border: 1px solid rgba(37, 99, 235, 0.3);
        }

        .status-shipped {
            background: rgba(6, 182, 212, 0.2);
            color: #06b6d4;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }

        .status-delivered {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-canceled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .order-item-body {
            padding: 20px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .order-info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-info-item label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-info-item .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #212529;
        }

        .order-info-item .info-value.amount {
            font-size: 18px;
            color: #667eea;
            font-weight: 700;
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

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .order-item-footer {
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
            .header-actions {
                width: 100%;
            }

            .btn-action {
                flex: 1;
                justify-content: center;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
            }

            .order-item-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <!-- QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <!-- QR Scanner Modal -->
    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrScannerModalLabel"><?php __e('admin_scan_qr_code'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php __e('close'); ?>" onclick="stopQRScanner()"></button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let html5QrcodeScanner = null;
        
        function openQRScanner() {
            const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
            modal.show();
            
            // Initialize scanner when modal is shown
            document.getElementById('qrScannerModal').addEventListener('shown.bs.modal', function () {
                startQRScanner();
            }, { once: true });
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
            modal.hide();
            
            try {
                // Handle both full URLs and relative paths
                let urlObj;
                if (url.startsWith('http://') || url.startsWith('https://')) {
                    urlObj = new URL(url);
                } else {
                    // If it's just a path, try to extract order_id
                    const match = url.match(/order_id[=:](\d+)/);
                    if (match) {
                        window.location.href = 'order_detail.php?order_id=' + match[1];
                        return;
                    }
                    urlObj = new URL(url, window.location.origin);
                }
                
                const orderId = urlObj.searchParams.get('order_id');
                
                if (orderId) {
                    window.location.href = 'order_detail.php?order_id=' + orderId;
                } else {
                    alert('Invalid QR code. Could not find order ID.');
                }
            } catch (e) {
                // Try to extract order_id from the URL string directly
                const match = url.match(/order_id[=:](\d+)/);
                if (match) {
                    window.location.href = 'order_detail.php?order_id=' + match[1];
                } else {
                    alert('Invalid QR code format.');
                }
            }
        }
        
        document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function () {
            stopQRScanner();
        });
    </script>
</body>

</html>

