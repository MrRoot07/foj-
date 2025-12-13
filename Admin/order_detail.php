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
<?php checkEmployeeAccess(['orders.php', 'order_detail.php']); ?>

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
                                            <div class="info-value amount-value">SAR<?php echo number_format(floatval($row['total_fee']), 2); ?></div>
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
                                // Display rating if exists
                                $order_rating = isset($row['rating']) ? intval($row['rating']) : null;
                                $rating_comment = isset($row['rating_comment']) ? htmlspecialchars($row['rating_comment']) : '';
                                $rating_date = isset($row['rating_date']) ? $row['rating_date'] : null;
                                if ($order_rating):
                                ?>
                                <div class="info-section">
                                    <div class="section-title"><?php __e('rating_title'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <label><?php __e('rating_view'); ?></label>
                                            <div class="info-value">
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <div style="display: flex; gap: 2px;">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <span style="font-size: 20px; color: <?php echo $i <= $order_rating ? '#fbbf24' : '#d1d5db'; ?>;">★</span>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span style="font-weight: 600; color: var(--text);"><?php echo $order_rating; ?>/5</span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($rating_comment): ?>
                                        <div class="info-item full-width">
                                            <label><?php __e('rating_comment'); ?></label>
                                            <div class="info-value" style="padding: 12px; background: var(--panel); border-radius: 8px; border: 1px solid rgba(0,0,0,.08);">
                                                <?php echo nl2br($rating_comment); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($rating_date): ?>
                                        <div class="info-item">
                                            <label><?php __e('rating_date'); ?></label>
                                            <div class="info-value"><?php echo date('M d, Y H:i', strtotime($rating_date)); ?></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="info-section">
                                    <div class="section-title"><?php __e('rating_title'); ?></div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-value" style="color: var(--muted);">
                                                <?php __e('rating_not_rated'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

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
                                
                                // Define default statuses with their details
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
                                ?>
                                
                                <div class="vertical-timeline-container">
                                    <h5 class="timeline-title"><?php __e('admin_order_tracking_timeline'); ?></h5>
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
                                                    <div class="timeline-text"><?php echo htmlspecialchars($status_data['info']['text']); ?></div>
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

                            </div>

                            <div class="order-detail-footer">
                                <?php 
                                $is_canceled = (intval($row['tracking_status']) == 12);
                                ?>
                                
                                <?php if ($is_canceled): ?>
                                <!-- Order is Canceled - Locked State -->
                                <div style="background: rgba(239, 68, 68, 0.1); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 20px;">
                                    <div style="font-size: 48px; color: #ef4444; margin-bottom: 16px;">
                                        <i class="bi bi-lock-fill"></i>
                                    </div>
                                    <h4 style="margin: 0 0 8px; color: #ef4444; font-weight: 700;">
                                        <?php __e('order_canceled_locked'); ?>
                                    </h4>
                                    <p style="margin: 0; color: #6c757d; font-size: 14px;">
                                        <?php __e('order_canceled_locked_desc'); ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                                
                                <div class="action-controls">
                                    <div class="control-group status-control-group">
                                        <label for="tracking_status" class="control-label">
                                            <i class="bi bi-arrow-repeat"></i> <?php __e('admin_order_status'); ?>
                                        </label>
                                        
                                        <?php
                                        // Get current status and next valid statuses
                                        $current_status = intval($row['tracking_status']);
                                        $next_statuses = getNextValidStatuses($current_status);
                                        
                                        // Get all available statuses (default + custom)
                                        $all_statuses = [];
                                        
                                        // Default statuses
                                        $default_statuses = [
                                            1 => ['icon' => 'fa-shopping-cart', 'text' => __t('tracking_status_placed'), 'group' => 'order'],
                                            2 => ['icon' => 'fa-box', 'text' => __t('tracking_status_preparing'), 'group' => 'order'],
                                            3 => ['icon' => 'fa-hand-holding-box', 'text' => __t('tracking_status_dropoff'), 'group' => 'shipping'],
                                            4 => ['icon' => 'fa-truck-pickup', 'text' => __t('tracking_status_picked'), 'group' => 'shipping'],
                                            5 => ['icon' => 'fa-warehouse', 'text' => __t('tracking_status_sorting_arrived'), 'group' => 'shipping'],
                                            6 => ['icon' => 'fa-truck', 'text' => __t('tracking_status_sorting_departed'), 'group' => 'shipping'],
                                            7 => ['icon' => 'fa-building', 'text' => __t('tracking_status_hub_arrived'), 'group' => 'shipping'],
                                            8 => ['icon' => 'fa-truck-fast', 'text' => __t('tracking_status_out_delivery'), 'group' => 'delivery'],
                                            9 => ['icon' => 'fa-exclamation-triangle', 'text' => __t('tracking_status_delivery_failed'), 'group' => 'special'],
                                            10 => ['icon' => 'fa-store', 'text' => __t('tracking_status_ready_collection'), 'group' => 'special'],
                                            11 => ['icon' => 'fa-circle-check', 'text' => __t('tracking_status_delivered'), 'group' => 'delivery'],
                                            12 => ['icon' => 'fa-times-circle', 'text' => __t('tracking_status_canceled'), 'group' => 'special'],
                                        ];
                                        
                                        // Add default statuses
                                        foreach ($default_statuses as $id => $status) {
                                            $all_statuses[$id] = $status;
                                        }
                                        
                                        // Get custom statuses from database (using status_id starting from 100 to avoid conflicts)
                                        $custom_statuses_result = getAllCustomStatuses();
                                        $custom_start_id = 100;
                                        while ($custom_row = mysqli_fetch_assoc($custom_statuses_result)) {
                                            $custom_id = $custom_start_id + $custom_row['status_id'];
                                            $current_lang = get_current_lang();
                                            $status_text = ($current_lang === 'ar') ? $custom_row['status_name_ar'] : $custom_row['status_name_en'];
                                            $all_statuses[$custom_id] = [
                                                'icon' => $custom_row['status_icon'],
                                                'text' => $status_text,
                                                'group' => 'custom',
                                                'custom' => true,
                                                'custom_id' => $custom_row['status_id']
                                            ];
                                        }
                                        
                                        // Get current status display info
                                        $current_status_info = $all_statuses[$current_status] ?? ['icon' => 'fa-circle', 'text' => 'Status ' . $current_status];
                                        ?>
                                        
                                        <div class="status-select-wrapper">
                                            <select
                                                <?php if ($is_canceled): ?>disabled<?php else: ?>id="status_select_<?php echo $request_id; ?>"<?php endif; ?>
                                                class='form-control-modern status-select <?php if ($is_canceled): ?>disabled-locked<?php endif; ?>'
                                                name="tracking_status">
                                                <option value="<?php echo $current_status; ?>" selected>
                                                    <?php echo htmlspecialchars($current_status_info['text']); ?> (<?php __e('admin_current_status'); ?>)
                                                </option>
                                                
                                                <?php if ($is_canceled): ?>
                                                    <option disabled><?php __e('order_canceled_no_changes'); ?></option>
                                                <?php else: ?>
                                                    <?php if (empty($next_statuses)): ?>
                                                        <option disabled><?php __e('admin_no_next_status'); ?></option>
                                                    <?php else: ?>
                                                        <optgroup label="➡️ <?php __e('admin_next_steps'); ?>">
                                                            <?php foreach ($next_statuses as $next_status): ?>
                                                                <?php if (isset($all_statuses[$next_status])): ?>
                                                                    <option value="<?php echo $next_status; ?>">
                                                                        <i class="fa <?php echo $all_statuses[$next_status]['icon']; ?>"></i> <?php echo htmlspecialchars($all_statuses[$next_status]['text']); ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </optgroup>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isAdmin()): ?>
                                                    <optgroup label="➕ <?php __e('admin_add_custom_status'); ?>">
                                                        <option value="__add_new__"><?php __e('admin_add_new_status'); ?></option>
                                                    </optgroup>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </select>
                                            <div class="status-select-icon">
                                                <i class="bi bi-chevron-down"></i>
                                            </div>
                                        </div>
                                        <?php if (!$is_canceled): ?>
                                        <button type="button" 
                                                id="save_status_btn_<?php echo $request_id; ?>"
                                                onclick="saveStatusChange(<?php echo $request_id; ?>, <?php echo $current_status; ?>)"
                                                class="btn-save-status"
                                                style="margin-top: 8px;">
                                            <i class="bi bi-check-circle"></i> <?php __e('admin_save_status'); ?>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if (isAdmin()): ?>
                                        <!-- Custom Status Input Modal -->
                                        <div class="custom-status-input-wrapper" id="custom_status_input_<?php echo $request_id; ?>" style="display: none;">
                                            <div class="custom-status-input-box">
                                                <div class="custom-status-header">
                                                    <h6><?php __e('admin_add_custom_status'); ?></h6>
                                                    <button type="button" class="btn-close-custom" onclick="cancelCustomStatus('<?php echo $request_id; ?>', <?php echo $current_status; ?>)">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                                <div class="custom-status-body">
                                                    <div class="form-field-custom">
                                                        <label><?php __e('admin_status_name_en'); ?></label>
                                                        <input type="text" 
                                                               id="custom_status_name_en_<?php echo $request_id; ?>"
                                                               class="form-control-custom" 
                                                               placeholder="<?php __e('admin_enter_status_name_en'); ?>">
                                                    </div>
                                                    <div class="form-field-custom">
                                                        <label><?php __e('admin_status_name_ar'); ?></label>
                                                        <input type="text" 
                                                               id="custom_status_name_ar_<?php echo $request_id; ?>"
                                                               class="form-control-custom" 
                                                               placeholder="<?php __e('admin_enter_status_name_ar'); ?>">
                                                    </div>
                                                    <div class="form-field-custom">
                                                        <label><?php __e('admin_status_icon'); ?></label>
                                                        <select id="custom_status_icon_<?php echo $request_id; ?>" class="form-control-custom">
                                                            <option value="fa-circle">Default Circle</option>
                                                            <option value="fa-check-circle">Check Circle</option>
                                                            <option value="fa-truck">Truck</option>
                                                            <option value="fa-box">Box</option>
                                                            <option value="fa-warehouse">Warehouse</option>
                                                            <option value="fa-building">Building</option>
                                                            <option value="fa-store">Store</option>
                                                            <option value="fa-exclamation-triangle">Warning</option>
                                                            <option value="fa-times-circle">Cancel</option>
                                                            <option value="fa-shopping-cart">Shopping Cart</option>
                                                            <option value="fa-hand-holding-box">Hand Holding</option>
                                                            <option value="fa-truck-pickup">Truck Pickup</option>
                                                            <option value="fa-truck-fast">Fast Truck</option>
                                                        </select>
                                                    </div>
                                                    <div class="custom-status-actions">
                                                        <button type="button" 
                                                                class="btn-save-custom-status"
                                                                onclick="saveCustomStatus('<?php echo $request_id; ?>', <?php echo $current_status; ?>)">
                                                            <i class="bi bi-check-circle"></i> <?php __e('admin_save_and_apply'); ?>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn-cancel-custom-status"
                                                                onclick="cancelCustomStatus('<?php echo $request_id; ?>', <?php echo $current_status; ?>)">
                                                            <i class="bi bi-x-circle"></i> <?php __e('cancel'); ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <small class="status-help-text"><?php __e('admin_status_step_by_step_note'); ?></small>
                                    </div>
                                    <div class="control-group">
                                        <label for="payment_status" class="control-label"><?php __e('admin_payment_status'); ?></label>
                                        <select
                                            <?php if ($is_canceled): ?>disabled<?php else: ?>id="payment_status_select_<?php echo $request_id; ?>" onchange='handlePaymentStatusChange(this, "<?php echo $request_id; ?>")'<?php endif; ?>
                                            class='form-control-modern <?php if ($is_canceled): ?>disabled-locked<?php endif; ?>'
                                            name="payment_status">
                                            <option value="pending" <?php if (($row['payment_status'] ?? 'pending') == "pending") echo "selected"; ?>><?php __e('admin_pending'); ?></option>
                                            <option value="paid" <?php if (($row['payment_status'] ?? 'pending') == "paid") echo "selected"; ?>><?php __e('admin_paid'); ?></option>
                                            <option value="failed" <?php if (($row['payment_status'] ?? 'pending') == "failed") echo "selected"; ?>><?php __e('admin_failed'); ?></option>
                                        </select>
                                        <?php if ($is_canceled): ?>
                                        <small style="color: #6c757d; font-size: 11px; display: block; margin-top: 4px;">
                                            <i class="bi bi-lock"></i> <?php __e('order_canceled_locked'); ?>
                                        </small>
                                        <?php endif; ?>
                                        <?php if (($row['payment_status'] ?? 'pending') == 'failed' && !empty($row['payment_failure_reason'] ?? '')): ?>
                                        <div style="margin-top: 8px; padding: 10px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px;">
                                            <strong style="font-size: 12px; color: #ef4444; display: block; margin-bottom: 4px;"><?php __e('admin_payment_failure_reason'); ?>:</strong>
                                            <div style="font-size: 12px; color: #495057;"><?php echo nl2br(htmlspecialchars($row['payment_failure_reason'])); ?></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                    // Check for cancellation request
                                    $cancellation_request = null;
                                    $cancellation_result = getCancellationRequestByRequestId($request_id);
                                    if ($cancellation_result && mysqli_num_rows($cancellation_result) > 0) {
                                        $cancellation_request = mysqli_fetch_assoc($cancellation_result);
                                    }
                                    ?>
                                    
                                    <?php if ($cancellation_request): ?>
                                    <div class="control-group" style="grid-column: 1 / -1;">
                                        <label class="control-label"><?php __e('admin_cancellation_request'); ?></label>
                                        <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; border: 1px solid #e9ecef;">
                                            <div style="margin-bottom: 12px;">
                                                <strong><?php __e('cancellation_request_status'); ?>:</strong>
                                                <span style="padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; margin-left: 8px;
                                                    <?php 
                                                    if ($cancellation_request['cancellation_status'] == 'pending') {
                                                        echo 'background: rgba(245, 158, 11, 0.1); color: #f59e0b;';
                                                    } elseif ($cancellation_request['cancellation_status'] == 'approved') {
                                                        echo 'background: rgba(239, 68, 68, 0.1); color: #ef4444;';
                                                    } else {
                                                        echo 'background: rgba(0, 0, 0, 0.06); color: #495057;';
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
                                            <div style="margin-bottom: 12px;">
                                                <strong><?php __e('cancellation_request_reason'); ?>:</strong>
                                                <div style="margin-top: 4px; color: #495057; padding: 8px; background: white; border-radius: 6px; border: 1px solid #e9ecef;">
                                                    <?php echo nl2br(htmlspecialchars($cancellation_request['cancellation_reason'])); ?>
                                                </div>
                                            </div>
                                            <div style="margin-bottom: 12px; font-size: 12px; color: #6c757d;">
                                                <strong><?php __e('cancellation_request_date'); ?>:</strong> <?php echo date('M d, Y H:i', strtotime($cancellation_request['requested_date'])); ?>
                                            </div>
                                            
                                            <?php if ($cancellation_request['cancellation_status'] == 'pending' && !$is_canceled): ?>
                                            <div style="display: flex; gap: 8px; margin-top: 16px;">
                                                <button type="button" 
                                                        onclick="handleCancellationRequest(<?php echo $cancellation_request['cancellation_id']; ?>, 'approved', <?php echo $request_id; ?>)"
                                                        class="btn" style="flex: 1; background: #10b981; color: white; padding: 10px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                                                    <i class="bi bi-check-circle"></i> <?php __e('admin_approve_cancellation'); ?>
                                                </button>
                                                <button type="button" 
                                                        onclick="showRejectModal(<?php echo $cancellation_request['cancellation_id']; ?>, <?php echo $request_id; ?>)"
                                                        class="btn" style="flex: 1; background: #ef4444; color: white; padding: 10px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                                                    <i class="bi bi-x-circle"></i> <?php __e('admin_reject_cancellation'); ?>
                                                </button>
                                            </div>
                                            <?php elseif ($is_canceled): ?>
                                            <div style="margin-top: 12px; padding: 8px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px;">
                                                <i class="bi bi-lock"></i> <?php __e('order_canceled_locked'); ?>
                                            </div>
                                            <?php elseif ($cancellation_request['admin_response_comment']): ?>
                                            <div style="margin-top: 12px; padding: 8px; background: white; border-radius: 6px; border: 1px solid #e9ecef;">
                                                <strong><?php __e('cancellation_request_admin_response'); ?>:</strong>
                                                <div style="margin-top: 4px; color: #495057;">
                                                    <?php echo nl2br(htmlspecialchars($cancellation_request['admin_response_comment'])); ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php 
                                            // Show refund section if cancellation is approved and payment was made
                                            $order_payment_method = $row['payment_method'] ?? 'cod';
                                            $order_payment_status = $row['payment_status'] ?? 'pending';
                                            if ($cancellation_request['cancellation_status'] == 'approved' && 
                                                $order_payment_method == 'paypal' && 
                                                $order_payment_status == 'paid'): 
                                            ?>
                                            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e9ecef;">
                                                <h5 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #2c3e50;">
                                                    <i class="bi bi-arrow-counterclockwise"></i> <?php __e('refund_title'); ?>
                                                </h5>
                                                
                                                <?php if ($cancellation_request['refund_status'] == 'completed'): ?>
                                                    <!-- Refund completed -->
                                                    <div style="padding: 12px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px;">
                                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                                            <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 18px;"></i>
                                                            <strong style="color: #10b981;"><?php __e('refund_completed'); ?></strong>
                                                        </div>
                                                        <?php if ($cancellation_request['refund_date']): ?>
                                                        <div style="font-size: 12px; color: #6c757d;">
                                                            <?php __e('refund_date'); ?>: <?php echo date('M d, Y H:i', strtotime($cancellation_request['refund_date'])); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($cancellation_request['refund_transaction_id']): ?>
                                                        <div style="font-size: 12px; color: #6c757d; margin-top: 4px;">
                                                            <?php __e('refund_transaction_id'); ?>: <?php echo htmlspecialchars($cancellation_request['refund_transaction_id']); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif ($cancellation_request['refund_status'] == 'pending'): ?>
                                                    <!-- Refund pending - show PayPal account and mark as completed button -->
                                                    <div style="padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px;">
                                                        <div style="margin-bottom: 12px;">
                                                            <strong style="color: #f59e0b;"><?php __e('refund_pending'); ?></strong>
                                                        </div>
                                                        
                                                        <?php if ($cancellation_request['refund_amount']): ?>
                                                        <div style="margin-bottom: 12px; font-size: 14px;">
                                                            <strong><?php __e('refund_amount'); ?>:</strong> SAR<?php echo number_format(floatval($cancellation_request['refund_amount']), 2); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($cancellation_request['customer_paypal_account'])): ?>
                                                        <div style="margin-bottom: 12px; padding: 10px; background: white; border-radius: 6px; border: 1px solid #e9ecef;">
                                                            <strong><?php __e('refund_customer_paypal'); ?>:</strong>
                                                            <div style="margin-top: 4px; font-family: monospace; color: #495057;">
                                                                <?php echo htmlspecialchars($cancellation_request['customer_paypal_account']); ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <?php if ($cancellation_request['refund_status'] == 'pending'): ?>
                                                        <div style="margin-top: 16px;">
                                                            <button type="button" 
                                                                    onclick="showRefundCompleteModal(<?php echo $cancellation_request['cancellation_id']; ?>, <?php echo $cancellation_request['refund_amount'] ?? 0; ?>)"
                                                                    class="btn" style="background: #10b981; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                                                                <i class="bi bi-check-circle"></i> <?php __e('admin_mark_refund_completed'); ?>
                                                            </button>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php else: ?>
                                                        <div style="padding: 10px; background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.3); border-radius: 6px;">
                                                            <i class="bi bi-info-circle"></i> <?php __e('refund_waiting_customer_paypal'); ?>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="control-group">
                                        <label class="control-label"><?php __e('admin_actions'); ?></label>
                                        <button type="button"
                                            onclick="deleteData(<?php echo $row['request_id']; ?>,'request', 'request_id')"
                                            class="btn-delete-action"
                                            <?php if ($is_canceled): ?>disabled style="opacity: 0.5; cursor: not-allowed;"<?php endif; ?>>
                                            <i class="bi bi-trash"></i> <?php __e('admin_delete_order'); ?>
                                        </button>
                                        <?php if ($is_canceled): ?>
                                        <small style="color: #6c757d; font-size: 11px; display: block; margin-top: 4px;">
                                            <i class="bi bi-lock"></i> <?php __e('order_canceled_cannot_delete'); ?>
                                        </small>
                                        <?php endif; ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function saveStatusChange(request_id, current_status) {
            const selectElement = document.getElementById('status_select_' + request_id);
            if (!selectElement) {
                return;
            }
            
            const selectedValue = selectElement.value;
            
            if (selectedValue === '__add_new__') {
                // Show custom status input
                const inputWrapper = document.getElementById('custom_status_input_' + request_id);
                if (inputWrapper) {
                    inputWrapper.style.display = 'block';
                    document.getElementById('custom_status_name_en_' + request_id).focus();
                }
                // Reset select to current value
                selectElement.value = current_status;
            } else if (selectedValue == current_status) {
                alert('<?php echo addslashes(__t('admin_status_no_change')); ?>');
                return;
            } else {
                // Update status normally
                updateStatusStepByStep(selectElement, request_id, current_status);
            }
        }
        
        // Handle custom status selection change
        $(document).ready(function() {
            $('[id^="status_select_"]').on('change', function() {
                const selectId = $(this).attr('id');
                const requestId = selectId.replace('status_select_', '');
                const selectedValue = $(this).val();
                
                if (selectedValue === '__add_new__') {
                    // Show custom status input
                    const inputWrapper = document.getElementById('custom_status_input_' + requestId);
                    if (inputWrapper) {
                        inputWrapper.style.display = 'block';
                        document.getElementById('custom_status_name_en_' + requestId).focus();
                    }
                    // Reset select to current value - we'll get it from the selected option
                    const currentOption = $(this).find('option[selected]');
                    if (currentOption.length) {
                        $(this).val(currentOption.val());
                    }
                }
            });
        });
        
        function saveCustomStatus(request_id, current_status) {
            const nameEn = document.getElementById('custom_status_name_en_' + request_id).value.trim();
            const nameAr = document.getElementById('custom_status_name_ar_' + request_id).value.trim();
            const icon = document.getElementById('custom_status_icon_' + request_id).value;
            
            if (!nameEn || !nameAr) {
                alert('<?php echo addslashes(__t('admin_please_enter_status_names')); ?>');
                return;
            }
            
            // Show loading
            const saveBtn = document.querySelector('#custom_status_input_' + request_id + ' .btn-save-custom-status');
            const originalHtml = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php echo addslashes(__t('admin_saving')); ?>...';
            
            // Add custom status
            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=addCustomStatus",
                data: {
                    name_en: nameEn,
                    name_ar: nameAr,
                    icon: icon
                },
                dataType: 'json',
                success: function(response) {
                    let result = response;
                    if (typeof result === 'string') {
                        try {
                            result = JSON.parse(result);
                        } catch (e) {
                            result = { success: false, error: 'Invalid response' };
                        }
                    }
                    
                    if (result.success && result.status_id) {
                        // Calculate the new status ID (100 + custom_id)
                        const newStatusId = 100 + result.status_id;
                        
                        // Update the order with the new custom status
                        updateStatusWithCustomStatus(request_id, newStatusId, current_status);
                    } else {
                        alert(result.error || '<?php echo addslashes(__t('admin_failed_to_save_status')); ?>');
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalHtml;
                    }
                },
                error: function(xhr, status, error) {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHtml;
                    let errorMsg = '<?php echo addslashes(__t('admin_failed_to_save_status')); ?>';
                    try {
                        if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMsg = response.error;
                            }
                        }
                    } catch (e) {
                        // Use default error
                    }
                    alert(errorMsg);
                }
            });
        }
        
        function updateStatusWithCustomStatus(request_id, new_status_id, current_status) {
            const data = {
                id: request_id,
                field: 'tracking_status',
                value: new_status_id,
                id_fild: 'request_id',
                table: 'request'
            };

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updateData",
                data: data,
                dataType: 'json',
                success: function(response) {
                    let result = response;
                    if (typeof result === 'string') {
                        try {
                            result = JSON.parse(result);
                        } catch (e) {
                            result = { success: true };
                        }
                    }
                    
                    if (result && result.success === false) {
                        alert(result.error || '<?php echo addslashes(__t('admin_status_change_error')); ?>');
                    } else {
                        // Hide input wrapper
                        document.getElementById('custom_status_input_' + request_id).style.display = 'none';
                        // Clear inputs
                        document.getElementById('custom_status_name_en_' + request_id).value = '';
                        document.getElementById('custom_status_name_ar_' + request_id).value = '';
                        // Reload page to show new status
                        showStatusChangeSuccess(function() {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    alert('<?php echo addslashes(__t('admin_failed_to_update_status')); ?>');
                }
            });
        }
        
        function cancelCustomStatus(request_id, current_status) {
            const inputWrapper = document.getElementById('custom_status_input_' + request_id);
            if (inputWrapper) {
                inputWrapper.style.display = 'none';
                // Clear inputs
                document.getElementById('custom_status_name_en_' + request_id).value = '';
                document.getElementById('custom_status_name_ar_' + request_id).value = '';
            }
            // Reset select
            const select = document.getElementById('tracking_status ' + request_id);
            if (select) {
                select.value = current_status;
            }
        }
        
        function updateStatusStepByStep(element, request_id, current_status) {
            const new_status = parseInt(element.value);
            
            // Validate on client side too
            if (new_status === current_status || isNaN(new_status)) {
                alert('<?php echo addslashes(__t('admin_status_no_change')); ?>');
                return; // No change
            }
            
            // Get save button
            const saveBtn = document.getElementById('save_status_btn_' + request_id);
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php echo addslashes(__t('admin_saving')); ?>...';
            }
            
            // Show loading overlay immediately to prevent visual jumps
            showStatusChangeLoading();
            
            // Disable the select and prevent further changes
            element.disabled = true;
            element.style.pointerEvents = 'none';
            const originalValue = current_status;
            
            const data = {
                id: request_id,
                field: 'tracking_status',
                value: new_status,
                id_fild: 'request_id',
                table: 'request'
            };

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updateData",
                data: data,
                timeout: 10000, // 10 second timeout
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    // Response should already be parsed as JSON
                    let result = response;
                    
                    // Handle case where response might be a string
                    if (typeof result === 'string') {
                        try {
                            result = JSON.parse(result);
                        } catch (e) {
                            // If parsing fails, assume success if response is truthy
                            result = { success: true };
                        }
                    }
                    
                    if (result && result.success === false) {
                        hideStatusChangeLoading();
                        alert(result.error || '<?php echo addslashes(__t('admin_status_change_error')); ?>');
                        element.value = originalValue;
                        element.disabled = false;
                        element.style.pointerEvents = 'auto';
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> <?php echo addslashes(__t('admin_save_status')); ?>';
                        }
                    } else {
                        // Show success message briefly before reload
                        showStatusChangeSuccess(function() {
                            // Smooth reload with scroll position maintained
                            window.location.href = window.location.href.split('#')[0];
                        });
                    }
                },
                error: function(xhr, status, error) {
                    hideStatusChangeLoading();
                    let errorMsg = '<?php echo addslashes(__t('admin_status_change_error')); ?>';
                    try {
                        if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response && response.error) {
                                errorMsg = response.error;
                            }
                        }
                    } catch (e) {
                        // Use default error message
                        if (xhr.status === 400) {
                            errorMsg = '<?php echo addslashes(__t('admin_status_change_error')); ?>';
                        }
                    }
                    alert(errorMsg);
                    element.value = originalValue;
                    element.disabled = false;
                    element.style.pointerEvents = 'auto';
                    const saveBtn = document.getElementById('save_status_btn_' + request_id);
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="bi bi-check-circle"></i> <?php echo addslashes(__t('admin_save_status')); ?>';
                    }
                }
            });
        }
        
        function showStatusChangeLoading() {
            // Create or show loading overlay
            let overlay = document.getElementById('status-change-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'status-change-overlay';
                overlay.className = 'status-change-overlay';
                overlay.innerHTML = `
                    <div class="status-change-spinner">
                        <div class="spinner"></div>
                        <p><?php echo addslashes(__t('admin_updating_status')); ?></p>
                    </div>
                `;
                document.body.appendChild(overlay);
            }
            overlay.style.display = 'flex';
        }
        
        function hideStatusChangeLoading() {
            const overlay = document.getElementById('status-change-overlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
        
        function showStatusChangeSuccess(callback) {
            const overlay = document.getElementById('status-change-overlay');
            if (overlay) {
                overlay.innerHTML = `
                    <div class="status-change-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <p><?php echo addslashes(__t('admin_status_updated')); ?></p>
                    </div>
                `;
                // Wait 1 second to show success message, then reload smoothly
                setTimeout(function() {
                    hideStatusChangeLoading();
                    // Small delay before reload to ensure smooth transition
                    setTimeout(callback, 200);
                }, 1000);
            } else {
                setTimeout(callback, 200);
            }
        }
        
        function handlePaymentStatusChange(element, request_id) {
            const newStatus = element.value;
            const currentStatus = '<?php echo $row['payment_status'] ?? 'pending'; ?>';
            
            if (newStatus === 'failed') {
                // Prompt for failure reason
                const reason = prompt('<?php echo addslashes(__t('admin_payment_failure_reason_prompt')); ?>', '');
                if (reason === null) {
                    // User cancelled - reset to current status
                    element.value = currentStatus;
                    return;
                }
                
                if (reason.trim() === '') {
                    alert('<?php echo addslashes(__t('admin_payment_failure_reason_required')); ?>');
                    element.value = currentStatus;
                    return;
                }
                
                // Update payment status with failure reason
                updatePaymentStatus(request_id, 'failed', reason.trim());
            } else {
                // For pending or paid, update normally
                updatePaymentStatus(request_id, newStatus, null);
            }
        }
        
        function updatePaymentStatus(request_id, status, failure_reason) {
            const data = {
                id: request_id,
                field: 'payment_status',
                value: status,
                id_fild: 'request_id',
                table: 'request',
                payment_failure_reason: failure_reason
            };

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updatePaymentStatus",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        location.reload();
                    } else {
                        alert(response.error || '<?php echo addslashes(__t('admin_payment_status_update_error')); ?>');
                        // Reset select
                        const select = document.getElementById('payment_status_select_' + request_id);
                        if (select) {
                            select.value = '<?php echo $row['payment_status'] ?? 'pending'; ?>';
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Update failed:", error);
                    alert('<?php echo addslashes(__t('admin_payment_status_update_error')); ?>');
                    // Reset select
                    const select = document.getElementById('payment_status_select_' + request_id);
                    if (select) {
                        select.value = '<?php echo $row['payment_status'] ?? 'pending'; ?>';
                    }
                }
            });
        }
        
        function updateData(element, id, field, table, id_field) {
            const value = element.value;
            const data = {
                id: id,
                field: field,
                value: value,
                id_fild: id_field,
                table: table
            };

            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updateData",
                data: data,
                success: function(response) {
                    // Handle success
                    console.log("Update successful:", response);
                },
                error: function(error) {
                    // Handle error
                    console.error("Update failed:", error);
                    alert("Failed to update data.");
                }
            });
        }
        
        function deleteData(id, table, id_field) {
            // Check if order is canceled
            const isCanceled = <?php echo isset($is_canceled) && $is_canceled ? 'true' : 'false'; ?>;
            if (isCanceled) {
                alert('<?php echo addslashes(__t('order_canceled_cannot_delete')); ?>');
                return;
            }
            
            if (confirm("<?php __e('admin_confirm_delete'); ?>")) {
                const data = {
                    id: id,
                    table: table,
                    id_fild: id_field
                };

                $.ajax({
                    method: "POST",
                    url: "../server/api.php?function_code=deleteData",
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.success) {
                            location.reload();
                        } else {
                            alert(response.error || response.message || '<?php echo addslashes(__t('order_canceled_cannot_delete')); ?>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Delete failed:", error);
                        let errorMsg = "Failed to delete data.";
                        try {
                            if (xhr.responseText) {
                                const response = JSON.parse(xhr.responseText);
                                if (response && response.error) {
                                    errorMsg = response.error;
                                }
                            }
                        } catch (e) {
                            // Use default error
                        }
                        alert(errorMsg);
                    }
                });
            }
        }
        
        // Cancellation request handling
        function handleCancellationRequest(cancellation_id, status, request_id, comment) {
            // If comment is not provided and status is rejected, show prompt
            if (status === 'rejected' && !comment) {
                comment = prompt('<?php echo addslashes(__t('admin_rejection_reason_prompt')); ?>', '');
                if (comment === null || comment.trim() === '') {
                    return; // User cancelled or entered empty string
                }
            }
            
            const data = {
                cancellation_id: cancellation_id,
                status: status,
                admin_response_comment: comment || null
            };
            
            console.log('Submitting cancellation request:', data);
            
            // Show loading indicator
            const originalButtons = document.querySelectorAll('button[onclick*="handleCancellationRequest"], button[onclick*="showRejectModal"]');
            originalButtons.forEach(btn => {
                btn.disabled = true;
                btn.style.opacity = '0.6';
                const originalText = btn.innerHTML;
                btn.setAttribute('data-original-text', originalText);
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            });
            
            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updateCancellationStatus",
                data: data,
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    console.log('Cancellation request response:', response);
                    
                    // Re-enable buttons
                    originalButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                        const originalText = btn.getAttribute('data-original-text');
                        if (originalText) {
                            btn.innerHTML = originalText;
                        }
                    });
                    
                    if (response && response.success) {
                        alert(response.message || '<?php echo addslashes(__t('cancellation_request_processed')); ?>');
                        location.reload();
                    } else {
                        let errorMsg = '<?php echo addslashes(__t('cancellation_request_error')); ?>';
                        if (response && response.error) {
                            errorMsg = response.error;
                        }
                        alert(errorMsg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Cancellation request update failed:", error);
                    console.error("Status:", status);
                    console.error("Response:", xhr.responseText);
                    console.error("XHR object:", xhr);
                    
                    // Re-enable buttons
                    originalButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                        const originalText = btn.getAttribute('data-original-text');
                        if (originalText) {
                            btn.innerHTML = originalText;
                        }
                    });
                    
                    let errorMsg = '<?php echo addslashes(__t('cancellation_request_error')); ?>';
                    try {
                        if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response && response.error) {
                                errorMsg = response.error;
                            }
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        // Use default error message
                    }
                    alert(errorMsg);
                }
            });
        }
        
        function showRejectModal(cancellation_id, request_id) {
            const comment = prompt('<?php echo addslashes(__t('admin_rejection_reason_prompt')); ?>', '');
            if (comment !== null && comment.trim() !== '') {
                handleCancellationRequest(cancellation_id, 'rejected', request_id, comment);
            }
        }
        
        // Refund completion handling
        function showRefundCompleteModal(cancellation_id, refund_amount) {
            const transactionId = prompt('<?php echo addslashes(__t('admin_refund_transaction_prompt')); ?>', '');
            if (transactionId !== null && transactionId.trim() !== '') {
                markRefundAsCompleted(cancellation_id, transactionId.trim());
            }
        }
        
        function markRefundAsCompleted(cancellation_id, transaction_id) {
            const data = {
                cancellation_id: cancellation_id,
                refund_transaction_id: transaction_id
            };
            
            console.log('Marking refund as completed:', data);
            
            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=markRefundCompleted",
                data: data,
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    console.log('Refund completion response:', response);
                    
                    if (response && response.success) {
                        alert(response.message || '<?php echo addslashes(__t('refund_marked_completed')); ?>');
                        location.reload();
                    } else {
                        let errorMsg = '<?php echo addslashes(__t('refund_error')); ?>';
                        if (response && response.error) {
                            errorMsg = response.error;
                        }
                        alert(errorMsg);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Refund completion failed:", error);
                    console.error("Response:", xhr.responseText);
                    
                    let errorMsg = '<?php echo addslashes(__t('refund_error')); ?>';
                    try {
                        if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response && response.error) {
                                errorMsg = response.error;
                            }
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                    }
                    alert(errorMsg);
                }
            });
        }
    </script>
    
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

    /* Enhanced Status Select Design */
    .status-control-group {
        position: relative;
    }

    .status-control-group .control-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .status-control-group .control-label i {
        font-size: 14px;
        color: #667eea;
    }

    .status-select-wrapper {
        position: relative;
    }

    .status-select {
        appearance: none;
        padding-right: 45px;
        padding-left: 12px;
        background-image: none;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        line-height: 1.5;
    }

    .status-select:focus {
        background: #ffffff;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }

    .status-select option {
        padding: 10px 12px;
        font-weight: 500;
        background: #ffffff;
        color: #2c3e50;
    }

    .status-select option:checked,
    .status-select option[selected] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        font-weight: 600;
    }
    
    .status-select option:disabled {
        background: #f8f9fa;
        color: #6c757d;
        font-style: italic;
    }

    .status-select optgroup {
        font-weight: 700;
        font-size: 12px;
        color: #667eea;
        background: #f8f9fa;
        padding: 8px 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ecef;
    }

    .status-select optgroup option {
        padding-left: 24px;
        font-weight: 500;
        color: #495057;
    }

    .status-select optgroup option:checked {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
    }

    .status-select-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #667eea;
        font-size: 16px;
        transition: transform 0.2s ease;
        z-index: 1;
    }

    .status-select-wrapper:focus-within .status-select-icon {
        transform: translateY(-50%) rotate(180deg);
    }

    .status-help-text {
        font-size: 11px;
        color: #6c757d;
        margin-top: 8px;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .status-help-text::before {
        content: "ℹ️";
        font-size: 12px;
    }

    /* Status Progress Indicator */
    .status-progress-indicator {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        flex-wrap: wrap;
    }

    .current-status-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .status-badge-icon {
        font-size: 16px;
    }

    .status-badge-text {
        white-space: nowrap;
    }

    .status-arrow {
        font-size: 20px;
        color: #667eea;
        font-weight: bold;
    }

    .next-statuses {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .next-status-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #ffffff;
        border: 2px solid #667eea;
        color: #667eea;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .next-status-badge:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .status-final-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-final-badge i {
        font-size: 18px;
    }

    .status-select:disabled,
    .status-select.disabled-locked,
    .form-control-modern.disabled-locked {
        background: #f8f9fa;
        cursor: not-allowed;
        opacity: 0.6;
        border-color: #e9ecef;
    }
    
    .btn-delete-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-save-status {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: 100%;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .btn-save-status:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .btn-save-status:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Custom Status Input Styles */
    .custom-status-input-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }

    .custom-status-input-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .custom-status-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-status-header h6 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .btn-close-custom {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 18px;
    }

    .btn-close-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .custom-status-body {
        padding: 24px;
    }

    .form-field-custom {
        margin-bottom: 20px;
    }

    .form-field-custom:last-child {
        margin-bottom: 0;
    }

    .form-field-custom label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 14px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #f8f9fa;
        font-family: inherit;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .custom-status-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .btn-save-custom-status {
        flex: 1;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        justify-content: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-save-custom-status:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-save-custom-status:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-cancel-custom-status {
        flex: 1;
        background: #6c757d;
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
        justify-content: center;
        gap: 8px;
    }

    .btn-cancel-custom-status:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    /* Status Change Loading Overlay */
    .status-change-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .status-change-spinner {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .status-change-spinner .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .status-change-spinner p {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }

    .status-change-success {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .status-change-success i {
        font-size: 48px;
        color: #10b981;
        margin-bottom: 16px;
        display: block;
    }

    .status-change-success p {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
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

