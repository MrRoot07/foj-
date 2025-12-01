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
                <h3><?php __e('admin_settings'); ?></h3>
                <p class="text-muted"><?php __e('admin_settings_desc'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <?php
                        // Get current user information
                        if (isset($_SESSION['admin'])) {
                            $getEmployee = getemployeeByEmail($_SESSION['admin']);
                            $employee = mysqli_fetch_assoc($getEmployee);
                            
                            if ($employee) {
                                // Get branch name
                                $getBranch = getBranchByID($employee['branch_id']);
                                $branchRow = mysqli_fetch_assoc($getBranch);
                                $branch_name = $branchRow ? $branchRow['branch_name'] : 'N/A';
                                ?>
                                
                                <!-- Profile Information Card -->
                                <div class="card" style="margin-bottom: 24px;">
                                    <div class="card-body">
                                        <div class="settings-section">
                                            <div class="section-header">
                                                <h4><i class="bi bi-person-circle"></i> <?php __e('admin_profile_info'); ?></h4>
                                                <p class="text-muted"><?php __e('admin_profile_info_desc'); ?></p>
                                            </div>
                                            
                                            <div class="profile-info-grid">
                                                <div class="info-item">
                                                    <label><?php __e('admin_full_name'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($employee['name']); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_email'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($employee['email']); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_phone'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($employee['phone']); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_nic'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($employee['nic']); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_branch'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($branch_name); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_gender'); ?></label>
                                                    <div class="info-value">
                                                        <?php 
                                                        if ($employee['gender'] == "1") {
                                                            __e('admin_male');
                                                        } else {
                                                            __e('admin_female');
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_address'); ?></label>
                                                    <div class="info-value"><?php echo htmlspecialchars($employee['address']); ?></div>
                                                </div>
                                                
                                                <div class="info-item">
                                                    <label><?php __e('admin_role'); ?></label>
                                                    <div class="info-value">
                                                        <?php if (isAdmin()): ?>
                                                            <span class="badge badge-admin"><?php __e('admin_role_admin'); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge badge-employee"><?php __e('admin_role_employee'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Change Password Card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="settings-section">
                                            <div class="section-header">
                                                <h4><i class="bi bi-shield-lock"></i> <?php __e('admin_change_password'); ?></h4>
                                                <p class="text-muted"><?php __e('admin_change_password_desc'); ?></p>
                                            </div>
                                            
                                            <form method="POST" id="changePasswordForm" class="password-form">
                                                <input type="hidden" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>">
                                                
                                                <div class="form-field">
                                                    <label for="current_password"><?php __e('admin_current_password'); ?></label>
                                                    <div class="input-wrapper">
                                                        <input type="password" 
                                                               id="current_password" 
                                                               name="current_password" 
                                                               class="form-control" 
                                                               placeholder="<?php __e('admin_enter_current_password'); ?>" 
                                                               required>
                                                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                                            <i class="bi bi-eye" id="eye-current_password"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-field">
                                                    <label for="new_password"><?php __e('admin_new_password'); ?></label>
                                                    <div class="input-wrapper">
                                                        <input type="password" 
                                                               id="new_password" 
                                                               name="new_password" 
                                                               class="form-control" 
                                                               placeholder="<?php __e('admin_enter_new_password'); ?>" 
                                                               required>
                                                        <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                                            <i class="bi bi-eye" id="eye-new_password"></i>
                                                        </button>
                                                    </div>
                                                    <small class="form-text"><?php __e('admin_password_requirements'); ?></small>
                                                </div>
                                                
                                                <div class="form-field">
                                                    <label for="confirm_new_password"><?php __e('admin_confirm_password'); ?></label>
                                                    <div class="input-wrapper">
                                                        <input type="password" 
                                                               id="confirm_new_password" 
                                                               name="confirm_new_password" 
                                                               class="form-control" 
                                                               placeholder="<?php __e('admin_confirm_new_password'); ?>" 
                                                               required>
                                                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_new_password')">
                                                            <i class="bi bi-eye" id="eye-confirm_new_password"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-actions">
                                                    <button type="button" onclick="changePasswordAdmin(this.form)" class="btn btn-primary">
                                                        <i class="bi bi-check-circle"></i> <?php __e('admin_save_password'); ?>
                                                    </button>
                                                    <button type="reset" class="btn btn-secondary">
                                                        <i class="bi bi-x-circle"></i> <?php __e('cancel'); ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                            } else {
                                echo '<div class="alert alert-danger">Employee information not found.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">Please log in to access settings.</div>';
                        }
                        ?>
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

        .settings-section {
            padding: 0;
        }

        .section-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header h4 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 600;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header h4 i {
            color: #667eea;
            font-size: 24px;
        }

        .section-header p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-item label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item .info-value {
            font-size: 15px;
            font-weight: 500;
            color: #212529;
            padding: 10px 14px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-admin {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .badge-employee {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .password-form {
            max-width: 600px;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 12px 45px 12px 14px;
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

        .toggle-password {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .toggle-password i {
            font-size: 18px;
        }

        .form-text {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #6c757d;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .profile-info-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
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
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-' + inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
