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
                <h3>Employee</h3>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Employee List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-12">
                        <div class="row">

                            <?php
                            if (isset($_REQUEST['emp_id'])) {
                                $getall = getemployeeByID($_REQUEST['emp_id']);
                                $row = mysqli_fetch_assoc($getall);
                                $emp_id = $row['emp_id']; ?>


                                <div class="form-group mt-2">
                                    <label for="inputName">Name</label>
                                    <input id="inputName" type="text" name="name" data-parsley-trigger="change"
                                        onchange="updateData(this, '<?php echo $emp_id; ?>', 'name', 'employee', 'emp_id');"
                                        value="<?php echo $row['name']; ?>" required="" placeholder="Enter Full Name"
                                        autocomplete="off" class="form-control">
                                </div>

                                <div class="form-group mt-2">
                                    <label for="inputEmail">Email address</label>
                                    <input id="inputEmail" type="email" name="email" data-parsley-trigger="change"
                                        onchange="updateData(this, '<?php echo $emp_id; ?>', 'email', 'employee', 'emp_id');"
                                        value="<?php echo $row['email']; ?>" required="" placeholder="Enter email"
                                        autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputPhone">Phone Number</label>
                                    <input id="inputPhone" type="text" name="phone" data-parsley-trigger="change"
                                        required="" placeholder="Enter Phone Number" autocomplete="off"
                                        onchange="updateData(this, '<?php echo $emp_id; ?>', 'phone', 'employee', 'emp_id');"
                                        value="<?php echo $row['phone']; ?>" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputNIC">NIC</label>
                                    <input id="inputNIC" type="text" name="nic" data-parsley-trigger="change"
                                        onchange="updateData(this, '<?php echo $emp_id; ?>', 'nic', 'employee', 'emp_id');"
                                        value="<?php echo $row['nic']; ?>" required="" placeholder="Enter NIC Number"
                                        autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputNIC">Branch</label>
                                    <select
                                        onchange='updateData(this, "<?php echo $emp_id; ?>","branch_id", "employee", "emp_id")'
                                        id="branch_id <?php echo $emp_id; ?>" class='form-control norad tx12'
                                        name="branch_id" type='text'>
                                        <?php
                                        $getallCat = getAllBranch();
                                        while ($row2 = mysqli_fetch_assoc($getallCat)) { ?>

                                            <option value="<?php echo $row2['branch_id']; ?>" <?php if ($row['branch_id'] == $row2['branch_id'])
                                                   echo "selected"; ?>>
                                                <?php echo $row2['branch_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputAddress">Address</label>
                                    <input id="inputAddress" type="text" name="address" data-parsley-trigger="change"
                                        onchange="updateData(this, '<?php echo $emp_id; ?>', 'address', 'employee', 'emp_id');"
                                        value="<?php echo $row['address']; ?>" required="" placeholder="Enter Address"
                                        autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="inputGender">Gender</label>
                                    <select
                                        onchange='updateData(this, "<?php echo $emp_id; ?>","gender", "employee", "emp_id")'
                                        id="gender <?php echo $emp_id; ?>" class='form-control norad tx12' name="gender"
                                        type='text'>
                                        <option value="1" <?php if ($row['gender'] == "1")
                                            echo "selected"; ?>>
                                            Male</option>
                                        <option value="0" <?php if ($row['gender'] == "0")
                                            echo "selected"; ?>>
                                            Female</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <div class="row mt-2">
                                        <a href="employee.php" class="btn btn-info">Back</a>
                                    </div>
                                </div>

                            <?php } ?>
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