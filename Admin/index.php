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
                <h3><?php __e('admin_dashboard'); ?></h3>
                <p class="text-muted"><?php __e('admin_dashboard_welcome'); ?></p>
            </div>

            <div class="page-content">
                <section class="row">
                    <!-- Overview Stats -->
                    <div class="col-12">
                        <div class="dashboard-section">
                            <h4 class="section-title"><?php __e('admin_overview'); ?></h4>
                            <div class="stats-grid">
                                <div class="stat-card stat-card-primary">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-columns"></i>
                                                </div>
                                            </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_branches'); ?></div>
                                        <div class="stat-value"><?php echo dataCount('branch'); ?></div>
                                            </div>
                                        </div>

                                <div class="stat-card stat-card-success">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_customers'); ?></div>
                                        <div class="stat-value"><?php echo dataCount('customer'); ?></div>
                                                </div>
                                            </div>

                                <div class="stat-card stat-card-info">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-person-fill"></i>
                                            </div>
                                        </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_employees'); ?></div>
                                        <div class="stat-value"><?php echo dataCount('employee'); ?></div>
                                    </div>
                                </div>

                                <div class="stat-card stat-card-warning">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-truck"></i>
                                                </div>
                                            </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_total_orders'); ?></div>
                                        <div class="stat-value"><?php echo dataCount('request'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <!-- Order Status Stats -->
                    <div class="col-12">
                        <div class="dashboard-section">
                            <h4 class="section-title"><?php __e('admin_order_status'); ?></h4>
                            <div class="stats-grid">
                                <div class="stat-card stat-card-pending">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-clock-history"></i>
                                                </div>
                                            </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_pending_orders'); ?></div>
                                        <div class="stat-value"><?php echo dataCountWhere('request', ' tracking_status = 1 '); ?></div>
                                            </div>
                                        </div>

                                <div class="stat-card stat-card-preparing">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-box-seam"></i>
                                    </div>
                                </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_preparing'); ?></div>
                                        <div class="stat-value"><?php echo dataCountWhere('request', ' tracking_status = 2 '); ?></div>
                            </div>
                        </div>

                                <div class="stat-card stat-card-delivered">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                                </div>
                                            </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_completed_orders'); ?></div>
                                        <div class="stat-value"><?php echo dataCountWhere('request', ' tracking_status = 3 '); ?></div>
                                            </div>
                                        </div>

                                <div class="stat-card stat-card-canceled">
                                    <div class="stat-icon-wrapper">
                                        <div class="stat-icon">
                                            <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                </div>
                                    <div class="stat-content">
                                        <div class="stat-label"><?php __e('admin_canceled_orders'); ?></div>
                                        <div class="stat-value"><?php echo dataCountWhere('request', ' tracking_status = 5 '); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <!-- Quick Actions -->
                    <div class="col-12">
                        <div class="dashboard-section">
                            <h4 class="section-title"><?php __e('admin_quick_actions'); ?></h4>
                            <div class="quick-actions-grid">
                                <a href="add_request.php" class="quick-action-card">
                                    <div class="action-icon">
                                        <i class="bi bi-plus-circle-fill"></i>
                                                </div>
                                    <div class="action-text"><?php __e('admin_add_new_order'); ?></div>
                                </a>
                                <a href="add_courier.php" class="quick-action-card">
                                    <div class="action-icon">
                                        <i class="bi bi-person-plus-fill"></i>
                                            </div>
                                    <div class="action-text"><?php __e('admin_register_customer'); ?></div>
                                </a>
                                <a href="orders.php" class="quick-action-card">
                                    <div class="action-icon">
                                        <i class="bi bi-qr-code-scan"></i>
                                            </div>
                                    <div class="action-text"><?php __e('admin_scan_qr_code'); ?></div>
                                </a>
                                <a href="payments.php" class="quick-action-card">
                                    <div class="action-icon">
                                        <i class="bi bi-credit-card-fill"></i>
                                        </div>
                                    <div class="action-text"><?php __e('admin_view_payments'); ?></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>

    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="../css/rtl.css">
    <?php endif; ?>
    <style>
        .page-heading {
            margin-bottom: 30px;
        }

        .page-heading h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }

        .page-heading p {
            margin: 8px 0 0;
            font-size: 14px;
            color: #6c757d;
        }

        .dashboard-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e9ecef;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--card-color);
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .stat-icon-wrapper {
            flex-shrink: 0;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            background: var(--card-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1;
        }

        .stat-card-primary {
            --card-color: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card-primary .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card-success {
            --card-color: #10b981;
        }

        .stat-card-success .stat-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card-info {
            --card-color: #2563eb;
        }

        .stat-card-info .stat-icon {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .stat-card-warning {
            --card-color: #f59e0b;
        }

        .stat-card-warning .stat-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-card-pending {
            --card-color: #f59e0b;
        }

        .stat-card-pending .stat-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-card-preparing {
            --card-color: #2563eb;
        }

        .stat-card-preparing .stat-icon {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .stat-card-delivered {
            --card-color: #10b981;
        }

        .stat-card-delivered .stat-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card-canceled {
            --card-color: #ef4444;
        }

        .stat-card-canceled .stat-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .quick-action-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .action-icon {
            font-size: 36px;
            opacity: 0.9;
        }

        .action-text {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-heading h3 {
                font-size: 24px;
            }
        }
    </style>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
