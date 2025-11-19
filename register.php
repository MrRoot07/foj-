<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/bootstrap/i18n.php';
if (isset($_SESSION['auth'])) {
    header("Location: index.php");
    exit();
}
$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register · <?php echo $companyName; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="css/rtl.css">
    <?php endif; ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Design tokens: same as landing & login (light theme) */
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

        /* Header / Nav (mirrors landing) */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, .08)
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            letter-spacing: .2px
        }

        .brand .logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: grid;
            place-items: center;
            box-shadow: var(--shadow-soft)
        }

        .brand .logo svg {
            width: 22px;
            height: 22px
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 22px
        }

        .nav-cta {
            display: flex;
            align-items: center;
            gap: 10px
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
            transition: .2s ease
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

        .btn.light {
            background: rgba(0, 0, 0, .06)
        }

        .hamburger {
            display: none;
            background: transparent;
            border: none;
            color: var(--text)
        }

        .mobile-menu {
            display: none
        }

        @media (max-width:980px) {
            .nav-links {
                display: none
            }

            .nav-cta {
                display: none
            }

            .hamburger {
                display: inline-flex;
                padding: 8px;
                border-radius: 10px
            }

            .mobile-menu {
                position: fixed;
                inset: 64px 12px auto 12px;
                background: var(--panel);
                border: 1px solid rgba(0, 0, 0, .08);
                border-radius: 16px;
                box-shadow: var(--shadow);
                padding: 14px;
                display: none;
                flex-direction: column;
                gap: 10px
            }

            .mobile-menu a,
            .mobile-menu .btn {
                display: flex
            }

            .mobile-menu.open {
                display: flex
            }
        }

        /* Page layout */
        main {
            display: grid;
            place-items: center;
            min-height: calc(100vh - 64px);
            padding: 40px 0
        }

        .auth-card {
            width: 100%;
            max-width: 720px;
            background: var(--panel);
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            padding: 22px
        }

        h1 {
            margin: 4px 0 6px;
            font-size: 28px
        }

        p.lead {
            margin: 0 0 18px;
            color: var(--muted)
        }

        /* Form */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        @media (max-width:720px) {
            .grid {
                grid-template-columns: 1fr
            }
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px
        }

        .field input,
        .field select {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none
        }

        .field input:focus,
        .field select:focus {
            box-shadow: var(--ring);
            border-color: transparent
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px
        }

        .helper {
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            margin-top: 12px
        }

        /* Alerts */
        .alert {
            background: #fff3cd;
            border: 1px solid #ffe69c;
            color: #664d03;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 10px
        }

        .alert.error {
            background: #ffe2e2;
            border-color: #ffb3b3;
            color: #7a0c0c
        }

        /* Footer */
        footer {
            border-top: 1px solid rgba(0, 0, 0, .08);
            padding: 28px 0;
            color: var(--muted)
        }

        .pass-wrapper {
            position: relative;
            display: flex;
            align-items: center;
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

        .req-text {
            flex: 1;
            line-height: 1.5;
        }

        .personal-section,
        .address-section,
        .password-section {
            margin: 24px 0;
            padding: 20px;
            background: rgba(37, 99, 235, 0.03);
            border: 1px solid rgba(37, 99, 235, 0.1);
            border-radius: 12px;
        }

        .personal-section h3,
        .address-section h3,
        .password-section h3 {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .personal-section h3::before {
            content: "👤";
            font-size: 18px;
        }

        .address-section h3::before {
            content: "📍";
            font-size: 18px;
        }

        .password-section h3::before {
            content: "🔒";
            font-size: 18px;
        }

        .personal-section .grid,
        .address-section .grid,
        .password-section .grid {
            margin-top: 0;
        }
    </style>
</head>

<body>

    <?php include 'pages/header.php'; ?>

    <!-- Register card -->
    <main>
        <div class="auth-card">
            <h1><?php __e('register_title'); ?></h1>
            <p class="lead"><?php __e('register_desc'); ?></p>

            <?php if (isset($_SESSION['status'])): ?>
                <div class="alert"><?= $_SESSION['status'];
                unset($_SESSION['status']); ?></div>
            <?php endif; ?>

            <form action="code.php" method="POST" novalidate>
                <div class="personal-section">
                    <h3><?php __e('register_personal_info'); ?></h3>
                <div class="grid">
                    <div class="field">
                            <label for="name"><?php __e('register_full_name'); ?></label>
                        <input id="name" type="text" name="name" required placeholder="Jane Doe" />
                    </div>
                    <div class="field">
                            <label for="nic"><?php __e('register_passport'); ?></label>
                        <input id="nic" type="text" name="nic" required placeholder="A123456789" />
                    </div>
                    <div class="field">
                            <label for="phone"><?php __e('register_phone'); ?></label>
                        <input id="phone" type="text" name="phone" required placeholder="+60 12 345 6789" />
                    </div>
                    <div class="field">
                            <label for="email"><?php __e('register_email'); ?></label>
                        <input id="email" type="email" name="email" required placeholder="you@example.com" />
                    </div>
                    <div class="field">
                            <label for="gender"><?php __e('register_gender'); ?></label>
                        <select id="gender" name="gender" required>
                                <option value=""><?php __e('register_gender_select'); ?></option>
                                <option value="1"><?php __e('register_gender_male'); ?></option>
                                <option value="2"><?php __e('register_gender_female'); ?></option>
                        </select>
                    </div>
                    </div>
                </div>

                <div class="address-section">
                    <h3><?php __e('register_address_info'); ?></h3>
                    <div class="grid">
                        <div class="field">
                            <label for="street"><?php __e('register_street'); ?></label>
                            <input id="street" type="text" name="street" required placeholder="123 Main Street" />
                        </div>
                        <div class="field">
                            <label for="city"><?php __e('register_city'); ?></label>
                            <input id="city" type="text" name="city" required placeholder="City Name" />
                        </div>
                        <div class="field">
                            <label for="state"><?php __e('register_state'); ?></label>
                            <input id="state" type="text" name="state" required placeholder="State or Province" />
                        </div>
                    <div class="field">
                            <label for="zip_code"><?php __e('register_zip'); ?></label>
                            <input id="zip_code" type="text" name="zip_code" required placeholder="12345" />
                        </div>
                        <div class="field" style="grid-column: 1 / -1;">
                            <label for="additional_address"><?php __e('register_additional'); ?></label>
                            <input id="additional_address" type="text" name="additional_address" placeholder="Apartment, suite, unit, building, floor, etc." />
                        </div>
                    </div>
                    </div>

                <div class="password-section">
                    <h3><?php __e('register_password'); ?></h3>
                    <div class="grid">
                    <div class="field password-field">
                            <label for="password"><?php __e('register_password'); ?></label>
                        <div class="pass-wrapper">
                                <input id="password" type="password" name="password" required placeholder="••••••••" oninput="checkPasswordStrength()" />
                        </div>
                    </div>

                    <div class="field password-field">
                            <label for="cpassword"><?php __e('register_confirm_password'); ?></label>
                        <div class="pass-wrapper">
                            <input id="cpassword" type="password" name="cpassword" required placeholder="••••••••" />
                        </div>
                    </div>

                        <div class="field" style="grid-column: 1 / -1; display: flex; justify-content: center; padding-top: 0; margin-top: -8px;">
                            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; margin: 0;">
                                <input type="checkbox" id="show-passwords" onchange="togglePasswords()" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
                                <span style="font-size: 13px;"><?php __e('register_show_passwords'); ?></span>
                            </label>
                        </div>

                        <div class="field" style="grid-column: 1 / -1;">
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

                <div class="field">
                    <div class="g-recaptcha" data-sitekey="6Le407cqAAAAALOot4V59zIHTXeBJilaqE3twlQQ"></div>
                </div>

                <div class="actions">
                    <a class="btn" href="index.php"><?php __e('back'); ?></a>
                    <button type="submit" name="reg_btn" class="btn primary"><?php __e('nav_register'); ?></button>
                </div>

                <div class="helper">
                    <?php __e('register_already_account'); ?>
                    <a href="login.php" style="text-decoration:underline"><?php __e('register_login_link'); ?></a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container"
            style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:10px">
                <span class="brand" style="gap:8px"><span class="logo"
                        style="width:28px;height:28px"></span><span><?php echo $companyName; ?></span></span>
            </div>
            <div style="display:flex;gap:14px">
                <a href="index.php#about">About</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#contact">Contact</a>
                <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'})">Back to top</a>
            </div>
        </div>
    </footer>

    <script>
        function togglePasswords() {
            const checkbox = document.getElementById('show-passwords');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('cpassword');
            
            if (checkbox.checked) {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            
            // Check each requirement
            const requirements = {
                'req-length': password.length >= 8,
                'req-uppercase': /[A-Z]/.test(password),
                'req-lowercase': /[a-z]/.test(password),
                'req-number': /[0-9]/.test(password),
                'req-special': /[\W_]/.test(password)
            };

            // Update each requirement's visual state
            Object.keys(requirements).forEach(reqId => {
                const reqElement = document.getElementById(reqId);
                if (requirements[reqId]) {
                    reqElement.classList.add('met');
                } else {
                    reqElement.classList.remove('met');
                }
            });
        }

        // Check password strength on page load if password field has value
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.value) {
                checkPasswordStrength();
            }
        });
    </script>

</body>

</html>