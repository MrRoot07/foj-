<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/bootstrap/i18n.php';
include 'pages/head.php';
include 'auth.php';
include 'conf.php';

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit;
}

$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();

// Get customer data
$getall = getAllcustomerById($_SESSION['customer_id']);
$row = mysqli_fetch_assoc($getall);
$customer_id = $row['customer_id'];
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php __e('profile_title'); ?> · <?php echo $companyName; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="css/rtl.css">
    <?php endif; ?>
    <style>
        :root {
            --bg: #ffffff;
            --panel: #f7f9fc;
            --muted: #556070;
            --text: #0b0d13;
            --brand: #2563eb;
            --brand-2: #06b6d4;
            --ok: #10b981;
            --warn: #f59e0b;
            --danger: #ef4444;
            --ring: 0 0 0 3px rgba(37, 99, 235, .25);
            --radius: 14px;
            --shadow: 0 8px 24px rgba(0, 0, 0, .12), 0 2px 8px rgba(0, 0, 0, .08);
            --shadow-soft: 0 6px 18px rgba(0, 0, 0, .08), inset 0 1px 0 rgba(255, 255, 255, .6);
            --grid-max: 1200px;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif
        }

        a {
            color: inherit;
            text-decoration: none
        }

        .container {
            max-width: var(--grid-max);
            margin: 0 auto;
            padding: 0 20px
        }

        main {
            padding: 40px 0;
            min-height: calc(100vh - 64px);
        }

        .profile-card {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            padding: 32px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px
        }

        p.lead {
            margin: 0 0 24px;
            color: var(--muted)
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 24px 0 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(0, 0, 0, .08);
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px
        }

        .field.full-width {
            grid-column: 1 / -1;
        }

        .field label {
            font-weight: 500;
            font-size: 14px;
            color: var(--text);
        }

        .field input,
        .field select {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none;
            font-family: inherit;
            font-size: 14px;
        }

        .field input:focus,
        .field select:focus {
            box-shadow: var(--ring);
            border-color: transparent
        }

        .field input:disabled {
            background: rgba(0, 0, 0, .04);
            cursor: not-allowed;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, .12);
            padding: 10px 14px;
            border-radius: 12px;
            background: transparent;
            color: var(--text);
            font-weight: 600;
            transition: .2s ease;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            border-color: rgba(0, 0, 0, .22)
        }

        .btn.primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            border: none;
            color: white;
            box-shadow: var(--shadow)
        }

        .btn.danger {
            background: var(--danger);
            border: none;
            color: white;
        }

        .btn.danger:hover {
            background: #dc2626;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(0, 0, 0, .08);
        }

        .alert {
            background: #fff3cd;
            border: 1px solid #ffe69c;
            color: #664d03;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .alert.success {
            background: rgba(16, 185, 129, .1);
            border-color: var(--ok);
            color: #065f46;
        }

        .alert.error {
            background: rgba(239, 68, 68, .1);
            border-color: var(--danger);
            color: #7a0c0c;
        }

        .address-section {
            margin-top: 24px;
            padding: 20px;
            background: rgba(37, 99, 235, 0.03);
            border: 1px solid rgba(37, 99, 235, 0.1);
            border-radius: 12px;
        }

        .address-section h3 {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .address-section h3::before {
            content: "📍";
            font-size: 18px;
        }

        .address-section .grid {
            margin-top: 0;
        }

        .password-section {
            margin-top: 24px;
            padding: 20px;
            background: rgba(37, 99, 235, 0.03);
            border: 1px solid rgba(37, 99, 235, 0.1);
            border-radius: 12px;
        }

        .password-section h3 {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
        }

        .password-requirements {
            margin-top: 12px;
            padding: 0;
            background: transparent;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0;
            font-size: 12px;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        @media (max-width: 720px) {
            .password-requirements {
                grid-template-columns: 1fr;
            }
        }

        .requirement.met {
            color: #28a745;
        }

        .req-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            font-size: 12px;
            font-weight: normal;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .requirement.met .req-icon {
            background: #28a745;
            color: #ffffff;
        }

        .requirement.met .req-icon::before {
            content: "✓";
            font-size: 14px;
            font-weight: bold;
        }

        .requirement:not(.met) .req-icon::before {
            content: "";
        }

        .requirement:not(.met) .req-icon {
            border: 2px solid #dee2e6;
            background: #ffffff;
        }
    </style>
</head>

<body>
    <?php include 'pages/header.php'; ?>

    <main>
        <div class="container">
            <div class="profile-card">
                <h1><?php __e('profile_title'); ?></h1>
                <p class="lead"><?php __e('profile_edit_title'); ?></p>

                <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert <?php echo strpos($_SESSION['status'], 'success') !== false ? 'success' : ''; ?>">
                        <?= $_SESSION['status'];
                        unset($_SESSION['status']); ?>
                </div>
                <?php endif; ?>

                <form id="profileForm" method="POST" action="#" onsubmit="event.preventDefault();">
                    <div class="section-title"><?php __e('profile_personal_info'); ?></div>
                    <div class="grid">
                        <div class="field">
                            <label for="name"><?php __e('profile_name_label'); ?></label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" 
                                   onchange='updateProfileField(this, "name")' required>
                </div>
                        <div class="field">
                            <label for="phone"><?php __e('profile_mobile_label'); ?></label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" 
                                   onchange='updateProfileField(this, "phone")' required>
            </div>
                        <div class="field">
                            <label for="email"><?php __e('profile_email_label'); ?></label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" 
                                   disabled style="opacity: 0.6;">
                            <small style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php __e('profile_email_readonly'); ?></small>
                </div>
                        <div class="field">
                            <label for="nic"><?php __e('profile_nic_label'); ?></label>
                            <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($row['nic']); ?>" 
                                   onchange='updateProfileField(this, "nic")' required>
            </div>
                        <div class="field">
                            <label for="gender"><?php __e('profile_gender_label'); ?></label>
                            <select id="gender" name="gender" onchange='updateProfileField(this, "gender")' required>
                                <option value="1" <?php echo $row['gender'] == "1" ? "selected" : ""; ?>><?php __e('register_gender_male'); ?></option>
                                <option value="2" <?php echo $row['gender'] == "2" ? "selected" : ""; ?>><?php __e('register_gender_female'); ?></option>
                            </select>
        </div>
    </div>


                    <div class="address-section">
                        <h3><?php __e('register_address_info'); ?></h3>
                        <div class="grid">
                            <div class="field">
                                <label for="street"><?php __e('register_street'); ?></label>
                                <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($row['street'] ?? ''); ?>" 
                                       onchange='updateProfileField(this, "street")' required>
                </div>
                            <div class="field">
                                <label for="city"><?php __e('register_city'); ?></label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($row['city'] ?? ''); ?>" 
                                       onchange='updateProfileField(this, "city")' required>
            </div>
                            <div class="field">
                                <label for="state"><?php __e('register_state'); ?></label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($row['state'] ?? ''); ?>" 
                                       onchange='updateProfileField(this, "state")' required>
                                    </div>
                            <div class="field">
                                <label for="zip_code"><?php __e('register_zip'); ?></label>
                                <input type="text" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($row['zip_code'] ?? ''); ?>" 
                                       onchange='updateProfileField(this, "zip_code")' required>
                                </div>
                            <div class="field full-width">
                                <label for="additional_address"><?php __e('register_additional'); ?></label>
                                <input type="text" id="additional_address" name="additional_address" value="<?php echo htmlspecialchars($row['additional_address'] ?? ''); ?>" 
                                       onchange='updateProfileField(this, "additional_address")' placeholder="Apartment, suite, unit, building, floor, etc.">
                                    </div>
                            <div class="field full-width">
                                <label for="address"><?php __e('profile_address_label'); ?> (<?php __e('profile_address_readonly'); ?>)</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" 
                                       disabled style="opacity: 0.6;">
                                <small style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php __e('profile_address_auto'); ?></small>
                                    </div>
                                    </div>
                                </div>

                    <div class="password-section">
                        <h3><?php __e('change_password_title'); ?></h3>
                        <div class="grid">
                            <div class="field">
                                <label for="current_password"><?php __e('change_password_current'); ?></label>
                                <input type="password" id="current_password" name="current_password" placeholder="••••••••">
                            </div>
                            <div class="field">
                                <label for="new_password"><?php __e('change_password_new'); ?></label>
                                <input type="password" id="new_password" name="new_password" placeholder="••••••••" oninput="checkPasswordStrength()">
                        </div>
                            <div class="field">
                                <label for="confirm_new_password"><?php __e('change_password_confirm'); ?></label>
                                <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="••••••••">
                                        </div>
                            <div class="field full-width">
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                                    <input type="checkbox" id="show-passwords" onchange="togglePasswords()" style="width: 14px; height: 14px; cursor: pointer;">
                                    <span style="font-size: 13px;"><?php __e('register_show_passwords'); ?></span>
                                </label>
                                            </div>
                            <div class="field full-width">
                                <div class="password-requirements">
                                    <div class="requirement" id="req-length">
                                        <span class="req-icon"></span>
                                        <span class="req-text"><?php __e('register_password_req_length'); ?></span>
                                        </div>
                                    <div class="requirement" id="req-uppercase">
                                        <span class="req-icon"></span>
                                        <span class="req-text"><?php __e('register_password_req_upper'); ?></span>
                                            </div>
                                    <div class="requirement" id="req-lowercase">
                                        <span class="req-icon"></span>
                                        <span class="req-text"><?php __e('register_password_req_lower'); ?></span>
                                            </div>
                                    <div class="requirement" id="req-number">
                                        <span class="req-icon"></span>
                                        <span class="req-text"><?php __e('register_password_req_number'); ?></span>
                                            </div>
                                    <div class="requirement" id="req-special">
                                        <span class="req-icon"></span>
                                        <span class="req-text"><?php __e('register_password_req_special'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="button" class="btn primary" onclick="savePassword()"><?php __e('change_password_save'); ?></button>
                        <a href="orders.php" class="btn"><?php __e('back'); ?></a>
                </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'pages/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateProfileField(element, field) {
            const value = element.value;
            const customer_id = <?php echo $customer_id; ?>;
            
            const data = {
                id: customer_id,
                field: field,
                value: value,
                id_fild: 'customer_id',
                table: 'customer'
            };

            $.ajax({
                method: "POST",
                url: "server/api.php?function_code=updateData",
                data: data,
                success: function(response) {
                    showMessage('<?php __e('profile_update_success'); ?>', 'success');
                },
                error: function(error) {
                    showMessage('<?php __e('profile_update_error'); ?>', 'error');
                }
            });
        }

        function savePassword() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_new_password').value;
            const customer_id = <?php echo $customer_id; ?>;

            if (!currentPassword || !newPassword || !confirmPassword) {
                showMessage('<?php __e('change_password_all_required'); ?>', 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                showMessage('<?php __e('error_passwords_match'); ?>', 'error');
                return;
            }

            // Check password strength
            const passwordErrors = [];
            if (newPassword.length < 8) passwordErrors.push('<?php __e('register_password_req_length'); ?>');
            if (!/[A-Z]/.test(newPassword)) passwordErrors.push('<?php __e('register_password_req_upper'); ?>');
            if (!/[a-z]/.test(newPassword)) passwordErrors.push('<?php __e('register_password_req_lower'); ?>');
            if (!/[0-9]/.test(newPassword)) passwordErrors.push('<?php __e('register_password_req_number'); ?>');
            if (!/[\W_]/.test(newPassword)) passwordErrors.push('<?php __e('register_password_req_special'); ?>');

            if (passwordErrors.length > 0) {
                showMessage('<?php __e('change_password_weak'); ?>', 'error');
                return;
            }

            // Check current password
            $.ajax({
                method: "POST",
                url: "server/api.php?function_code=checkPassword",
                data: {
                    password: currentPassword,
                    customer_id: customer_id
                },
                success: function(response) {
                    if (parseInt(response) > 0) {
                        // Hash the new password before updating
                        $.ajax({
                            method: "POST",
                            url: "server/api.php?function_code=updatePassword",
                            data: {
                                customer_id: customer_id,
                                new_password: newPassword
                            },
                            success: function(response) {
                                showMessage('<?php __e('change_password_success'); ?>', 'success');
                                document.getElementById('current_password').value = '';
                                document.getElementById('new_password').value = '';
                                document.getElementById('confirm_new_password').value = '';
                                // Clear password requirements
                                document.querySelectorAll('.requirement').forEach(req => req.classList.remove('met'));
                            },
                            error: function(error) {
                                showMessage('<?php __e('change_password_error'); ?>', 'error');
                            }
                        });
                    } else {
                        showMessage('<?php __e('change_password_wrong_current'); ?>', 'error');
                    }
                },
                error: function(error) {
                    showMessage('<?php __e('change_password_error'); ?>', 'error');
                }
            });
        }

        function togglePasswords() {
            const checkbox = document.getElementById('show-passwords');
            const currentPassword = document.getElementById('current_password');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_new_password');
            
            if (checkbox.checked) {
                currentPassword.type = "text";
                newPassword.type = "text";
                confirmPassword.type = "text";
            } else {
                currentPassword.type = "password";
                newPassword.type = "password";
                confirmPassword.type = "password";
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            
            const requirements = {
                'req-length': password.length >= 8,
                'req-uppercase': /[A-Z]/.test(password),
                'req-lowercase': /[a-z]/.test(password),
                'req-number': /[0-9]/.test(password),
                'req-special': /[\W_]/.test(password)
            };

            Object.keys(requirements).forEach(reqId => {
                const reqElement = document.getElementById(reqId);
                if (requirements[reqId]) {
                    reqElement.classList.add('met');
                } else {
                    reqElement.classList.remove('met');
                }
            });
        }

        function showMessage(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert ' + (type === 'success' ? 'success' : 'error');
            alertDiv.textContent = message;
            
            const profileCard = document.querySelector('.profile-card');
            const firstChild = profileCard.querySelector('h1');
            firstChild.insertAdjacentElement('afterend', alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>

</html>
