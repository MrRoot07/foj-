<!DOCTYPE html>
<html lang="en">

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
                <h3>Courier Request</h3>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Courier List</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <a href="add_courier.php" class="btn btn-primary"> Register Customer</a>
                </div>
                <div class="col-lg-3">
                    <a href="add_request.php" class="btn btn-primary"> Add
                        Courier Request</a>
                </div>
            </div>
            <div class="page-content">
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" 
                               id="courierSearch" 
                               class="search-input" 
                               placeholder="<?php __e('admin_search_couriers'); ?>">
                        <button type="button" class="search-clear" id="clearCourierSearch" style="display: none;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="search-results-info" id="courierSearchResultsInfo" style="display: none;">
                        <span id="courierResultsCount">0</span> <?php __e('admin_couriers_found'); ?>
                    </div>
                </div>
                <?php
                $getall = getAllTracking();

                while ($row = mysqli_fetch_assoc($getall)) {
                    $request_id = $row['request_id'];
                    ?>
                    <article class="card mt-5 courier-item" 
                             style="border: 2px solid #2c3e50"
                             data-tracking="<?php echo strtolower(htmlspecialchars($row['tracking_code'])); ?>"
                             data-customer="<?php echo strtolower(htmlspecialchars($row['name'] ?? '')); ?>"
                             data-phone="<?php echo htmlspecialchars($row['phone'] ?? ''); ?>">
                        <header class="card-header text-white" style="background-color: #2c3e50; border-radius: 0px;">
                            Orders /
                            Tracking </header>
                        <div class="card-body mt-3">
                            <article class="card">
                                <div class="card-body row">

                                    <div class="col"> <strong>Shipping Address:</strong>
                                        <br><?php echo $row['name']; ?>
                                        <br><?php echo $row['phone']; ?>
                                        <br><?php echo $row['red_address']; ?>
                                    </div>
                                    <div class="col"> <strong>Recever Mobile:</strong>
                                        <br><?php echo $row['res_phone']; ?>
                                    </div>
                                    <div class="col"> <strong>Current Status:</strong>
                                        <br>
                                        <?php if ($row['tracking_status'] == 1) {
                                            echo 'Order Pending';
                                        } else if ($row['tracking_status'] == 2) {
                                            echo 'Prepare Order';
                                        } else if ($row['tracking_status'] == 3) {
                                            echo 'Shipped Order';
                                        } else if ($row['tracking_status'] == 4) {
                                            echo 'Deliverd';
                                        } else if ($row['tracking_status'] == 5) {
                                            echo 'Canceled';
                                        } ?>
                                    </div>
                                    <div class="col"> <strong>Requested Date:</strong>
                                        <br><?php echo $row['date_updated']; ?>
                                    </div>
                                </div>
                                <div class="card-body row">

                                    <div class="col"> <strong>Weight:</strong>
                                        <br><?php echo $row['weight']; ?>
                                    </div>
                                    <div class="col"> <strong>Sender Mobile:</strong>
                                        <br><?php echo $row['sender_phone']; ?>
                                    </div>
                                    <div class="col"> <strong>Send Location</strong>
                                        <br>
                                        <?php
                                        $getLocation = getAllAreabyID($row['send_location']);
                                        $row2 = mysqli_fetch_assoc($getLocation);
                                        echo $row2['area_name'];
                                        ?>
                                    </div>
                                    <div class="col"> <strong>End Location</strong>
                                        <br>
                                        <?php
                                        $getLocation = getAllAreabyID($row['end_location']);
                                        $row2 = mysqli_fetch_assoc($getLocation);
                                        echo $row2['area_name'];
                                        ?>
                                    </div>
                                </div>
                            </article>
                            <?php if ($row['tracking_status'] != 5) { ?>
                                <div class="track">

                                    <div class="step <?php if ($row['tracking_status'] == 1 || $row['tracking_status'] == 2 || $row['tracking_status'] == 3 || $row['tracking_status'] == 4) {
                                        echo 'active';
                                    } ?>">
                                        <span class="icon"> <i class="fa fa-check"></i> </span>
                                        <span class="text">Order confirmed</span>
                                    </div>
                                    <div class="step <?php if ($row['tracking_status'] == 2 || $row['tracking_status'] == 3 || $row['tracking_status'] == 4) {
                                        echo 'active';
                                    } ?>">
                                        <span class="icon"> <i class="fa fa-user"></i> </span>
                                        <span class="text">Prepare Order</span>
                                    </div>
                                    <div class="step <?php if ($row['tracking_status'] == 3 || $row['tracking_status'] == 4) {
                                        echo 'active';
                                    } ?>">
                                        <span class="icon"> <i class="fa fa-truck"></i> </span>
                                        <span class="text"> Shipped Order </span>
                                    </div>
                                    <div class="step <?php if ($row['tracking_status'] == 4) {
                                        echo 'active';
                                    } ?>">
                                        <span class="icon"> <i class="fa fa-box"></i> </span>
                                        <span class="text">Deliverd</span>
                                    </div>
                                </div>
                            <?php } ?>
                            <hr>
                            <div class="row">


                                <div class="col-md-5">
                                    <label for="tracking_status" class="form-label">Order Status</label>
                                    <select
                                        onchange='updateData(this, "<?php echo $request_id; ?>","tracking_status", "request", "request_id")'
                                        id="tracking_status <?php echo $request_id; ?>" class='form-control norad tx12'
                                        name="tracking_status" type='text'>
                                        <option value="1" <?php if ($row['tracking_status'] == "1")
                                            echo "selected"; ?>>
                                            Order Pending
                                        </option>
                                        <option value="2" <?php if ($row['tracking_status'] == "2")
                                            echo "selected"; ?>>
                                            Prepare Order
                                        </option>
                                        <option value="3" <?php if ($row['tracking_status'] == "3")
                                            echo "selected"; ?>>
                                            Shipped Order
                                        </option>
                                        <option value="4" <?php if ($row['tracking_status'] == "4")
                                            echo "selected"; ?>>
                                            Deliverd
                                        </option>
                                        <option value="5" <?php if ($row['tracking_status'] == "5")
                                            echo "selected"; ?>>
                                            Canceled
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="tracking_status" class="form-label">Order Delete : </label>
                                    <button type="button"
                                        onclick="deleteData(<?php echo $row['request_id']; ?>,'request', 'request_id')"
                                        class="btn btn-darkblue"> <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>


    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const searchInput = $('#courierSearch');
            const clearButton = $('#clearCourierSearch');
            const resultsInfo = $('#courierSearchResultsInfo');
            const resultsCount = $('#courierResultsCount');
            const courierItems = $('.courier-item');

            searchInput.on('input', function() {
                if ($(this).val().length > 0) {
                    clearButton.show();
                } else {
                    clearButton.hide();
                    resultsInfo.hide();
                }
            });

            clearButton.on('click', function() {
                searchInput.val('');
                $(this).hide();
                resultsInfo.hide();
                filterCouriers('');
            });

            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterCouriers(searchTerm);
            });

            function filterCouriers(searchTerm) {
                let visibleCount = 0;
                if (searchTerm === '') {
                    courierItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }
                courierItems.each(function() {
                    const $item = $(this);
                    const tracking = $item.data('tracking') || '';
                    const customer = $item.data('customer') || '';
                    const phone = $item.data('phone') || '';
                    const searchableText = (tracking + ' ' + customer + ' ' + phone).toLowerCase();
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
<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

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

    .courier-item.hidden {
        display: none;
    }

    .card {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.10rem
    }

    .card-header:first-child {
        border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1)
    }

    .track {
        position: relative;
        background-color: #ddd;
        height: 7px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 60px;
        margin-top: 50px
    }

    .track .step {
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: 25%;
        margin-top: -18px;
        text-align: center;
        position: relative
    }

    .track .step.active:before {
        background: #2c3e50
    }

    .track .step::before {
        height: 7px;
        position: absolute;
        content: "";
        width: 100%;
        left: 0;
        top: 18px
    }

    .track .step.active .icon {
        background: #2c3e50;
        color: #fff
    }

    .track .icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        position: relative;
        border-radius: 100%;
        background: #ddd
    }

    .track .step.active .text {
        font-weight: 400;
        color: #000
    }

    .track .text {
        display: block;
        margin-top: 7px
    }

    .itemside {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 100%
    }

    .itemside .aside {
        position: relative;
        -ms-flex-negative: 0;
        flex-shrink: 0
    }

    .img-sm {
        width: 80px;
        height: 80px;
        padding: 7px
    }

    ul.row,
    ul.row-sm {
        list-style: none;
        padding: 0
    }

    .itemside .info {
        padding-left: 15px;
        padding-right: 7px
    }

    .itemside .title {
        display: block;
        margin-bottom: 5px;
        color: #212529
    }

    p {
        margin-top: 0;
        margin-bottom: 1rem
    }

    .btn-warning {
        color: #ffffff;
        background-color: #2c3e50;
        border-color: #2c3e50;
        border-radius: 1px
    }

    .btn-warning:hover {
        color: #ffffff;
        background-color: #ff2b00;
        border-color: #ff2b00;
        border-radius: 1px
    }
</style>

</html>

