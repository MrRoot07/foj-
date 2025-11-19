<?php
if (session_id() == '') {
    session_start();
}
// Include i18n bootstrap
require_once __DIR__ . '/../bootstrap/i18n.php';
$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
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
            <a href="index.php#services"><?php __e('nav_services'); ?></a>
            <a href="index.php#about"><?php __e('nav_about'); ?></a>
            <a href="index.php#gallery"><?php __e('nav_gallery'); ?></a>
            <a href="index.php#contact"><?php __e('nav_contact'); ?></a>
        </nav>
        <div class="nav-cta">
            <div class="lang-switcher">
                <a href="<?php echo get_lang_url('en'); ?>" class="lang-link <?php echo $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
                <span class="lang-separator">|</span>
                <a href="<?php echo get_lang_url('ar'); ?>" class="lang-link <?php echo $current_lang === 'ar' ? 'active' : ''; ?>">AR</a>
            </div>
            <?php if (isset($_SESSION['auth'])): ?>
                <a class="btn" href="orders.php"><?php __e('nav_orders'); ?></a>
                <a class="btn" href="request.php"><?php __e('nav_request'); ?></a>
                <a class="btn" href="profile.php"><?php __e('nav_profile'); ?></a>
                <a class="btn light" href="logout.php"><?php __e('nav_logout'); ?></a>
            <?php else: ?>
                <a class="btn light" href="login.php"><?php __e('nav_login'); ?></a>
                <a class="btn primary" href="register.php"><?php __e('nav_register'); ?></a>
            <?php endif; ?>
        </div>
        <button class="hamburger" aria-label="Open menu" onclick="toggleMenu()">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18M3 12h18M3 18h18" />
            </svg>
        </button>
    </div>
    <div id="mobileMenu" class="mobile-menu container" role="dialog" aria-modal="true" aria-label="Mobile menu">
        <a href="index.php#services" onclick="toggleMenu(false)"><?php __e('nav_services'); ?></a>
        <a href="index.php#about" onclick="toggleMenu(false)"><?php __e('nav_about'); ?></a>
        <a href="index.php#gallery" onclick="toggleMenu(false)"><?php __e('nav_gallery'); ?></a>
        <a href="index.php#contact" onclick="toggleMenu(false)"><?php __e('nav_contact'); ?></a>
        <div class="lang-switcher-mobile">
            <a href="<?php echo get_lang_url('en'); ?>" class="lang-link <?php echo $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
            <span class="lang-separator">|</span>
            <a href="<?php echo get_lang_url('ar'); ?>" class="lang-link <?php echo $current_lang === 'ar' ? 'active' : ''; ?>">AR</a>
        </div>
        <?php if (isset($_SESSION['auth'])): ?>
            <a class="btn" href="orders.php" onclick="toggleMenu(false)"><?php __e('nav_orders'); ?></a>
            <a class="btn primary" href="request.php" onclick="toggleMenu(false)"><?php __e('nav_request'); ?></a>
            <a class="btn" href="profile.php" onclick="toggleMenu(false)"><?php __e('nav_profile'); ?></a>
            <a class="btn light" href="logout.php" onclick="toggleMenu(false)"><?php __e('nav_logout'); ?></a>
        <?php else: ?>
            <a class="btn light" href="login.php" onclick="toggleMenu(false)"><?php __e('nav_login'); ?></a>
            <a class="btn primary" href="register.php" onclick="toggleMenu(false)"><?php __e('nav_register'); ?></a>
        <?php endif; ?>
    </div>
</header>

<script>
    function toggleMenu(force) {
        const el = document.getElementById('mobileMenu');
        const isOpen = typeof force === 'boolean' ? force : !el.classList.contains('open');
        el.classList.toggle('open', isOpen);
    }
</script>

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

    header {
        position: sticky;
        top: 0;
        z-index: 50;
        background: rgba(255, 255, 255, .85);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, .08);
    }

    .container {
        max-width: var(--grid-max);
        margin: 0 auto;
        padding: 0 20px;
    }

    .nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 64px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        letter-spacing: .2px;
        color: var(--text);
        text-decoration: none;
    }

    .brand .logo {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--brand), var(--brand-2));
        display: grid;
        place-items: center;
        box-shadow: var(--shadow-soft);
    }

    .brand .logo svg {
        width: 22px;
        height: 22px;
        color: white;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .nav-links a {
        color: var(--text);
        text-decoration: none;
        opacity: .92;
        transition: opacity .2s ease;
    }

    .nav-links a:hover {
        opacity: 1;
    }

    .nav-cta {
        display: flex;
        align-items: center;
        gap: 10px;
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
        text-decoration: none;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-1px);
        border-color: rgba(0, 0, 0, .22);
    }

    .btn.primary {
        background: linear-gradient(135deg, var(--brand), var(--brand-2));
        border: none;
        color: white;
        box-shadow: var(--shadow);
    }

    .btn.light {
        background: rgba(0, 0, 0, .06);
    }

    .lang-switcher {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-right: 12px;
        font-size: 13px;
    }

    .lang-link {
        color: var(--text);
        text-decoration: none;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background .2s ease;
        opacity: .7;
    }

    .lang-link:hover,
    .lang-link.active {
        opacity: 1;
        background: rgba(0, 0, 0, .06);
    }

    .lang-separator {
        color: var(--muted);
        opacity: .5;
    }

    .lang-switcher-mobile {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, .08);
        margin-bottom: 8px;
    }

    .hamburger {
        display: none;
        background: transparent;
        border: none;
        color: var(--text);
        cursor: pointer;
        padding: 8px;
        border-radius: 10px;
    }

    .mobile-menu {
        display: none;
    }

    @media (max-width: 980px) {
        .nav-links {
            display: none;
        }

        .nav-cta {
            display: none;
        }

        .hamburger {
            display: inline-flex;
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
            gap: 10px;
        }

        .mobile-menu a,
        .mobile-menu .btn {
            display: flex;
        }

        .mobile-menu.open {
            display: flex;
        }
    }

    /* RTL Support */
    [dir="rtl"] .nav {
        direction: rtl;
    }

    [dir="rtl"] .nav-links {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .nav-cta {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .lang-switcher {
        margin-right: 0;
        margin-left: 12px;
    }

    [dir="rtl"] .mobile-menu {
        text-align: right;
    }
</style>

