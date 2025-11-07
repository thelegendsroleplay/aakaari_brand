<?php
/**
 * The Header for the Aakaari Brand Theme
 */
?>
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
    <header class="header-main">
        <!-- Main Header -->
        <div class="header-container">
            <div class="header-content">
                <!-- Logo -->
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo">
                    <div class="header-logo-icon">
                        <span class="header-logo-text">ST</span>
                    </div>
                    <span class="header-logo-name"><?php bloginfo( 'name' ); ?></span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="header-nav">
                    <?php
                    $menu_items = array(
                        array( 'label' => 'Home', 'url' => home_url('/') ),
                        array( 'label' => 'T-Shirts', 'url' => get_permalink( wc_get_page_id( 'shop' ) ) ),
                        array( 'label' => 'Hoodies', 'url' => get_term_link( 'hoodies', 'product_cat' ) ),
                        array( 'label' => 'New Arrivals', 'url' => home_url('/new-arrivals/') ),
                        array( 'label' => 'Sale', 'url' => home_url('/sale/') ),
                    );

                    foreach ( $menu_items as $item ) :
                        $is_current = ( $_SERVER['REQUEST_URI'] === parse_url( $item['url'], PHP_URL_PATH ) );
                        $active_class = $is_current ? 'header-nav-link-active' : '';
                    ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"
                           class="header-nav-link <?php echo esc_attr( $active_class ); ?>">
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Actions -->
                <div class="header-actions">
                    <!-- User Menu -->
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="header-user-menu">
                            <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"
                               class="header-action-btn">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </a>
                            <div class="header-dropdown">
                                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"
                                   class="header-dropdown-item">
                                    Account
                                </a>
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
                                   class="header-dropdown-item">
                                    Orders
                                </a>
                                <?php if ( current_user_can( 'manage_options' ) ) : ?>
                                    <a href="<?php echo esc_url( admin_url() ); ?>"
                                       class="header-dropdown-item header-dropdown-admin">
                                        Admin
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
                                   class="header-dropdown-item header-dropdown-logout">
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"
                           class="header-action-btn">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- Wishlist -->
                    <?php if ( function_exists( 'YITH_WCWL' ) ) :
                        $wishlist_count = YITH_WCWL()->count_products();
                    ?>
                        <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>"
                           class="header-action-btn header-wishlist-btn">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            <?php if ( $wishlist_count > 0 ) : ?>
                                <span class="header-badge"><?php echo esc_html( $wishlist_count ); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Cart -->
                    <?php if ( function_exists( 'WC' ) ) :
                        $cart_count = WC()->cart->get_cart_contents_count();
                    ?>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                           class="header-action-btn header-cart-btn">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                            <?php if ( $cart_count > 0 ) : ?>
                                <span class="header-badge cart-count"><?php echo esc_html( $cart_count ); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="header-mobile-toggle" id="mobile-menu-toggle">
                        <svg class="menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                        <svg class="close-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <nav class="header-mobile-menu" id="mobile-menu" style="display: none;">
            <div class="header-mobile-menu-content">
                <?php foreach ( $menu_items as $item ) :
                    $is_current = ( $_SERVER['REQUEST_URI'] === parse_url( $item['url'], PHP_URL_PATH ) );
                    $active_class = $is_current ? 'header-mobile-menu-item-active' : '';
                ?>
                    <a href="<?php echo esc_url( $item['url'] ); ?>"
                       class="header-mobile-menu-item <?php echo esc_attr( $active_class ); ?>">
                        <?php echo esc_html( $item['label'] ); ?>
                    </a>
                <?php endforeach; ?>

                <?php if ( is_user_logged_in() ) : ?>
                    <div class="header-mobile-divider"></div>
                    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>"
                       class="header-mobile-menu-item">
                        Account
                    </a>
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
                       class="header-mobile-menu-item">
                        Orders
                    </a>
                    <?php if ( current_user_can( 'manage_options' ) ) : ?>
                        <a href="<?php echo esc_url( admin_url() ); ?>"
                           class="header-mobile-menu-item header-mobile-menu-admin">
                            Admin Dashboard
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
                       class="header-mobile-menu-item header-mobile-menu-logout">
                        Logout
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div id="content" class="site-content">
