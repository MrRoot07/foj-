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
<?php if (isEmployee()) { header("Location: index.php"); exit(); } ?>

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
                        <?php
                        // Get price per km from settings
                        $getSettings = getAllSettings();
                        $settings = mysqli_fetch_assoc($getSettings);
                        $price_per_km = isset($settings['price_per_km']) ? floatval($settings['price_per_km']) : 5.0;
                        ?>
                        
                        <!-- Price Per KM Configuration -->
                        <div class="card" style="margin-bottom: 20px;">
                            <div class="card-body">
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                                    <div style="flex: 1; min-width: 250px;">
                                        <label for="price_per_km" style="font-weight: 600; margin-bottom: 8px; display: block; color: #495057;">
                                            <i class="bi bi-speedometer2"></i> <?php __e('admin_price_per_km'); ?> (SAR/km)
                                        </label>
                                        <div class="price-input-wrapper" style="max-width: 200px;">
                                            <span class="currency-symbol">SAR</span>
                                            <input type="number" 
                                                   id="price_per_km" 
                                                   class="form-control price-input" 
                                                   value="<?php echo number_format($price_per_km, 2); ?>"
                                                   onchange="updatePricePerKm(this.value)"
                                                   min="0"
                                                   step="0.01"
                                                   style="padding-right: 50px;">
                                        </div>
                                        <small style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">
                                            <?php __e('admin_price_per_km_desc'); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="price-header">
                                    <button class="btn-add-price" data-bs-toggle="modal" data-bs-target="#PriceModal">
                                        <i class="bi bi-plus-circle"></i> <?php __e('admin_add_new_price'); ?>
                                    </button>
                                </div>

                                <div class="search-container">
                                    <div class="search-wrapper">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" 
                                               id="priceSearch" 
                                               class="search-input" 
                                               placeholder="<?php __e('admin_search_prices'); ?>">
                                        <button type="button" class="search-clear" id="clearPriceSearch" style="display: none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="search-results-info" id="priceSearchResultsInfo" style="display: none;">
                                        <span id="priceResultsCount">0</span> <?php __e('admin_prices_found'); ?>
                                    </div>
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
                                        <div class="price-item"
                                             data-start-area="<?php echo strtolower(htmlspecialchars($startAreaName)); ?>"
                                             data-end-area="<?php echo strtolower(htmlspecialchars($endAreaName)); ?>"
                                             data-price="<?php echo htmlspecialchars($row['price']); ?>">
                                            <div class="price-item-header">
                                                <div class="price-date"><?php echo date('M d, Y', strtotime($row['date_updated'])); ?></div>
                                            </div>
                                            
                                            <div class="price-item-body">
                                                <div class="price-route">
                                                    <div class="route-section">
                                                        <label><?php __e('admin_start_area'); ?></label>
                                                        <select id="start_area_<?php echo $price_id; ?>"
                                                                class="form-control editable-select" 
                                                                onchange="calculatePrice(<?php echo $price_id; ?>); updateData(this, '<?php echo $price_id; ?>', 'start_area', 'price_table', 'price_id')">
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
                                                                onchange="calculatePrice(<?php echo $price_id; ?>); updateData(this, '<?php echo $price_id; ?>', 'end_area', 'price_table', 'price_id')">
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
                                                    <label><?php __e('admin_auto_calculated_price'); ?></label>
                                                    <div class="price-input-wrapper">
                                                        <span class="currency-symbol">SAR</span>
                                                        <input type="number" 
                                                               id="auto_price_<?php echo $price_id; ?>"
                                                               class="form-control price-input auto-calculated-price" 
                                                               value=""
                                                               readonly
                                                               style="background: #e9ecef; cursor: not-allowed;"
                                                               min="0"
                                                               step="0.01">
                                                    </div>
                                                    <small style="color: #6c757d; font-size: 11px; margin-top: 4px; display: block;">
                                                        <i class="bi bi-calculator"></i> <?php __e('admin_auto_calculated'); ?>
                                                    </small>
                                                </div>
                                                
                                                <div class="price-amount-section" style="margin-top: 12px;">
                                                    <label><?php __e('admin_manual_price'); ?></label>
                                                    <div class="price-input-wrapper">
                                                        <span class="currency-symbol">SAR</span>
                                                        <input type="number" 
                                                               id="price_<?php echo $price_id; ?>"
                                                               class="form-control price-input manual-price" 
                                                               value="<?php echo number_format(floatval($row['price']), 2, '.', ''); ?>"
                                                               onchange="updateData(this, '<?php echo $price_id; ?>', 'price', 'price_table', 'price_id')"
                                                               min="0"
                                                               step="0.01"
                                                               pattern="[0-9]+(\.[0-9]{1,2})?">
                                                    </div>
                                                    <small style="color: #6c757d; font-size: 11px; margin-top: 4px; display: block;">
                                                        <i class="bi bi-pencil"></i> <?php __e('admin_manual_override'); ?>
                                                    </small>
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
                            <select id="start_area" class="form-control" name="start_area" required onchange="calculatePriceModal()">
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
                            <select id="end_area" class="form-control" name="end_area" required onchange="calculatePriceModal()">
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
                                <label for="auto_price" class="form-label"><?php __e('admin_auto_calculated_price'); ?></label>
                            <div class="price-input-wrapper">
                                <span class="currency-symbol">SAR</span>
                                <input type="number" 
                                       class="form-control price-input auto-calculated-price" 
                                       id="auto_price" 
                                       placeholder="0.00"
                                       readonly
                                       style="background: #e9ecef; cursor: not-allowed;"
                                       min="0"
                                       step="0.01">
                            </div>
                            <small style="color: #6c757d; font-size: 11px; margin-top: 4px; display: block;">
                                <i class="bi bi-calculator"></i> <?php __e('admin_auto_calculated'); ?>
                            </small>
                            </div>
                        
                        <div class="form-field">
                                <label for="price" class="form-label"><?php __e('admin_manual_price'); ?> <span style="color: #ef4444;">*</span></label>
                            <div class="price-input-wrapper">
                                <span class="currency-symbol">SAR</span>
                                <input type="number" 
                                       class="form-control price-input manual-price" 
                                       name="price" 
                                       id="price" 
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                       required>
                            </div>
                            <small style="color: #6c757d; font-size: 11px; margin-top: 4px; display: block;">
                                <i class="bi bi-pencil"></i> <?php __e('admin_manual_override'); ?>
                            </small>
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

        .price-item.hidden {
            display: none;
        }

        .price-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 16px;
            max-height: calc(100vh - 300px);
            overflow-y: auto;
            padding-right: 8px;
        }

        .price-list::-webkit-scrollbar {
            width: 8px;
        }

        .price-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .price-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .price-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .price-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .price-item:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            border-color: #d0d0d0;
            transform: translateY(-1px);
        }

        .price-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .price-date {
            color: white;
            font-size: 10px;
            opacity: 0.9;
        }

        .price-item-body {
            padding: 12px 14px;
            flex: 1;
        }

        .price-route {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            margin-bottom: 14px;
        }

        .route-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .route-section label {
            font-size: 10px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .route-arrow {
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            color: #667eea;
            font-size: 18px;
        }

        .price-amount-section {
            display: flex;
            flex-direction: column;
        }

        .price-amount-section label {
            font-size: 10px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .currency-symbol {
            position: absolute;
            right: 14px;
            color: #6c757d;
            font-weight: 600;
            font-size: 16px;
            z-index: 1;
            pointer-events: none;
        }

        .price-input {
            width: 100%;
            padding: 8px 28px 8px 10px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 14px;
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
            padding: 8px 10px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 12px;
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
            padding: 10px 14px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
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

        @media (max-width: 1200px) {
            .price-list {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .price-list {
                grid-template-columns: 1fr;
                max-height: none;
            }

            .price-route {
                flex-direction: column;
                gap: 12px;
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
    <script>
        $(document).ready(function() {
            const searchInput = $('#priceSearch');
            const clearButton = $('#clearPriceSearch');
            const resultsInfo = $('#priceSearchResultsInfo');
            const resultsCount = $('#priceResultsCount');
            const priceItems = $('.price-item');

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
                filterPrices('');
            });

            // Search functionality
            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterPrices(searchTerm);
            });

            function filterPrices(searchTerm) {
                let visibleCount = 0;

                if (searchTerm === '') {
                    priceItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }

                priceItems.each(function() {
                    const $item = $(this);
                    const startArea = $item.data('start-area') || '';
                    const endArea = $item.data('end-area') || '';
                    const price = $item.data('price') || '';

                    const searchableText = (startArea + ' ' + endArea + ' ' + price).toLowerCase();

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

        // City name mapping - maps area names to standardized city names
        const cityNameMap = {
            'riyadh': 'Riyadh',
            'al ahsa': 'Al Ahsa',
            'ahsa': 'Al Ahsa',
            'mecca': 'Mecca',
            'makkah': 'Mecca',
            'madinah': 'Madinah',
            'medina': 'Madinah',
            'jeddah': 'Jeddah',
            'dammam': 'Dammam'
        };

        // Distance matrix in kilometers (hard-coded)
        const distanceMatrix = {
            'Riyadh': {
                'Riyadh': 0,
                'Al Ahsa': 330,
                'Mecca': 870,
                'Madinah': 850,
                'Jeddah': 950,
                'Dammam': 400
            },
            'Al Ahsa': {
                'Riyadh': 330,
                'Al Ahsa': 0,
                'Mecca': 1200,
                'Madinah': 1150,
                'Jeddah': 1300,
                'Dammam': 150
            },
            'Mecca': {
                'Riyadh': 870,
                'Al Ahsa': 1200,
                'Mecca': 0,
                'Madinah': 450,
                'Jeddah': 80,
                'Dammam': 1300
            },
            'Madinah': {
                'Riyadh': 850,
                'Al Ahsa': 1150,
                'Mecca': 450,
                'Madinah': 0,
                'Jeddah': 420,
                'Dammam': 1250
            },
            'Jeddah': {
                'Riyadh': 950,
                'Al Ahsa': 1300,
                'Mecca': 80,
                'Madinah': 420,
                'Jeddah': 0,
                'Dammam': 1350
            },
            'Dammam': {
                'Riyadh': 400,
                'Al Ahsa': 150,
                'Mecca': 1300,
                'Madinah': 1250,
                'Jeddah': 1350,
                'Dammam': 0
            }
        };

        // Function to normalize city name
        function normalizeCityName(areaName) {
            if (!areaName) return null;
            const normalized = areaName.trim().toLowerCase();
            
            // Direct match
            if (cityNameMap[normalized]) {
                return cityNameMap[normalized];
            }
            
            // Partial match
            for (const key in cityNameMap) {
                if (normalized.includes(key) || key.includes(normalized)) {
                    return cityNameMap[key];
                }
            }
            
            // Check if area name contains city name
            for (const key in cityNameMap) {
                if (normalized.includes(key)) {
                    return cityNameMap[key];
                }
            }
            
            return null;
        }

        // Function to get city name from area select element
        function getCityNameFromSelect(selectElement) {
            if (!selectElement || !selectElement.value) return null;
            
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (!selectedOption) return null;
            
            const areaName = selectedOption.text.trim();
            return normalizeCityName(areaName);
        }

        // Get price per km from settings
        function getPricePerKm() {
            const pricePerKmInput = document.getElementById('price_per_km');
            if (pricePerKmInput && pricePerKmInput.value) {
                return parseFloat(pricePerKmInput.value) || 5.0;
            }
            return 5.0; // Default fallback
        }

        // Update price per km in settings
        function updatePricePerKm(value) {
            const pricePerKm = parseFloat(value) || 5.0;
            
            if (pricePerKm < 0) {
                alert('<?php echo addslashes(__t('admin_price_per_km_invalid')); ?>');
                document.getElementById('price_per_km').value = '5.00';
                return;
            }
            
            // Update via AJAX
            $.ajax({
                method: "POST",
                url: "../server/api.php?function_code=updateSettings",
                data: {
                    field: 'price_per_km',
                    value: pricePerKm
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        // Recalculate all prices
                        $('.price-item').each(function() {
                            const priceId = $(this).find('.editable-select').first().attr('id');
                            if (priceId) {
                                const id = priceId.replace('start_area_', '').replace('end_area_', '');
                                if (id && !isNaN(id)) {
                                    calculatePrice(parseInt(id));
                                }
                            }
                        });
                    } else {
                        alert(response.error || '<?php echo addslashes(__t('admin_price_per_km_update_error')); ?>');
                    }
                },
                error: function() {
                    alert('<?php echo addslashes(__t('admin_price_per_km_update_error')); ?>');
                }
            });
        }

        // Auto-calculate price function
        function calculatePrice(priceId) {
            const startArea = document.getElementById('start_area_' + priceId);
            const endArea = document.getElementById('end_area_' + priceId);
            const autoPriceInput = document.getElementById('auto_price_' + priceId);
            const manualPriceInput = document.getElementById('price_' + priceId);
            
            if (!startArea || !endArea || !autoPriceInput || !startArea.value || !endArea.value) {
                return;
            }
            
            const startCity = getCityNameFromSelect(startArea);
            const endCity = getCityNameFromSelect(endArea);
            
            if (!startCity || !endCity) {
                // If cities are not in our matrix, clear auto-calculated price
                autoPriceInput.value = '';
                return;
            }
            
            // Get distance from matrix
            const distance = distanceMatrix[startCity] && distanceMatrix[startCity][endCity];
            
            if (distance !== undefined && distance !== null) {
                // Get price per km from settings
                const pricePerKm = getPricePerKm();
                
                // Calculate price: distance * price_per_km
                const calculatedPrice = distance * pricePerKm;
                
                // Round to 2 decimal places
                const roundedPrice = Math.round(calculatedPrice * 100) / 100;
                
                // Update auto-calculated price (read-only)
                autoPriceInput.value = roundedPrice;
                
                // If manual price is empty, also set it to calculated price
                if (!manualPriceInput.value || manualPriceInput.value == '0' || manualPriceInput.value == '') {
                    manualPriceInput.value = roundedPrice;
                    // Trigger update to save the calculated price
                    updateData(manualPriceInput, priceId, 'price', 'price_table', 'price_id');
                }
            } else {
                autoPriceInput.value = '';
            }
        }

        // Auto-calculate price for modal form
        function calculatePriceModal() {
            const startArea = document.getElementById('start_area');
            const endArea = document.getElementById('end_area');
            const autoPriceInput = document.getElementById('auto_price');
            const manualPriceInput = document.getElementById('price');
            
            if (!startArea || !endArea || !autoPriceInput || !startArea.value || !endArea.value) {
                return;
            }
            
            const startCity = getCityNameFromSelect(startArea);
            const endCity = getCityNameFromSelect(endArea);
            
            if (!startCity || !endCity) {
                // If cities are not in our matrix, clear auto-calculated price
                autoPriceInput.value = '';
                return;
            }
            
            // Get distance from matrix
            const distance = distanceMatrix[startCity] && distanceMatrix[startCity][endCity];
            
            if (distance !== undefined && distance !== null) {
                // Get price per km from settings
                const pricePerKm = getPricePerKm();
                
                // Calculate price: distance * price_per_km
                const calculatedPrice = distance * pricePerKm;
                
                // Round to 2 decimal places
                const roundedPrice = Math.round(calculatedPrice * 100) / 100;
                
                // Update auto-calculated price (read-only)
                autoPriceInput.value = roundedPrice;
                
                // Auto-fill manual price with calculated value if empty
                if (!manualPriceInput.value || manualPriceInput.value == '0' || manualPriceInput.value == '') {
                    manualPriceInput.value = roundedPrice;
                }
            } else {
                autoPriceInput.value = '';
            }
        }
        
        // Calculate prices on page load for existing items
        $(document).ready(function() {
            // Calculate prices for existing items after a short delay to ensure DOM is ready
            setTimeout(function() {
                $('.price-item').each(function() {
                    const priceId = $(this).find('.editable-select').first().attr('id');
                    if (priceId) {
                        const id = priceId.replace('start_area_', '').replace('end_area_', '');
                        if (id && !isNaN(id)) {
                            calculatePrice(parseInt(id));
                        }
                    }
                });
            }, 500);
        });
    </script>
</body>

</html>
