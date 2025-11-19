<?php
// Include i18n bootstrap
if (!function_exists('__e')) {
    require_once __DIR__ . '/../../bootstrap/i18n.php';
}
// Get current page name to determine active menu item
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header-modern">
            <div class="sidebar-logo">
                <div class="logo-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="logo-text">
                    <h1 class="logo-title">FOJ Express</h1>
                    <span class="logo-subtitle">Admin Panel</span>
                </div>
            </div>
        </div>
        <div class="sidebar-menu-modern">
            <ul class="menu-modern">
                <li class="sidebar-item-modern <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <a href="index.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-grid-fill"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_dashboard'); ?></span>
                        <?php if ($current_page == 'index.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="sidebar-item-modern <?php echo ($current_page == 'customer.php' || $current_page == 'add_courier.php') ? 'active' : ''; ?>">
                    <a href="customer.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_customers_title'); ?></span>
                        <?php if ($current_page == 'customer.php' || $current_page == 'add_courier.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="sidebar-item-modern <?php echo ($current_page == 'price.php') ? 'active' : ''; ?>">
                    <a href="price.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-table"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_price_table'); ?></span>
                        <?php if ($current_page == 'price.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="sidebar-item-modern <?php echo ($current_page == 'orders.php' || $current_page == 'order_detail.php' || $current_page == 'add_request.php') ? 'active' : ''; ?>">
                    <a href="orders.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_orders'); ?></span>
                        <?php if ($current_page == 'orders.php' || $current_page == 'order_detail.php' || $current_page == 'add_request.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="sidebar-item-modern <?php echo ($current_page == 'payments.php') ? 'active' : ''; ?>">
                    <a href="payments.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_payments'); ?></span>
                        <?php if ($current_page == 'payments.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <?php /* Commented out - Messages page
                <li class="sidebar-item-modern <?php echo ($current_page == 'message.php') ? 'active' : ''; ?>">
                    <a href="message.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-chat"></i>
                        </div>
                        <span class="link-text">Message</span>
                        <?php if ($current_page == 'message.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>
                */ ?>

                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'admin'): ?>
                    <li class="sidebar-item-modern <?php echo ($current_page == 'branch.php') ? 'active' : ''; ?>">
                        <a href="branch.php" class='sidebar-link-modern'>
                            <div class="link-icon">
                                <i class="bi bi-columns"></i>
                            </div>
                            <span class="link-text"><?php __e('admin_branches_title'); ?></span>
                            <?php if ($current_page == 'branch.php'): ?>
                                <div class="link-indicator"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="sidebar-item-modern <?php echo ($current_page == 'employee.php' || $current_page == 'empolyee_edit.php') ? 'active' : ''; ?>">
                    <a href="employee.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_employees_title'); ?></span>
                        <?php if ($current_page == 'employee.php' || $current_page == 'empolyee_edit.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'admin'): ?>
                    <li class="sidebar-item-modern <?php echo ($current_page == 'area.php') ? 'active' : ''; ?>">
                        <a href="area.php" class='sidebar-link-modern'>
                            <div class="link-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <span class="link-text"><?php __e('admin_areas_title'); ?></span>
                            <?php if ($current_page == 'area.php'): ?>
                                <div class="link-indicator"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php /* Commented out - Gallery page
                    <li class="sidebar-item-modern <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>">
                        <a href="gallery.php" class='sidebar-link-modern'>
                            <div class="link-icon">
                                <i class="bi bi-images"></i>
                            </div>
                            <span class="link-text">Gallery</span>
                            <?php if ($current_page == 'gallery.php'): ?>
                                <div class="link-indicator"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                    */ ?>
                <?php endif; ?>

                <li class="sidebar-item-modern <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                    <a href="settings.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_settings'); ?></span>
                        <?php if ($current_page == 'settings.php'): ?>
                            <div class="link-indicator"></div>
                        <?php endif; ?>
                    </a>
                </li>

                <li class="sidebar-divider"></li>

                <li class="sidebar-item-modern sidebar-item-lang">
                    <div class="lang-switcher-sidebar">
                        <a href="<?php echo get_lang_url('en'); ?>" class="lang-link-sidebar <?php echo get_current_lang() === 'en' ? 'active' : ''; ?>">
                            <i class="bi bi-translate"></i>
                            <span class="lang-text">EN</span>
                        </a>
                        <span class="lang-separator-sidebar">|</span>
                        <a href="<?php echo get_lang_url('ar'); ?>" class="lang-link-sidebar <?php echo get_current_lang() === 'ar' ? 'active' : ''; ?>">
                            <i class="bi bi-translate"></i>
                            <span class="lang-text">AR</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item-modern sidebar-item-logout">
                    <a href="logout.php" class='sidebar-link-modern'>
                        <div class="link-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <span class="link-text"><?php __e('admin_log_out'); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>

<style>
    /* Modern Sidebar Styles */
    #sidebar {
        background: linear-gradient(180deg, #1a1f3a 0%, #2d3561 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .sidebar-header-modern {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 8px;
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .logo-icon i {
        font-size: 24px;
        color: white;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
    }

    .logo-title {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin: 0;
        line-height: 1.2;
    }

    .logo-subtitle {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .sidebar-menu-modern {
        padding: 12px 8px;
        overflow-y: auto;
        height: calc(100vh - 120px);
    }

    .menu-modern {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-item-modern {
        margin-bottom: 4px;
        position: relative;
    }

    .sidebar-link-modern {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.2s ease;
        position: relative;
        font-size: 14px;
        font-weight: 500;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar-link-modern:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.5) 0%, rgba(118, 75, 162, 0.5) 100%);
        color: white;
        transform: translateX(4px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar-item-modern.active .sidebar-link-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        font-weight: 600;
        border-color: transparent;
    }
    
    .sidebar-item-modern.active .sidebar-link-modern .link-icon {
        transform: scale(1.1);
    }

    .link-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        transition: transform 0.2s ease;
        color: white;
    }

    .link-text {
        flex: 1;
        font-weight: 500;
        letter-spacing: 0.3px;
        color: white;
    }

    .link-indicator {
        position: absolute;
        right: 12px;
        width: 4px;
        height: 20px;
        background: white;
        border-radius: 2px;
        opacity: 0.8;
    }

    .sidebar-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 16px 12px;
        list-style: none;
    }

    .sidebar-item-logout .sidebar-link-modern {
        color: white;
        margin-top: 8px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar-item-logout .sidebar-link-modern:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.4) 0%, rgba(220, 38, 38, 0.4) 100%);
        color: white;
        transform: translateX(4px);
        border-color: rgba(239, 68, 68, 0.3);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    /* Language Switcher Styles */
    .sidebar-item-lang {
        margin-bottom: 4px;
    }

    .lang-switcher-sidebar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .lang-link-sidebar {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 13px;
        font-weight: 600;
    }

    .lang-link-sidebar:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
    }

    .lang-link-sidebar.active {
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .lang-link-sidebar i {
        font-size: 14px;
    }

    .lang-text {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .lang-separator-sidebar {
        color: rgba(255, 255, 255, 0.3);
        font-weight: 300;
    }

    @media (max-width: 1200px) {
        .lang-text {
            display: none;
        }

        .lang-link-sidebar {
            padding: 8px;
            justify-content: center;
        }

        .lang-separator-sidebar {
            display: none;
        }
    }

    /* Scrollbar Styling */
    .sidebar-menu-modern::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-menu-modern::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .sidebar-menu-modern::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .sidebar-menu-modern::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .logo-text {
            display: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
        }

        .link-text {
            display: none;
        }

        .sidebar-link-modern {
            justify-content: center;
            padding: 12px;
        }

        .link-indicator {
            display: none;
        }
    }
</style>

