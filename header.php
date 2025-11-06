<?php
/**
 * The header for the theme
 *
 * @package Aakaari_Brand
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
    <!-- Skip Link -->
    <a class="header-skip-link" href="#primary">
        <?php esc_html_e( 'Skip to content', 'aakaari-brand' ); ?>
    </a>

    <!-- Header -->
    <header id="masthead" class="header-main">
        <div class="header-container">
            <div class="header-content">
                <!-- Mobile Menu Button -->
                <button
                    class="header-mobile-menu-btn"
                    id="mobile-menu-toggle"
                    aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'aakaari-brand' ); ?>"
                    aria-expanded="false"
                    aria-controls="mobile-navigation"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <!-- Logo -->
                <?php if ( has_custom_logo() ) : ?>
                    <div class="header-logo-wrapper">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo" rel="home">
                        <?php
                        $site_name = get_bloginfo( 'name' );
                        $parts = explode( ' ', $site_name, 2 );
                        if ( count( $parts ) > 1 ) {
                            echo esc_html( $parts[0] ) . '<span class="header-logo-accent">' . esc_html( $parts[1] ) . '</span>';
                        } else {
                            echo esc_html( $site_name );
                        }
                        ?>
                    </a>
                <?php endif; ?>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="header-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aakaari-brand' ); ?>">
                    <?php
                    if ( has_nav_menu( 'primary' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'header-nav-menu',
                            'fallback_cb'    => false,
                            'items_wrap'     => '%3$s',
                            'link_before'    => '',
                            'link_after'     => '',
                        ) );
                    } else {
                        // Fallback menu
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-nav-link <?php echo is_front_page() ? 'header-nav-link-active' : ''; ?>">
                            <?php esc_html_e( 'Home', 'aakaari-brand' ); ?>
                        </a>
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="header-nav-link <?php echo is_shop() ? 'header-nav-link-active' : ''; ?>">
                                <?php esc_html_e( 'Shop', 'aakaari-brand' ); ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="header-nav-link">
                            <?php esc_html_e( 'About', 'aakaari-brand' ); ?>
                        </a>
                        <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="header-nav-link">
                            <?php esc_html_e( 'Contact', 'aakaari-brand' ); ?>
                        </a>
                        <?php
                    }
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search Button -->
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
                           class="header-action-btn header-action-search"
                           aria-label="<?php esc_attr_e( 'Search', 'aakaari-brand' ); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="9" r="7"></circle>
                                <line x1="15" y1="15" x2="20" y2="20"></line>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- User Account Button -->
                    <a href="<?php echo is_user_logged_in() ? esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) : esc_url( wp_login_url() ); ?>"
                       class="header-action-btn"
                       aria-label="<?php esc_attr_e( 'Account', 'aakaari-brand' ); ?>">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 17v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="5" r="4"></circle>
                        </svg>
                    </a>

                    <!-- Wishlist Button (if YITH plugin active) -->
                    <?php if ( function_exists( 'yith_wcwl_count_products' ) ) : ?>
                        <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>"
                           class="header-action-btn header-wishlist-btn"
                           aria-label="<?php esc_attr_e( 'Wishlist', 'aakaari-brand' ); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 17.5L8.825 16.45C4.4 12.475 1.5 9.85 1.5 6.625C1.5 4 3.5 2 6.125 2C7.6 2 9.025 2.725 10 3.875C10.975 2.725 12.4 2 13.875 2C16.5 2 18.5 4 18.5 6.625C18.5 9.85 15.6 12.475 11.175 16.45L10 17.5Z"></path>
                            </svg>
                            <?php
                            $wishlist_count = yith_wcwl_count_products();
                            if ( $wishlist_count > 0 ) :
                                ?>
                                <span class="header-badge"><?php echo absint( $wishlist_count ); ?></span>
                                <?php
                            endif;
                            ?>
                        </a>
                    <?php endif; ?>

                    <!-- Cart Button -->
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                           class="header-action-btn header-cart-btn"
                           aria-label="<?php esc_attr_e( 'Shopping Cart', 'aakaari-brand' ); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="17" y2="6"></line>
                                <path d="M10 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            </svg>
                            <?php
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ( $cart_count > 0 ) :
                                ?>
                                <span class="header-badge"><?php echo absint( $cart_count ); ?></span>
                                <?php
                            endif;
                            ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <?php get_template_part( 'template-parts/mobile-navigation' ); ?>
