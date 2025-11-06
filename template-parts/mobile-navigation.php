<?php
/**
 * Mobile Navigation Template
 *
 * @package Aakaari_Brand
 */

$is_user_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
$user_role = '';

if ( $is_user_logged_in ) {
    $user_role = current_user_can( 'administrator' ) ? 'admin' : 'customer';
}
?>

<!-- Backdrop -->
<div class="mobile-nav-backdrop" id="mobile-nav-backdrop"></div>

<!-- Sidebar -->
<div class="mobile-nav-sidebar" id="mobile-navigation">
    <!-- Header -->
    <div class="mobile-nav-header">
        <div class="mobile-nav-logo">
            <span class="mobile-nav-title"><?php bloginfo( 'name' ); ?></span>
            <span class="mobile-nav-subtitle"><?php esc_html_e( 'Menu', 'aakaari-brand' ); ?></span>
        </div>
        <button class="mobile-nav-close" id="mobile-nav-close" aria-label="<?php esc_attr_e( 'Close menu', 'aakaari-brand' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- User Section -->
    <?php if ( $is_user_logged_in ) : ?>
        <div class="mobile-nav-user">
            <div class="mobile-nav-user-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="mobile-nav-user-info">
                <p class="mobile-nav-user-name">
                    <?php
                    if ( $user_role === 'admin' ) {
                        esc_html_e( 'Admin User', 'aakaari-brand' );
                    } else {
                        esc_html_e( 'Welcome Back', 'aakaari-brand' );
                    }
                    ?>
                </p>
                <p class="mobile-nav-user-role">
                    <?php
                    if ( $user_role === 'admin' ) {
                        esc_html_e( 'Administrator', 'aakaari-brand' );
                    } else {
                        esc_html_e( 'Customer', 'aakaari-brand' );
                    }
                    ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="mobile-nav-menu">
        <!-- Main Navigation -->
        <div class="mobile-nav-section">
            <p class="mobile-nav-section-title"><?php esc_html_e( 'Navigation', 'aakaari-brand' ); ?></p>

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span><?php esc_html_e( 'Home', 'aakaari-brand' ); ?></span>
            </a>

            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="mobile-nav-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span><?php esc_html_e( 'Shop', 'aakaari-brand' ); ?></span>
                </a>
            <?php endif; ?>

            <?php if ( function_exists( 'yith_wcwl_count_products' ) ) : ?>
                <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>" class="mobile-nav-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <span><?php esc_html_e( 'Wishlist', 'aakaari-brand' ); ?></span>
                </a>
            <?php endif; ?>
        </div>

        <!-- User Actions -->
        <?php if ( $is_user_logged_in ) : ?>
            <div class="mobile-nav-section">
                <p class="mobile-nav-section-title"><?php esc_html_e( 'Account', 'aakaari-brand' ); ?></p>

                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="mobile-nav-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>
                            <?php
                            if ( $user_role === 'admin' ) {
                                esc_html_e( 'Dashboard', 'aakaari-brand' );
                            } else {
                                esc_html_e( 'My Account', 'aakaari-brand' );
                            }
                            ?>
                        </span>
                    </a>

                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="mobile-nav-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span><?php esc_html_e( 'My Orders', 'aakaari-brand' ); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ( $user_role === 'admin' ) : ?>
                    <a href="<?php echo esc_url( admin_url() ); ?>" class="mobile-nav-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6m6-12h-6m-6 0H0m12 6H6m12 0h-6"></path>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span><?php esc_html_e( 'Admin Panel', 'aakaari-brand' ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Information -->
        <div class="mobile-nav-section">
            <p class="mobile-nav-section-title"><?php esc_html_e( 'Information', 'aakaari-brand' ); ?></p>

            <a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="mobile-nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span><?php esc_html_e( 'About Us', 'aakaari-brand' ); ?></span>
            </a>

            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="mobile-nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <span><?php esc_html_e( 'Contact', 'aakaari-brand' ); ?></span>
            </a>

            <a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="mobile-nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <span><?php esc_html_e( 'FAQ', 'aakaari-brand' ); ?></span>
            </a>
        </div>

        <!-- Auth Actions -->
        <div class="mobile-nav-section">
            <?php if ( ! $is_user_logged_in ) : ?>
                <a href="<?php echo esc_url( wp_login_url() ); ?>" class="mobile-nav-link mobile-nav-link-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span><?php esc_html_e( 'Sign In', 'aakaari-brand' ); ?></span>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="mobile-nav-link mobile-nav-link-danger">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span><?php esc_html_e( 'Sign Out', 'aakaari-brand' ); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Footer -->
    <div class="mobile-nav-footer">
        <p class="mobile-nav-footer-text">
            &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>
        </p>
    </div>
</div>
