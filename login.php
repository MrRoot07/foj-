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
    <title>Login · <?php echo $companyName; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="css/rtl.css">
    <?php endif; ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Same design tokens as the landing page (light theme) */
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

        /* Header / Nav copied from landing page */
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
            max-width: 560px;
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
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px
        }

        .field input {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .12);
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            outline: none
        }

        .field input:focus {
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

        /* Footer */
        footer {
            border-top: 1px solid rgba(0, 0, 0, .08);
            padding: 28px 0;
            color: var(--muted)
        }
    </style>
</head>

<body>

    <?php include 'pages/header.php'; ?>

    <!-- Auth card -->
    <main>
        <div class="auth-card">
            <h1><?php __e('login_title'); ?></h1>
            <p class="lead"><?php __e('login_desc'); ?></p>

            <?php if (isset($_SESSION['status'])): ?>
                <div
                    style="background:#fff3cd;border:1px solid #ffe69c;color:#664d03;padding:10px 12px;border-radius:10px;margin-bottom:10px;">
                    <?= $_SESSION['status'];
                    unset($_SESSION['status']); ?>
                </div>
            <?php endif; ?>

            <form action="code.php" method="POST" novalidate>
                <div class="field">
                    <label for="email"><?php __e('login_email'); ?></label>
                    <input id="email" type="email" name="email" required placeholder="you@example.com" />
                </div>
                <div class="field">
                    <label for="password"><?php __e('login_password'); ?></label>
                    <input id="password" type="password" name="password" required placeholder="••••••••" />
                </div>

                <div class="field">
                    <div class="g-recaptcha" data-sitekey="6Le407cqAAAAALOot4V59zIHTXeBJilaqE3twlQQ"></div>
                </div>

                <div class="actions">
                    <a class="btn" href="index.php"><?php __e('back'); ?></a>
                    <button type="submit" name="login_btn" class="btn primary"><?php __e('login_title'); ?></button>
                </div>

                <div class="helper">
                    <?php __e('login_no_account'); ?>
                    <a href="register.php" style="text-decoration:underline"><?php __e('login_register_link'); ?></a>
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

</body>

</html>