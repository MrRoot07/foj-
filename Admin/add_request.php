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
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Courier Register</li>
                            <li class="breadcrumb-item active" aria-current="page">Courier Request</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">

                            <form action="#" class="p-5 bg-white" method="post">


                                <h4>Sending Details</h4>
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="fname">Customer</label>
                                                <select id="customer_id" class='form-control norad tx12'
                                                    name="customer_id" type='text'>
                                                    <option>Please Select</option>
                                                    <?php $getall = getAllcustomers();
                                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                        <option value="<?php echo $row['customer_id'] ?>">
                                                            <?php echo $row['name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="send_phone">Phone Number</label>
                                                <input type="text" name="sender_phone" id="sender_phone"
                                                    class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="weight">Weight</label>
                                                <input type="number" name="weight" id="weight" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="fname">Sending Location</label>
                                                <select id="send_location" class='form-control norad tx12'
                                                    name="send_location" type='text'>
                                                    <option>Please Select</option>
                                                    <?php $getall = getAllArea();
                                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                        <option value="<?php echo $row['area_id'] ?>">
                                                            <?php echo $row['area_name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="fname">Pick Up Location</label>
                                                <select id="end_location" onchange="calculationAdmin(this)"
                                                    class='form-control norad tx12' name="end_location" type='text'>
                                                    <option>Please Select</option>
                                                    <?php $getall = getAllArea();
                                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                                        <option value="<?php echo $row['area_id'] ?>">
                                                            <?php echo $row['area_name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-5">
                                        <div class="row form-group">

                                            <div class="col-md-12">
                                                <label class="text-black" for="email">Shipping details</label>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        Price :
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" disabled name="total" id="total"
                                                            class="form-control">
                                                        <input type="hidden" name="total_fee" id="total_fee"
                                                            class="form-control">

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="mt-5">Receiver Details</h4>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="res_name">Receiver Name</label>
                                                <input type="text" name="res_name" id="res_name" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <div class="col-md-12 mb-3 mb-md-0">
                                                <label class="text-black" for="res_phone">Phone Number</label>
                                                <input type="text" name="res_phone" id="res_phone" class="form-control">
                                            </div>
                                        </div>


                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-black" for="red_address">Receiver Address</label>
                                        <textarea name="red_address" id="red_address" cols="30" rows="7"
                                            class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <input type="button" onclick="addRequestAdmin(this.form)" value="Send Request"
                                            class="btn btn-primary py-2 px-4 text-white">
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>

                </section>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>