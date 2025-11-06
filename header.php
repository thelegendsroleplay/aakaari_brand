<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'fashionmen'); ?></a>

    <header id="masthead" class="site-header">
        <div class="header-container">
            <div class="header-row">
                <!-- Logo -->
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Navigation -->
                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search Icon -->
                    <a href="<?php echo esc_url(home_url('/search')); ?>" class="header-icon search-icon" aria-label="<?php esc_attr_e('Search', 'fashionmen'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </a>

                    <!-- Account Icon -->
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="header-icon account-icon" aria-label="<?php esc_attr_e('My Account', 'fashionmen'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>

                    <!-- Wishlist Icon -->
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(home_url('/wishlist')); ?>" class="header-icon wishlist-icon" aria-label="<?php esc_attr_e('Wishlist', 'fashionmen'); ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </a>

                        <!-- Cart Icon -->
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-icon cart-icon" aria-label="<?php esc_attr_e('Cart', 'fashionmen'); ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Menu', 'fashionmen'); ?>">
                        <span class="menu-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <style>
        /* Header Styles */
        .site-header {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .site-branding .site-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #000;
        }

        .main-navigation {
            flex: 1;
        }

        .main-navigation ul {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .main-navigation a {
            color: #333;
            font-weight: 500;
            padding: 0.5rem 0;
        }

        .main-navigation a:hover {
            color: #000;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-icon {
            position: relative;
            display: flex;
            align-items: center;
            color: #333;
        }

        .header-icon:hover {
            color: #000;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #000;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .main-navigation {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .menu-icon {
                display: block;
                width: 24px;
                height: 2px;
                background: #000;
                position: relative;
            }

            .menu-icon::before,
            .menu-icon::after {
                content: '';
                display: block;
                width: 24px;
                height: 2px;
                background: #000;
                position: absolute;
            }

            .menu-icon::before {
                top: -8px;
            }

            .menu-icon::after {
                top: 8px;
            }
        }
    </style>
