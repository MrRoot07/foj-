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
                <h3><?php __e('admin_employees_title'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_employees'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'admin'): ?>
                                <div class="employee-header">
                                    <button class="btn-add-employee" data-bs-toggle="modal" data-bs-target="#EmployeeModal">
                                        <i class="bi bi-plus-circle"></i> <?php __e('admin_add_new_employee'); ?>
                                    </button>
                                </div>
                                <?php endif; ?>

                                <div class="search-container">
                                    <div class="search-wrapper">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" 
                                               id="employeeSearch" 
                                               class="search-input" 
                                               placeholder="<?php __e('admin_search_employees'); ?>">
                                        <button type="button" class="search-clear" id="clearEmployeeSearch" style="display: none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="search-results-info" id="employeeSearchResultsInfo" style="display: none;">
                                        <span id="employeeResultsCount">0</span> <?php __e('admin_employees_found'); ?>
                                    </div>
                                </div>

                                <div class="employees-list">
                                    <?php
                                    if ($_SESSION['admin'] != 'admin') {
                                        $getall = getemployeeByEmail($_SESSION['admin']);
                                    } else {
                                        $getall = getAllemployee();
                                    }
                                    
                                    $has_employees = false;

                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $has_employees = true;
                                        $emp_id = $row['emp_id'];
                                        
                                        // Get branch name
                                        $getCat = getBranchByID($row['branch_id']);
                                        $branchRow = mysqli_fetch_assoc($getCat);
                                        $branch_name = $branchRow ? $branchRow['branch_name'] : 'N/A';
                                        ?>
                                        <div class="employee-list-item"
                                             data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
                                             data-email="<?php echo strtolower(htmlspecialchars($row['email'])); ?>"
                                             data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                             data-branch="<?php echo strtolower(htmlspecialchars($branch_name)); ?>">
                                            <div class="employee-item-header">
                                                <div class="employee-id-section">
                                                    <?php if ($row['active'] == 1): ?>
                                                        <span class="badge-status active"><?php __e('admin_active'); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge-status inactive"><?php __e('admin_inactive'); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="employee-item-body">
                                                <div class="employee-fields-grid">
                                                    <div class="employee-field">
                                                        <label><?php __e('admin_full_name'); ?></label>
                                                        <input type="text" 
                                                               id="name_<?php echo $emp_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['name']); ?>" 
                                                               onchange="updateData(this, '<?php echo $emp_id; ?>', 'name', 'employee', 'emp_id')">
                                                    </div>

                                                    <div class="employee-field">
                                                        <label><?php __e('admin_email'); ?></label>
                                                        <input type="email" 
                                                               id="email_<?php echo $emp_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['email']); ?>" 
                                                               onchange="updateData(this, '<?php echo $emp_id; ?>', 'email', 'employee', 'emp_id')">
                                                    </div>

                                                    <div class="employee-field">
                                                        <label><?php __e('admin_phone'); ?></label>
                                                        <input type="text" 
                                                               id="phone_<?php echo $emp_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['phone']); ?>" 
                                                               onchange="updateData(this, '<?php echo $emp_id; ?>', 'phone', 'employee', 'emp_id')">
                                                    </div>

                                                    <div class="employee-field">
                                                        <label><?php __e('admin_nic'); ?></label>
                                                        <input type="text" 
                                                               id="nic_<?php echo $emp_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['nic']); ?>" 
                                                               onchange="updateData(this, '<?php echo $emp_id; ?>', 'nic', 'employee', 'emp_id')">
                                                    </div>

                                                    <div class="employee-field">
                                                        <label><?php __e('admin_gender'); ?></label>
                                                        <select id="gender_<?php echo $emp_id; ?>"
                                                                class="form-control editable-input" 
                                                                onchange="updateData(this, '<?php echo $emp_id; ?>', 'gender', 'employee', 'emp_id')">
                                                            <option value="1" <?php echo ($row['gender'] == "1") ? "selected" : ""; ?>><?php __e('admin_male'); ?></option>
                                                            <option value="0" <?php echo ($row['gender'] == "0" || $row['gender'] == "2") ? "selected" : ""; ?>><?php __e('admin_female'); ?></option>
                                                        </select>
                                                    </div>

                                                    <div class="employee-field">
                                                        <label><?php __e('admin_branch'); ?></label>
                                                        <select id="branch_id_<?php echo $emp_id; ?>"
                                                                class="form-control editable-input" 
                                                                onchange="updateData(this, '<?php echo $emp_id; ?>', 'branch_id', 'employee', 'emp_id')">
                                                            <?php
                                                            $getallBranches = getAllBranch();
                                                            while ($branchRow = mysqli_fetch_assoc($getallBranches)) { ?>
                                                                <option value="<?php echo $branchRow['branch_id']; ?>" 
                                                                        <?php if ($row['branch_id'] == $branchRow['branch_id']) echo "selected"; ?>>
                                                                    <?php echo $branchRow['branch_name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="employee-field full-width">
                                                        <label><?php __e('admin_address'); ?></label>
                                                        <input type="text" 
                                                               id="address_<?php echo $emp_id; ?>"
                                                               class="form-control editable-input" 
                                                               value="<?php echo htmlspecialchars($row['address']); ?>" 
                                                               onchange="updateData(this, '<?php echo $emp_id; ?>', 'address', 'employee', 'emp_id')"
                                                               placeholder="<?php __e('admin_address'); ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="employee-item-footer">
                                                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'admin'): ?>
                                                    <button type="button"
                                                                   onclick="deleteData(<?php echo $emp_id; ?>, 'employee', 'emp_id')"
                                                                   class="btn-delete" 
                                                                   title="<?php __e('admin_delete_employee'); ?>">
                                                               <i class="bi bi-trash"></i> <?php __e('admin_delete_employee'); ?>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                           if (!$has_employees) {
                                               echo '<div class="empty-state">
                                                   <i class="bi bi-person" style="font-size: 48px; color: #6c757d; margin-bottom: 16px;"></i>
                                                   <h4>' . __t('admin_no_employees') . '</h4>
                                                   <p>' . __t('admin_no_employees_desc') . '</p>
                                               </div>';
                                           }
                                    ?>
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
    <div class="modal fade" id="EmployeeModal" tabindex="-1" aria-labelledby="EmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EmployeeModalLabel"><?php __e('admin_add_new_employee'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php __e('close'); ?>"></button>
                </div>
                <form action="" method="post" id="employeeForm" data-parsley-validate="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-field">
                                <label for="inputName" class="form-label"><?php __e('admin_full_name'); ?></label>
                                <input id="inputName" 
                                       type="text" 
                                       name="name" 
                                       data-parsley-trigger="change" 
                                       required="" 
                                       placeholder="<?php __e('admin_full_name'); ?>" 
                                       autocomplete="off" 
                                       class="form-control">
                            </div>

                            <div class="form-field">
                                <label for="inputEmail" class="form-label"><?php __e('admin_email'); ?></label>
                                <input id="inputEmail" 
                                       type="email" 
                                       name="email" 
                                       data-parsley-trigger="change"
                                       required="" 
                                       placeholder="<?php __e('admin_email'); ?>" 
                                       autocomplete="off" 
                                       class="form-control">
                </div>
                            </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label for="inputPhone" class="form-label"><?php __e('admin_phone'); ?></label>
                                <input id="inputPhone" 
                                       type="text" 
                                       name="phone" 
                                       data-parsley-trigger="change"
                                       required="" 
                                       placeholder="<?php __e('admin_phone'); ?>" 
                                       autocomplete="off"
                                       class="form-control">
                            </div>

                            <div class="form-field">
                                <label for="inputNIC" class="form-label"><?php __e('admin_nic'); ?></label>
                                <input id="inputNIC" 
                                       type="text" 
                                       name="nic" 
                                       data-parsley-trigger="change" 
                                       required=""
                                       placeholder="<?php __e('admin_nic'); ?>" 
                                       autocomplete="off" 
                                    class="form-control">
                            </div>
                            </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label for="branch_id" class="form-label"><?php __e('admin_branch'); ?></label>
                                <select id="branch_id" class="form-control" name="branch_id" required>
                                    <option value=""><?php __e('admin_branch'); ?></option>
                                    <?php 
                                    $getall = getAllBranch();
                                    while ($row = mysqli_fetch_assoc($getall)) { ?>
                                        <option value="<?php echo $row['branch_id']; ?>">
                                            <?php echo $row['branch_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="inputGender" class="form-label"><?php __e('admin_gender'); ?></label>
                                <select class="form-control" name="gender" id="inputGender" required>
                                    <option value="1" selected><?php __e('admin_male'); ?></option>
                                    <option value="0"><?php __e('admin_female'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="inputAddress" class="form-label"><?php __e('admin_address'); ?></label>
                            <input id="inputAddress" 
                                   type="text" 
                                   name="address" 
                                   data-parsley-trigger="change"
                                   required="" 
                                   placeholder="<?php __e('admin_address'); ?>" 
                                   autocomplete="off" 
                                   class="form-control">
                            </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label for="inputPassword" class="form-label"><?php __e('register_password'); ?></label>
                                <input id="inputPassword" 
                                       type="password" 
                                       name="password" 
                                       placeholder="<?php __e('register_password'); ?>"
                                       required="" 
                                       class="form-control">
                            </div>

                            <div class="form-field">
                                <label for="inputRepeatPassword" class="form-label"><?php __e('register_confirm_password'); ?></label>
                                <input id="inputRepeatPassword" 
                                       data-parsley-equalto="#inputPassword" 
                                       type="password"
                                       required="" 
                                       name="conf_password" 
                                       placeholder="<?php __e('register_confirm_password'); ?>" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php __e('cancel'); ?></button>
                        <button type="button" onclick="addEmployee(this.form)" name="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> <?php __e('admin_save_employee'); ?>
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

        .employee-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .btn-add-employee {
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

        .btn-add-employee:hover {
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

        .employee-list-item.hidden {
            display: none;
        }

        .employees-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 16px;
            max-height: calc(100vh - 300px);
            overflow-y: auto;
            padding-right: 8px;
        }

        .employees-list::-webkit-scrollbar {
            width: 8px;
        }

        .employees-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .employees-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .employees-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .employee-list-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .employee-list-item:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            border-color: #d0d0d0;
            transform: translateY(-1px);
        }

        .employee-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 14px;
        }

        .employee-id-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .employee-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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

        .employee-item-body {
            padding: 12px 14px;
            flex: 1;
        }

        .employee-fields-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 12px;
        }

        .employee-field {
            display: flex;
            flex-direction: column;
        }

        .employee-field.full-width {
            grid-column: 1 / -1;
        }

        .employee-field label {
            font-size: 10px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .editable-input {
            width: 100%;
            padding: 8px 10px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 12px;
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

        .employee-item-footer {
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h4 {
            margin: 16px 0 8px;
            color: #495057;
            font-size: 18px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
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

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-field {
            margin-bottom: 16px;
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
            .employees-list {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .employees-list {
                grid-template-columns: 1fr;
                max-height: none;
            }

            .employee-fields-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
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
            const searchInput = $('#employeeSearch');
            const clearButton = $('#clearEmployeeSearch');
            const resultsInfo = $('#employeeSearchResultsInfo');
            const resultsCount = $('#employeeResultsCount');
            const employeeItems = $('.employee-list-item');

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
                filterEmployees('');
            });

            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterEmployees(searchTerm);
            });

            function filterEmployees(searchTerm) {
                let visibleCount = 0;
                if (searchTerm === '') {
                    employeeItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }
                employeeItems.each(function() {
                    const $item = $(this);
                    const name = $item.data('name') || '';
                    const email = $item.data('email') || '';
                    const phone = $item.data('phone') || '';
                    const branch = $item.data('branch') || '';
                    const searchableText = (name + ' ' + email + ' ' + phone + ' ' + branch).toLowerCase();
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