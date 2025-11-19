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
                <h3>Customer Register</h3>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Courier Register</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-6">
                        <div class="row">

                            <form action="" method="post" id="basicform" data-parsley-validate="">
                                <div class="form-group mt-2">
                                    <label for="inputName">Name</label>
                                    <input id="inputName" type="text" name="name" data-parsley-trigger="change"
                                        required="" placeholder="Enter Full Name" autocomplete="off"
                                        class="form-control">
                                </div>

                                <div class="form-group mt-2">
                                    <label for="inputEmail">Email address</label>
                                    <input id="inputEmail" type="email" name="email" data-parsley-trigger="change"
                                        required="" placeholder="Enter email" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputPhone">Phone Number</label>
                                    <input id="inputPhone" type="text" name="phone" data-parsley-trigger="change"
                                        required="" placeholder="Enter Phone Number" autocomplete="off"
                                        class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputNIC">NIC</label>
                                    <input id="inputNIC" type="text" name="nic" data-parsley-trigger="change"
                                        required="" placeholder="Enter NIC Number" autocomplete="off"
                                        class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputAddress">Address</label>
                                    <input id="inputAddress" type="text" name="address" data-parsley-trigger="change"
                                        required="" placeholder="Enter Address" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputGender">Gender</label>
                                    <select class="form-select" name="gender" id="gender"
                                        aria-label="Default select example">
                                        <option value="1" selected>Male</option>
                                        <option value="0">Female</option>
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputPassword">Password</label>
                                    <input id="inputPassword" type="password" name="password" placeholder="Password"
                                        required="" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputRepeatPassword">Repeat Password</label>
                                    <input id="inputRepeatPassword" data-parsley-equalto="#inputPassword"
                                        type="password" required="" name="conf_password" placeholder="Password"
                                        class="form-control">
                                </div>
                                <button type="button" onclick="addCustomerAdmin(this.form)" name="submit"
                                    class="btn btn-primary">Save changes</button>
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