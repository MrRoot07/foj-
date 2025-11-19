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
                <h3><?php __e('admin_order_details'); ?></h3>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><?php __e('admin_dashboard'); ?></a></li>
                            <li class="breadcrumb-item"><a href="orders.php"><?php __e('admin_orders'); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php __e('admin_order_details'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="page-content">
                <?php
                $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
                
                if ($order_id <= 0) {
                    header("Location: orders.php");
                    exit;
                }

                $getall = getAllTracking();
                $order_found = false;

                while ($row = mysqli_fetch_assoc($getall)) {
                    if ($row['request_id'] == $order_id) {
                        $order_found = true;
                        $request_id = $row['request_id'];
                        ?>
                        <div class="order-detail-card">
                            <div class="order-detail-header">
                                <div class="order-header-left">
                                    <div class="order-id-badge"><?php __e('admin_order'); ?> #<?php echo $row['request_id']; ?></div>
                                    <div class="tracking-code-text"><?php __e('admin_tracking'); ?>: <strong><?php echo htmlspecialchars($row['tracking_code']); ?></strong></div>
                                </div>
                                <?php if (!empty($row['qr_code_path'])): ?>
                                    <div class="qr-code-section">
                                        <a href="../<?php echo htmlspecialchars($row['qr_code_path']); ?>" download="QR_Code_<?php echo htmlspecialchars($row['tracking_code']); ?>.png" class="qr-image-link">
                                            <img src="../<?php echo htmlspecialchars($row['qr_code_path']); ?>" alt="QR Code" class="qr-image">
                                        </a>
                                        <small class="qr-label"><?php __e('admin_qr_code_label'); ?></small>
                                        <div class="qr-actions">
                                            <button type="button" class="btn-qr-action btn-download" onclick="downloadQRCode('<?php echo htmlspecialchars($row['qr_code_path']); ?>', '<?php echo htmlspecialchars($row['tracking_code']); ?>')">
                                                <i class="bi bi-download"></i> <?php __e('download'); ?>
                                            </button>
                                            <button type="button" class="btn-qr-action btn-print" onclick="printQRCode('<?php echo htmlspecialchars($row['qr_code_path']); ?>', '<?php echo htmlspecialchars($row['tracking_code']); ?>', '<?php echo $request_id; ?>')">
                                                <i class="bi bi-printer"></i> <?php __e('print'); ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="order-detail-body">
                                <div class="info-section">
                                    <div class="section-title"><?php __e('admin_customer_information'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <label><?php __e('admin_full_name'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('admin_phone'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('admin_email'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <div class="section-title"><?php __e('admin_receiver_information'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <label><?php __e('request_receiver_name'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['res_name']); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('request_receiver_phone'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['res_phone']); ?></div>
                                        </div>
                                        <div class="info-item full-width">
                                            <label><?php __e('request_delivery_address'); ?></label>
                                            <div class="info-value"><?php echo nl2br(htmlspecialchars($row['red_address'])); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <div class="section-title"><?php __e('admin_shipping_details'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <label><?php __e('request_weight'); ?></label>
                                            <div class="info-value"><?php echo $row['weight']; ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('request_sender_phone'); ?></label>
                                            <div class="info-value"><?php echo htmlspecialchars($row['sender_phone']); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('request_dropoff_point'); ?></label>
                                            <div class="info-value">
                                                <?php
                                                $getLocation = getAllAreabyID($row['send_location']);
                                                $row2 = mysqli_fetch_assoc($getLocation);
                                                echo htmlspecialchars($row2['area_name']);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('request_pickup_point'); ?></label>
                                            <div class="info-value">
                                                <?php
                                                $getLocation = getAllAreabyID($row['end_location']);
                                                $row2 = mysqli_fetch_assoc($getLocation);
                                                echo htmlspecialchars($row2['area_name']);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <div class="section-title"><?php __e('admin_payment_information'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <label><?php __e('admin_amount'); ?></label>
                                            <div class="info-value amount-value">$<?php echo number_format(floatval($row['total_fee']), 2); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('admin_payment_method'); ?></label>
                                            <div class="info-value">
                                                <?php 
                                                $payment_method = $row['payment_method'] ?? 'cod';
                                                if ($payment_method == 'paypal') {
                                                    echo '<span class="badge badge-info">' . __t('admin_paypal') . '</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary">' . __t('admin_cod') . '</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <label><?php __e('admin_payment_status'); ?></label>
                                            <div class="info-value">
                                                <?php 
                                                $payment_status = $row['payment_status'] ?? 'pending';
                                                if ($payment_status == 'paid') {
                                                    echo '<span class="badge badge-success">' . __t('admin_paid') . '</span>';
                                                } elseif ($payment_status == 'pending') {
                                                    echo '<span class="badge badge-warning">' . __t('admin_pending') . '</span>';
                                                } elseif ($payment_status == 'failed') {
                                                    echo '<span class="badge badge-danger">' . __t('admin_failed') . '</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($row['paypal_transaction_id'])): ?>
                                        <div class="info-item">
                                            <label><?php __e('admin_transaction_id'); ?></label>
                                            <div class="info-value transaction-id"><?php echo htmlspecialchars($row['paypal_transaction_id']); ?></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php
                                // Get all statuses and determine which are active
                                $current_status = $row['tracking_status'];
                                $statuses = [
                                    1 => ['icon' => 'fa-shopping-cart', 'text' => __t('tracking_status_placed'), 'date' => date('d M H:i', strtotime($row['date_updated']))],
                                    2 => ['icon' => 'fa-box', 'text' => __t('tracking_status_preparing'), 'date' => ''],
                                    3 => ['icon' => 'fa-hand-holding-box', 'text' => __t('tracking_status_dropoff'), 'date' => ''],
                                    4 => ['icon' => 'fa-truck-pickup', 'text' => __t('tracking_status_picked'), 'date' => ''],
                                    5 => ['icon' => 'fa-warehouse', 'text' => __t('tracking_status_sorting'), 'date' => ''],
                                    6 => ['icon' => 'fa-truck', 'text' => __t('tracking_status_departed'), 'date' => ''],
                                    7 => ['icon' => 'fa-building', 'text' => __t('tracking_status_hub'), 'date' => ''],
                                    8 => ['icon' => 'fa-truck-fast', 'text' => __t('tracking_status_out_delivery'), 'date' => ''],
                                    9 => ['icon' => 'fa-exclamation-triangle', 'text' => __t('tracking_status_unsuccessful'), 'date' => ''],
                                    10 => ['icon' => 'fa-store', 'text' => __t('tracking_status_collection'), 'date' => ''],
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
                                ?>
                                
                                <div class="vertical-timeline-container">
                                    <h5 class="timeline-title"><?php __e('admin_order_tracking_timeline'); ?></h5>
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

                            </div>

                            <div class="order-detail-footer">
                                <div class="action-controls">
                                    <div class="control-group">
                                        <label for="tracking_status" class="control-label"><?php __e('admin_order_status'); ?></label>
                                        <select
                                            onchange='updateData(this, "<?php echo $request_id; ?>","tracking_status", "request", "request_id")'
                                            id="tracking_status <?php echo $request_id; ?>" 
                                            class='form-control-modern'
                                            name="tracking_status">
                                            <option value="1" <?php if ($row['tracking_status'] == "1") echo "selected"; ?>><?php __e('tracking_status_placed'); ?></option>
                                            <option value="2" <?php if ($row['tracking_status'] == "2") echo "selected"; ?>><?php __e('tracking_status_preparing'); ?></option>
                                            <option value="3" <?php if ($row['tracking_status'] == "3") echo "selected"; ?>><?php __e('tracking_status_dropoff'); ?></option>
                                            <option value="4" <?php if ($row['tracking_status'] == "4") echo "selected"; ?>><?php __e('tracking_status_picked'); ?></option>
                                            <option value="5" <?php if ($row['tracking_status'] == "5") echo "selected"; ?>><?php __e('tracking_status_sorting'); ?></option>
                                            <option value="6" <?php if ($row['tracking_status'] == "6") echo "selected"; ?>><?php __e('tracking_status_departed'); ?></option>
                                            <option value="7" <?php if ($row['tracking_status'] == "7") echo "selected"; ?>><?php __e('tracking_status_hub'); ?></option>
                                            <option value="8" <?php if ($row['tracking_status'] == "8") echo "selected"; ?>><?php __e('tracking_status_out_delivery'); ?></option>
                                            <option value="9" <?php if ($row['tracking_status'] == "9") echo "selected"; ?>><?php __e('tracking_status_unsuccessful'); ?></option>
                                            <option value="10" <?php if ($row['tracking_status'] == "10") echo "selected"; ?>><?php __e('tracking_status_collection'); ?></option>
                                            <option value="11" <?php if ($row['tracking_status'] == "11") echo "selected"; ?>><?php __e('tracking_status_delivered'); ?></option>
                                            <option value="12" <?php if ($row['tracking_status'] == "12" || $row['tracking_status'] == "5") echo "selected"; ?>><?php __e('tracking_status_canceled'); ?></option>
                                        </select>
                                    </div>
                                    <div class="control-group">
                                        <label for="payment_status" class="control-label"><?php __e('admin_payment_status'); ?></label>
                                        <select
                                            onchange='updateData(this, "<?php echo $request_id; ?>","payment_status", "request", "request_id")'
                                            id="payment_status <?php echo $request_id; ?>" 
                                            class='form-control-modern'
                                            name="payment_status">
                                            <option value="pending" <?php if (($row['payment_status'] ?? 'pending') == "pending") echo "selected"; ?>><?php __e('admin_pending'); ?></option>
                                            <option value="paid" <?php if (($row['payment_status'] ?? 'pending') == "paid") echo "selected"; ?>><?php __e('admin_paid'); ?></option>
                                            <option value="failed" <?php if (($row['payment_status'] ?? 'pending') == "failed") echo "selected"; ?>><?php __e('admin_failed'); ?></option>
                                        </select>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label"><?php __e('admin_actions'); ?></label>
                                        <button type="button"
                                            onclick="deleteData(<?php echo $row['request_id']; ?>,'request', 'request_id')"
                                            class="btn-delete-action">
                                            <i class="bi bi-trash"></i> <?php __e('admin_delete_order'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="back-button-container">
                                    <a href="orders.php" class="btn-back">
                                        <i class="bi bi-arrow-left"></i> <?php __e('admin_back_to_orders'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                        break;
                    }
                }

                if (!$order_found) {
                    echo '<div class="alert alert-danger">' . __t('admin_order_not_found') . '</div>';
                    echo '<a href="orders.php" class="btn btn-secondary">' . __t('admin_back_to_orders') . '</a>';
                }
                ?>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    
    <script>
        function downloadQRCode(qrPath, trackingCode) {
            // Create a temporary anchor element to trigger download
            const link = document.createElement('a');
            link.href = '../' + qrPath;
            link.download = 'QR_Code_' + trackingCode + '.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        function printQRCode(qrPath, trackingCode, orderId) {
            // Create a print-friendly window
            const printWindow = window.open('', '_blank', 'width=600,height=800');
            const qrImageUrl = '../' + qrPath;
            
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
    </script>
</body>
<style>
    .order-detail-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-top: 20px;
    }

    .order-detail-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .order-header-left {
        flex: 1;
    }

    .order-id-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .tracking-code-text {
        color: white;
        font-size: 14px;
        opacity: 0.95;
    }

    .tracking-code-text strong {
        font-weight: 600;
    }

    .qr-code-section {
        text-align: center;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 16px;
        backdrop-filter: blur(10px);
    }

    .qr-image-link {
        display: inline-block;
        transition: transform 0.2s ease;
    }

    .qr-image-link:hover {
        transform: scale(1.05);
    }

    .qr-image {
        max-width: 140px;
        height: auto;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 8px;
        background: white;
        display: block;
        margin: 0 auto 8px;
    }

    .qr-label {
        display: block;
        color: white;
        font-size: 11px;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .qr-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-qr-action {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-download {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        backdrop-filter: blur(10px);
    }

    .btn-download:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    .btn-print {
        background: rgba(255, 255, 255, 0.25);
        color: white;
        backdrop-filter: blur(10px);
    }

    .btn-print:hover {
        background: rgba(255, 255, 255, 0.35);
    }

    .order-detail-body {
        padding: 24px;
    }

    .info-section {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e9ecef;
    }

    .info-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #667eea;
        display: inline-block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-item label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #212529;
        word-break: break-word;
    }

    .info-value.amount-value {
        font-size: 20px;
        color: #667eea;
        font-weight: 700;
    }

    .info-value.transaction-id {
        font-family: monospace;
        font-size: 13px;
        color: #495057;
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

    .vertical-timeline-container {
        margin: 30px 0;
        padding: 24px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .timeline-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 24px;
        color: #2c3e50;
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
        background: #28a745;
        border-color: #28a745;
        box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2);
    }

    .timeline-item.active .timeline-dot {
        background: #2c3e50;
        border-color: #2c3e50;
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
        color: #6c757d;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .timeline-item.active .timeline-date {
        color: #495057;
    }

    .timeline-text {
        font-size: 14px;
        color: #495057;
        line-height: 1.5;
    }

    .timeline-item.active .timeline-text {
        color: #212529;
        font-weight: 500;
    }

    .timeline-item.current .timeline-text {
        color: #28a745;
        font-weight: 600;
    }

    .order-detail-footer {
        background: #f8f9fa;
        padding: 24px;
        border-top: 1px solid #e9ecef;
    }

    .action-controls {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .control-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .control-label {
        font-size: 12px;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-modern {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #ffffff;
        cursor: pointer;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control-modern:hover {
        border-color: #ced4da;
    }

    .btn-delete-action {
        background: #ef4444;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .btn-delete-action:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .back-button-container {
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        margin-top: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #6c757d;
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-1px);
        color: white;
    }

    @media (max-width: 768px) {
        .order-detail-header {
            flex-direction: column;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-controls {
            grid-template-columns: 1fr;
        }

        .qr-code-section {
            width: 100%;
        }
    }
</style>

<?php if ($is_rtl): ?>
<link rel="stylesheet" href="../css/rtl.css">
<?php endif; ?>

</html>

