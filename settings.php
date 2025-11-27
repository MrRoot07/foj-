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
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}
$getall = getAllcustomerById($_SESSION['customer_id']);
if (!$getall || !($row = mysqli_fetch_assoc($getall))) {
    header("Location: login.php");
    exit;
}
$customer_id = $row['customer_id'];
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php __e('settings_title'); ?> · <?php echo $companyName; ?></title>
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

        .settings-card {
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

        .settings-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 2px solid rgba(0, 0, 0, .08);
        }

        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(0, 0, 0, .08);
        }

        .section-title i {
            font-size: 20px;
            color: var(--brand);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .settings-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .settings-field.full-width {
            grid-column: 1 / -1;
        }

        .settings-field label {
            font-weight: 500;
            font-size: 14px;
            color: var(--text);
        }

        .settings-field input,
        .settings-field select {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none;
            font-family: inherit;
            font-size: 14px;
        }

        .settings-field input:focus,
        .settings-field select:focus {
            box-shadow: var(--ring);
            border-color: transparent
        }

        .settings-field input:disabled {
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

        .settings-actions {
            margin-top: 20px;
            display: flex;
            gap: 12px;
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

        .settings-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(37, 99, 235, 0.03);
            border-radius: 8px;
            font-size: 14px;
        }

        .info-item i {
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'pages/header.php'; ?>

    <main>
        <div class="container">
            <div class="settings-card">
                <h1><?php __e('settings_title'); ?></h1>
                <p class="lead"><?php __e('settings_desc'); ?></p>

                <div id="messageContainer"></div>

                <!-- Account Information Section -->
                <div class="settings-section">
                    <h4 class="section-title">
                        <i class="bi bi-person-circle"></i> <?php __e('settings_account_info'); ?>
                    </h4>
                    <div class="settings-grid">
                        <div class="settings-field">
                            <label><?php __e('profile_name_label'); ?></label>
                            <input type="text" 
                                   id="name" 
                                   value="<?php echo htmlspecialchars($row['name']); ?>" 
                                   onchange="updateProfileField(this, 'name')" 
                                   required>
                        </div>
                        <div class="settings-field">
                            <label><?php __e('profile_mobile_label'); ?></label>
                            <input type="text" 
                                   id="phone" 
                                   value="<?php echo htmlspecialchars($row['phone']); ?>" 
                                   onchange="updateProfileField(this, 'phone')" 
                                   required>
                        </div>
                        <div class="settings-field">
                            <label><?php __e('profile_email_label'); ?></label>
                            <input type="email" 
                                   id="email" 
                                   value="<?php echo htmlspecialchars($row['email']); ?>" 
                                   disabled 
                                   style="opacity: 0.6;">
                            <small style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php __e('profile_email_readonly'); ?></small>
                        </div>
                        <div class="settings-field">
                            <label><?php __e('profile_nic_label'); ?></label>
                            <input type="text" 
                                   id="nic" 
                                   value="<?php echo htmlspecialchars($row['nic']); ?>" 
                                   onchange="updateProfileField(this, 'nic')" 
                                   required>
                        </div>
                        <div class="settings-field">
                            <label><?php __e('profile_gender_label'); ?></label>
                            <select id="gender" onchange="updateProfileField(this, 'gender')" required>
                                <option value="1" <?php echo $row['gender'] == "1" ? "selected" : ""; ?>><?php __e('register_gender_male'); ?></option>
                                <option value="2" <?php echo $row['gender'] == "2" ? "selected" : ""; ?>><?php __e('register_gender_female'); ?></option>
                            </select>
                        </div>
                        <div class="settings-field full-width">
                            <label><?php __e('profile_address_label'); ?></label>
                            <input type="text" 
                                   id="address" 
                                   value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>" 
                                   disabled 
                                   style="opacity: 0.6;">
                            <small style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php __e('profile_address_auto'); ?></small>
                        </div>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="settings-section">
                    <h4 class="section-title">
                        <i class="bi bi-shield-lock"></i> <?php __e('change_password_title'); ?>
                    </h4>
                    <form id="passwordForm" onsubmit="event.preventDefault(); savePassword();">
                        <div class="settings-grid">
                            <div class="settings-field">
                                <label><?php __e('change_password_current'); ?></label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       required>
                            </div>
                            <div class="settings-field">
                                <label><?php __e('change_password_new'); ?></label>
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password" 
                                       required>
                            </div>
                            <div class="settings-field">
                                <label><?php __e('change_password_confirm'); ?></label>
                                <input type="password" 
                                       id="confirm_new_password" 
                                       name="confirm_new_password" 
                                       required>
                            </div>
                        </div>
                        <div class="settings-actions">
                            <button type="submit" class="btn primary">
                                <i class="bi bi-check-circle"></i> <?php __e('change_password_save'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Account Status Section -->
                <div class="settings-section">
                    <h4 class="section-title">
                        <i class="bi bi-info-circle"></i> <?php __e('settings_account_status'); ?>
                    </h4>
                    <div class="settings-info">
                        <div class="info-item">
                            <i class="bi bi-check-circle-fill" style="color: var(--ok);"></i>
                            <span><?php __e('settings_member_since'); ?>: <?php echo date('M d, Y', strtotime($row['date_created'] ?? 'now')); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-shield-check" style="color: var(--brand);"></i>
                            <span><?php __e('settings_account_status'); ?>: <?php echo ($row['active'] == 1) ? __t('settings_active') : __t('settings_inactive'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="settings-actions" style="margin-top: 32px; padding-top: 24px; border-top: 2px solid rgba(0, 0, 0, .08);">
                    <a href="orders.php" class="btn">
                        <i class="bi bi-arrow-left"></i> <?php __e('back'); ?>
                    </a>
                </div>
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

            if (newPassword.length < 8) {
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
                        // Update password
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

        function showMessage(message, type) {
            const container = document.getElementById('messageContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert ' + (type === 'success' ? 'success' : 'error');
            alertDiv.textContent = message;
            
            container.innerHTML = '';
            container.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>

</html>

