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
                <h3><?php __e('admin_price_table'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_prices'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="price-header">
                                    <button class="btn-add-price" data-bs-toggle="modal" data-bs-target="#PriceModal">
                                        <i class="bi bi-plus-circle"></i> <?php __e('admin_add_new_price'); ?>
                                    </button>
                                </div>

                                <div class="price-list">
                                    <?php
                                    $getall = getAllPrice();
                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $price_id = $row['price_id'];
                                        
                                        // Get area names
                                        $getStartArea = getAllAreabyID($row['start_area']);
                                        $startAreaRow = mysqli_fetch_assoc($getStartArea);
                                        $startAreaName = $startAreaRow['area_name'];
                                        
                                        $getEndArea = getAllAreabyID($row['end_area']);
                                        $endAreaRow = mysqli_fetch_assoc($getEndArea);
                                        $endAreaName = $endAreaRow['area_name'];
                                        ?>
                                        <div class="price-item">
                                            <div class="price-item-header">
                                                <div class="price-id-badge">ID: <?php echo $price_id; ?></div>
                                                <div class="price-date"><?php echo date('M d, Y', strtotime($row['date_updated'])); ?></div>
                                            </div>
                                            
                                            <div class="price-item-body">
                                                <div class="price-route">
                                                    <div class="route-section">
                                                        <label><?php __e('admin_start_area'); ?></label>
                                                        <select id="start_area_<?php echo $price_id; ?>"
                                                                class="form-control editable-select" 
                                                                onchange="updateData(this, '<?php echo $price_id; ?>', 'start_area', 'price_table', 'price_id')">
                                                    <?php
                                                    $getallCat = getAllArea();
                                                    while ($row2 = mysqli_fetch_assoc($getallCat)) { ?>
                                                                <option value="<?php echo $row2['area_id']; ?>" 
                                                                        <?php if ($row['start_area'] == $row2['area_id']) echo "selected"; ?>>
                                                            <?php echo $row2['area_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                    </div>
                                                    
                                                    <div class="route-arrow">
                                                        <i class="bi bi-arrow-right"></i>
                                                    </div>
                                                    
                                                    <div class="route-section">
                                                        <label><?php __e('admin_end_area'); ?></label>
                                                        <select id="end_area_<?php echo $price_id; ?>"
                                                                class="form-control editable-select" 
                                                                onchange="updateData(this, '<?php echo $price_id; ?>', 'end_area', 'price_table', 'price_id')">
                                                    <?php
                                                    $getallCat = getAllArea();
                                                    while ($row2 = mysqli_fetch_assoc($getallCat)) { ?>
                                                                <option value="<?php echo $row2['area_id']; ?>" 
                                                                        <?php if ($row['end_area'] == $row2['area_id']) echo "selected"; ?>>
                                                            <?php echo $row2['area_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="price-amount-section">
                                                    <label><?php __e('admin_price'); ?></label>
                                                    <div class="price-input-wrapper">
                                                        <span class="currency-symbol">$</span>
                                                        <input type="number" 
                                                               id="price_<?php echo $price_id; ?>"
                                                               class="form-control price-input" 
                                                    value="<?php echo $row['price']; ?>"
                                                               onchange="updateData(this, '<?php echo $price_id; ?>', 'price', 'price_table', 'price_id')"
                                                               min="0"
                                                               step="0.01">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="price-item-footer">
                                                <button type="button"
                                                        onclick="deleteData(<?php echo $price_id; ?>, 'price_table', 'price_id')"
                                                        class="btn-delete" 
                                                        title="<?php __e('admin_delete_price'); ?>">
                                                    <i class="bi bi-trash"></i> <?php __e('delete'); ?>
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

    <!-- Modal -->
    <div class="modal fade" id="PriceModal" tabindex="-1" aria-labelledby="PriceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PriceModalLabel">Add New Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="priceForm" data-parsley-validate="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-field">
                                <label for="start_area" class="form-label">Start Area</label>
                            <select id="start_area" class="form-control" name="start_area" required>
                                <option value="">Select Start Area</option>
                                <?php 
                                $getall = getAllArea();
                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                    <option value="<?php echo $row['area_id']; ?>">
                                        <?php echo $row['area_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        
                        <div class="form-field">
                                <label for="end_area" class="form-label">End Area</label>
                            <select id="end_area" class="form-control" name="end_area" required>
                                <option value="">Select End Area</option>
                                <?php 
                                $getall = getAllArea();
                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                    <option value="<?php echo $row['area_id']; ?>">
                                        <?php echo $row['area_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                        <div class="form-field">
                                <label for="price" class="form-label">Price</label>
                            <div class="price-input-wrapper">
                                <span class="currency-symbol">$</span>
                                <input type="number" 
                                       class="form-control price-input" 
                                       name="price" 
                                       id="price" 
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                    required>
                            </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" onclick="addPrice(this.form)" name="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Price
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .page-heading p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .price-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .btn-add-price {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-add-price:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .price-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .price-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .price-item:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-color: #d0d0d0;
        }

        .price-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .price-date {
            color: white;
            font-size: 12px;
            opacity: 0.9;
        }

        .price-item-body {
            padding: 24px;
        }

        .price-route {
            display: flex;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 24px;
        }

        .route-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .route-section label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .route-arrow {
            display: flex;
            align-items: center;
            padding-bottom: 28px;
            color: #667eea;
            font-size: 24px;
        }

        .price-amount-section {
            display: flex;
            flex-direction: column;
        }

        .price-amount-section label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-symbol {
            position: absolute;
            left: 14px;
            color: #6c757d;
            font-weight: 600;
            font-size: 16px;
            z-index: 1;
        }

        .price-input {
            width: 100%;
            padding: 12px 14px 12px 32px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .price-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .editable-select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
            cursor: pointer;
        }

        .editable-select:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .editable-select:hover {
            border-color: #ced4da;
            background: #ffffff;
        }

        .price-item-footer {
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

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 20px 24px;
        }

        .modal-title {
            color: white;
            font-weight: 600;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 24px;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 16px 24px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        @media (max-width: 768px) {
            .price-route {
                flex-direction: column;
                gap: 16px;
            }

            .route-arrow {
                transform: rotate(90deg);
                padding: 0;
                justify-content: center;
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
</body>

</html>
