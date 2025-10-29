<?php
session_start();
if (isset($_SESSION['auth'])) {
    header("Location: index.php");
    exit();
}
$companyName = "FOJ Express";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register · <?php echo $companyName; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
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

        .show-pass {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            font-size: 13px;
            cursor: pointer;
            color: var(--brand);
            font-weight: 600;
        }

        .show-pass:hover {
            opacity: .8;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="container nav">
            <a href="index.php" class="brand" aria-label="<?php echo $companyName; ?> Home">
                <span class="logo" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M3 12h9" />
                        <path d="M12 6l6 6-6 6" />
                    </svg>
                </span>
                <span><?php echo $companyName; ?></span>
            </a>
            <nav class="nav-links" aria-label="Primary">
                <a href="index.php#services">Services</a>
                <a href="index.php#about">About</a>
                <a href="index.php#gallery">Gallery</a>
                <a href="index.php#contact">Contact</a>
            </nav>
            <div class="nav-cta">
                <a class="btn light" href="login.php">Log in</a>
                <a class="btn primary" href="register.php">Register</a>
            </div>
            <button class="hamburger" aria-label="Open menu" onclick="toggleMenu()">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </button>
        </div>
        <div id="mobileMenu" class="mobile-menu container" role="dialog" aria-modal="true" aria-label="Mobile menu">
            <a href="index.php#services" onclick="toggleMenu(false)">Services</a>
            <a href="index.php#about" onclick="toggleMenu(false)">About</a>
            <a href="index.php#gallery" onclick="toggleMenu(false)">Gallery</a>
            <a href="index.php#contact" onclick="toggleMenu(false)">Contact</a>
            <a class="btn light" href="login.php">Log in</a>
            <a class="btn primary" href="register.php">Register</a>
        </div>
    </header>

    <!-- Register card -->
    <main>
        <div class="auth-card">
            <h1>Create your account</h1>
            <p class="lead">Join <?php echo $companyName; ?> and start managing your deliveries.</p>

            <?php if (isset($_SESSION['status'])): ?>
                <div class="alert"><?= $_SESSION['status'];
                unset($_SESSION['status']); ?></div>
            <?php endif; ?>

            <form action="code.php" method="POST" novalidate>
                <div class="grid">
                    <div class="field">
                        <label for="name">Full Name</label>
                        <input id="name" type="text" name="name" required placeholder="Jane Doe" />
                    </div>
                    <div class="field">
                        <label for="nic">Passport Number</label>
                        <input id="nic" type="text" name="nic" required placeholder="A123456789" />
                    </div>
                    <div class="field">
                        <label for="phone">Phone Number</label>
                        <input id="phone" type="text" name="phone" required placeholder="+60 12 345 6789" />
                    </div>
                    <div class="field">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" required placeholder="you@example.com" />
                    </div>
                    <div class="field">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="address">Address</label>
                        <input id="address" type="text" name="address" required placeholder="123 Street, City" />
                    </div>
                    <div class="field password-field">
                        <label for="password">Password</label>
                        <div class="pass-wrapper">
                            <input id="password" type="password" name="password" required placeholder="••••••••" />
                            <button type="button" class="show-pass"
                                onclick="togglePassword('password', this)">Show</button>
                        </div>
                    </div>

                    <div class="field password-field">
                        <label for="cpassword">Confirm Password</label>
                        <div class="pass-wrapper">
                            <input id="cpassword" type="password" name="cpassword" required placeholder="••••••••" />
                            <button type="button" class="show-pass"
                                onclick="togglePassword('cpassword', this)">Show</button>
                        </div>
                    </div>

                </div>

                <div class="field">
                    <div class="g-recaptcha" data-sitekey="6Le407cqAAAAALOot4V59zIHTXeBJilaqE3twlQQ"></div>
                </div>

                <div class="actions">
                    <a class="btn" href="index.php">Back</a>
                    <button type="submit" name="reg_btn" class="btn primary">Register</button>
                </div>

                <div class="helper">
                    Already have an account?
                    <a href="login.php" style="text-decoration:underline">Log in</a>
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
        function toggleMenu(force) {
            const el = document.getElementById('mobileMenu');
            const isOpen = typeof force === 'boolean' ? force : !el.classList.contains('open');
            el.classList.toggle('open', isOpen);
        }
    </script>
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "Hide";
            } else {
                input.type = "password";
                btn.textContent = "Show";
            }
        }
    </script>

</body>

</html>