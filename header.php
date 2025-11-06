<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aakaari-brand' ); ?></a>

    <header class="header-main" id="masthead">
        <!-- Main Header -->
        <div class="header-container">
            <div class="header-content">
                <!-- Logo -->
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <div class="header-logo-icon">
                            <span class="header-logo-text">ST</span>
                        </div>
                        <span class="header-logo-name"><?php bloginfo( 'name' ); ?></span>
                    <?php endif; ?>
                </a>

                <!-- Desktop Navigation -->
                <nav class="header-nav">
                    <?php
                    $menu_items = array(
                        array( 'label' => 'Home', 'url' => home_url( '/' ) ),
                        array( 'label' => 'T-Shirts', 'url' => home_url( '/shop/' ) ),
                        array( 'label' => 'Hoodies', 'url' => home_url( '/shop/' ) ),
                        array( 'label' => 'New Arrivals', 'url' => home_url( '/shop/' ) ),
                        array( 'label' => 'Sale', 'url' => home_url( '/shop/' ) ),
                    );

                    foreach ( $menu_items as $item ) :
                        $is_active = ( home_url( $_SERVER['REQUEST_URI'] ) === $item['url'] ) ? 'header-nav-link-active' : '';
                        ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"
                           class="header-nav-link <?php echo esc_attr( $is_active ); ?>">
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Actions -->
                <div class="header-actions">
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="header-user-menu">
                            <button class="header-action-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </button>
                            <div class="header-dropdown">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="header-dropdown-item">
                                    <?php esc_html_e( 'Account', 'aakaari-brand' ); ?>
                                </a>
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="header-dropdown-item">
                                    <?php esc_html_e( 'Orders', 'aakaari-brand' ); ?>
                                </a>
                                <?php if ( current_user_can( 'manage_options' ) ) : ?>
                                    <a href="<?php echo esc_url( admin_url() ); ?>" class="header-dropdown-item header-dropdown-admin">
                                        <?php esc_html_e( 'Admin', 'aakaari-brand' ); ?>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="header-dropdown-item header-dropdown-logout">
                                    <?php esc_html_e( 'Logout', 'aakaari-brand' ); ?>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="header-action-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- Wishlist Button (placeholder) -->
                    <button class="header-action-btn header-wishlist-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="header-badge" style="display: none;">0</span>
                    </button>

                    <!-- Cart Button -->
                    <?php if ( function_exists( 'WC' ) ) : ?>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-action-btn header-cart-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <?php
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ( $cart_count > 0 ) :
                                ?>
                                <span class="header-badge"><?php echo esc_html( $cart_count ); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="header-mobile-toggle" id="mobile-menu-toggle">
                        <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="close-icon" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <nav class="header-mobile-menu" id="mobile-menu" style="display: none;">
            <div class="header-mobile-menu-content">
                <?php
                foreach ( $menu_items as $item ) :
                    $is_active = ( home_url( $_SERVER['REQUEST_URI'] ) === $item['url'] ) ? 'header-mobile-menu-item-active' : '';
                    ?>
                    <a href="<?php echo esc_url( $item['url'] ); ?>"
                       class="header-mobile-menu-item <?php echo esc_attr( $is_active ); ?>">
                        <?php echo esc_html( $item['label'] ); ?>
                    </a>
                <?php endforeach; ?>

                <?php if ( is_user_logged_in() ) : ?>
                    <div class="header-mobile-divider"></div>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="header-mobile-menu-item">
                        <?php esc_html_e( 'Account', 'aakaari-brand' ); ?>
                    </a>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="header-mobile-menu-item">
                        <?php esc_html_e( 'Orders', 'aakaari-brand' ); ?>
                    </a>
                    <?php if ( current_user_can( 'manage_options' ) ) : ?>
                        <a href="<?php echo esc_url( admin_url() ); ?>" class="header-mobile-menu-item header-mobile-menu-admin">
                            <?php esc_html_e( 'Admin Dashboard', 'aakaari-brand' ); ?>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="header-mobile-menu-item header-mobile-menu-logout">
                        <?php esc_html_e( 'Logout', 'aakaari-brand' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header><!-- #masthead -->

    <div id="content" class="site-content">
