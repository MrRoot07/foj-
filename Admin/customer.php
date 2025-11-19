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
                <h3><?php __e('admin_customers_title'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_customers'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="search-container">
                                    <div class="search-wrapper">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" 
                                               id="customerSearch" 
                                               class="search-input" 
                                               placeholder="<?php __e('admin_search_customers'); ?>">
                                        <button type="button" class="search-clear" id="clearSearch" style="display: none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="search-results-info" id="searchResultsInfo" style="display: none;">
                                        <span id="resultsCount">0</span> <?php __e('admin_customers_found'); ?>
                                    </div>
                                </div>
                                <div class="customers-list" id="customersList">
                                    <?php
                                    $getall = getAllcustomers();
                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $customer_id = $row['customer_id'];
                                        ?>
                                        <div class="customer-list-item" 
                                             data-customer-id="<?php echo $customer_id; ?>"
                                             data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
                                             data-email="<?php echo strtolower(htmlspecialchars($row['email'])); ?>"
                                             data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                             data-nic="<?php echo strtolower(htmlspecialchars($row['nic'])); ?>">
                                            <div class="customer-item-header">
                                                <div class="customer-id-section">
                                                    <span class="customer-id-badge">ID: <?php echo $customer_id; ?></span>
                                                    <?php if ($row['active'] == 1): ?>
                                                        <span class="badge-status active">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge-status inactive">Inactive</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="customer-item-body">
                                                <div class="customer-fields-grid">
                                                    <div class="customer-field">
                                                        <label><?php __e('admin_full_name'); ?></label>
                                                        <input type="text" 
                                                               id="name_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['name']); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'name', 'customer', 'customer_id')">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_email'); ?></label>
                                                        <input type="email" 
                                                               id="email_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['email']); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'email', 'customer', 'customer_id')">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_phone'); ?></label>
                                                        <input type="text" 
                                                               id="phone_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['phone']); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'phone', 'customer', 'customer_id')">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_nic'); ?></label>
                                                        <input type="text" 
                                                               id="nic_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['nic']); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'nic', 'customer', 'customer_id')">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_gender'); ?></label>
                                                        <select id="gender_<?php echo $customer_id; ?>"
                                                                class="form-control editable-input" 
                                                                onchange="updateData(this, '<?php echo $customer_id; ?>', 'gender', 'customer', 'customer_id')">
                                                            <option value="1" <?php echo $row['gender'] == "1" ? "selected" : ""; ?>><?php __e('admin_male'); ?></option>
                                                            <option value="2" <?php echo $row['gender'] == "2" ? "selected" : ""; ?>><?php __e('admin_female'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="section-divider">
                                                    <span><?php __e('admin_address_information'); ?></span>
                                                </div>

                                                <div class="customer-fields-grid">
                                                    <div class="customer-field">
                                                        <label><?php __e('admin_street'); ?></label>
                                                        <input type="text" 
                                                               id="street_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['street'] ?? ''); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'street', 'customer', 'customer_id')"
                                                               placeholder="<?php __e('admin_street'); ?>">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_city'); ?></label>
                                                        <input type="text" 
                                                               id="city_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['city'] ?? ''); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'city', 'customer', 'customer_id')"
                                                               placeholder="<?php __e('admin_city'); ?>">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_state'); ?></label>
                                                        <input type="text" 
                                                               id="state_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['state'] ?? ''); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'state', 'customer', 'customer_id')"
                                                               placeholder="<?php __e('admin_state'); ?>">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_zip_code'); ?></label>
                                                        <input type="text" 
                                                               id="zip_code_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['zip_code'] ?? ''); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'zip_code', 'customer', 'customer_id')"
                                                               placeholder="<?php __e('admin_zip_code'); ?>">
                                                    </div>

                                                    <div class="customer-field">
                                                        <label><?php __e('admin_additional_address'); ?></label>
                                                        <input type="text" 
                                                               id="additional_address_<?php echo $customer_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['additional_address'] ?? ''); ?>" 
                                                               onchange="updateData(this, '<?php echo $customer_id; ?>', 'additional_address', 'customer', 'customer_id')"
                                                               placeholder="<?php __e('register_additional'); ?>">
                                                    </div>
                        </div>
                    </div>

                                            <div class="customer-item-footer">
                                                <button type="button"
                                                        onclick="deleteData(<?php echo $customer_id; ?>, 'customer', 'customer_id')"
                                                        class="btn-delete" 
                                                        title="<?php __e('admin_delete_customer'); ?>">
                                                    <i class="bi bi-trash"></i> <?php __e('admin_delete_customer'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>

    <style>
        .page-heading p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .customers-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .customer-list-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .customer-list-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-color: #d0d0d0;
        }

        .customer-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 14px 20px;
        }

        .customer-id-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-status.active {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-status.inactive {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .customer-item-body {
            padding: 20px;
        }

        .customer-fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .customer-field {
            display: flex;
            flex-direction: column;
        }

        .customer-field label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .editable-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .editable-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .editable-input:hover {
            border-color: #ced4da;
            background: #ffffff;
        }

        .section-divider {
            margin: 24px 0 16px;
            position: relative;
            text-align: center;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }

        .section-divider span {
            position: relative;
            background: #ffffff;
            padding: 0 12px;
            color: #6c757d;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .customer-item-footer {
            padding: 16px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .btn-delete:active {
            transform: translateY(0);
        }

        .search-container {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            max-width: 600px;
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

        .search-results-info span {
            color: #667eea;
            font-weight: 600;
        }

        .customer-list-item.hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .customer-fields-grid {
                grid-template-columns: 1fr;
            }

            .search-wrapper {
                max-width: 100%;
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
            const searchInput = $('#customerSearch');
            const clearButton = $('#clearSearch');
            const resultsInfo = $('#searchResultsInfo');
            const resultsCount = $('#resultsCount');
            const customerItems = $('.customer-list-item');

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
                filterCustomers('');
            });

            // Search functionality
            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterCustomers(searchTerm);
            });

            function filterCustomers(searchTerm) {
                let visibleCount = 0;

                if (searchTerm === '') {
                    customerItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }

                customerItems.each(function() {
                    const $item = $(this);
                    const customerId = $item.data('customer-id').toString();
                    const name = $item.data('name') || '';
                    const email = $item.data('email') || '';
                    const phone = $item.data('phone') || '';
                    const nic = $item.data('nic') || '';

                    const searchableText = (customerId + ' ' + name + ' ' + email + ' ' + phone + ' ' + nic).toLowerCase();

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


    <script src="assets/js/bootstrap.bundle.min.js"></script>




    <script src="assets/vendors/apexcharts/apexcharts.js"></script>

    <script src="assets/js/pages/dashboard.js"></script>




    <script src="assets/js/main.js"></script>


</body>





</html>
