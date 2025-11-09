<?php
/**
 * The header template
 *
 * @package Aakaari_Brand
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="header-main">
        <!-- Main Header -->
        <div class="header-container">
            <div class="header-content">
                <!-- Logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo">
                    <?php
                    if (has_custom_logo()) {
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        if ($logo) {
                            ?>
                            <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php bloginfo('name'); ?>" class="header-logo-custom">
                            <?php
                        }
                    } else {
                        $site_name = get_bloginfo('name');
                        $initials = '';
                        $words = explode(' ', $site_name);
                        if (count($words) >= 2) {
                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($site_name, 0, 2));
                        }
                        ?>
                        <div class="header-logo-icon">
                            <span class="header-logo-text"><?php echo esc_html($initials); ?></span>
                        </div>
                        <span class="header-logo-name"><?php bloginfo('name'); ?></span>
                        <?php
                    }
                    ?>
                </a>

                <!-- Desktop Navigation -->
                <nav class="header-nav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'header-nav-menu',
                        'container'      => false,
                        'fallback_cb'    => 'aakaari_default_menu',
                    ));
                    ?>
                </nav>

                <!-- Actions -->
                <div class="header-actions">
                    <!-- User Account -->
                    <?php if (is_user_logged_in()) : ?>
                        <div class="header-user-menu">
                            <button class="header-action-btn" aria-label="User Account">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </button>
                            <div class="header-dropdown">
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="header-dropdown-item">
                                    Account
                                </a>
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="header-dropdown-item">
                                    Orders
                                </a>
                                <?php if (current_user_can('manage_options')) : ?>
                                    <a href="<?php echo esc_url(admin_url()); ?>" class="header-dropdown-item header-dropdown-admin">
                                        Admin
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="header-dropdown-item header-dropdown-logout">
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="header-action-btn" aria-label="Login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- Wishlist -->
                    <?php if (function_exists('YITH_WCWL')) : ?>
                        <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" class="header-action-btn header-wishlist-btn" aria-label="Wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                            <?php
                            $wishlist_count = yith_wcwl_count_products();
                            if ($wishlist_count > 0) :
                            ?>
                                <span class="header-badge"><?php echo esc_html($wishlist_count); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Cart -->
                    <?php if (is_woocommerce_activated()) : ?>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-action-btn header-cart-btn" aria-label="Cart">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <?php
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ($cart_count > 0) :
                            ?>
                                <span class="header-badge cart-count"><?php echo esc_html($cart_count); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="header-mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle Menu">
                        <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                        <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <nav class="header-mobile-menu" id="mobile-menu" style="display: none;">
            <div class="header-mobile-menu-content">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'header-mobile-nav',
                    'container'      => false,
                    'fallback_cb'    => 'aakaari_default_mobile_menu',
                ));
                ?>
                <?php if (is_user_logged_in()) : ?>
                    <div class="header-mobile-divider"></div>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="header-mobile-menu-item">
                        Account
                    </a>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="header-mobile-menu-item">
                        Orders
                    </a>
                    <?php if (current_user_can('manage_options')) : ?>
                        <a href="<?php echo esc_url(admin_url()); ?>" class="header-mobile-menu-item header-mobile-menu-admin">
                            Admin Dashboard
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="header-mobile-menu-item header-mobile-menu-logout">
                        Logout
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div id="content" class="site-content">
