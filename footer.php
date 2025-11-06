<?php
/**
 * The template for displaying the footer
 *
 * @package Aakaari_Brand
 */
?>

    <footer id="colophon" class="footer-main">
        <div class="footer-container">
            <!-- Main Footer Content -->
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-column footer-brand">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h3 class="footer-logo">
                            <?php bloginfo( 'name' ); ?>
                        </h3>
                    <?php endif; ?>

                    <p class="footer-description">
                        <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( $description ) {
                            echo esc_html( $description );
                        } else {
                            esc_html_e( 'Premium men\'s fashion for the modern gentleman. Quality craftsmanship and timeless style.', 'aakaari-brand' );
                        }
                        ?>
                    </p>

                    <!-- Social Links -->
                    <div class="footer-social">
                        <?php
                        $social_links = array(
                            'facebook'  => get_theme_mod( 'aakaari_brand_facebook_url', '#' ),
                            'instagram' => get_theme_mod( 'aakaari_brand_instagram_url', '#' ),
                            'twitter'   => get_theme_mod( 'aakaari_brand_twitter_url', '#' ),
                            'linkedin'  => get_theme_mod( 'aakaari_brand_linkedin_url', '#' ),
                            'youtube'   => get_theme_mod( 'aakaari_brand_youtube_url', '#' ),
                        );

                        $social_icons = array(
                            'facebook'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                            'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
                            'twitter'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
                            'linkedin'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
                            'youtube'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="white"></polygon></svg>',
                        );

                        foreach ( $social_links as $network => $url ) {
                            if ( ! empty( $url ) && $url !== '#' ) {
                                printf(
                                    '<a href="%s" class="footer-social-link" aria-label="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                                    esc_url( $url ),
                                    esc_attr( ucfirst( $network ) ),
                                    $social_icons[ $network ]
                                );
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php else : ?>
                        <h4 class="footer-column-title"><?php esc_html_e( 'Quick Links', 'aakaari-brand' ); ?></h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="footer-link"><?php esc_html_e( 'About Us', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="footer-link"><?php esc_html_e( 'Contact', 'aakaari-brand' ); ?></a></li>
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="footer-link"><?php esc_html_e( 'Shop', 'aakaari-brand' ); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo esc_url( home_url( '/shipping' ) ); ?>" class="footer-link"><?php esc_html_e( 'Shipping Info', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="footer-link"><?php esc_html_e( 'FAQ', 'aakaari-brand' ); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Customer Service Column -->
                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <h4 class="footer-column-title"><?php esc_html_e( 'Customer Service', 'aakaari-brand' ); ?></h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/returns' ) ); ?>" class="footer-link"><?php esc_html_e( 'Returns & Exchanges', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="footer-link"><?php esc_html_e( 'Help & FAQ', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="footer-link"><?php esc_html_e( 'Privacy Policy', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="footer-link"><?php esc_html_e( 'Terms & Conditions', 'aakaari-brand' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="footer-link"><?php esc_html_e( 'Track Order', 'aakaari-brand' ); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Newsletter Column -->
                <div class="footer-column">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php else : ?>
                        <h4 class="footer-column-title"><?php esc_html_e( 'Newsletter', 'aakaari-brand' ); ?></h4>
                        <p class="footer-newsletter-text">
                            <?php esc_html_e( 'Subscribe to get special offers, free giveaways, and exclusive updates.', 'aakaari-brand' ); ?>
                        </p>
                        <form class="footer-newsletter-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <input type="hidden" name="action" value="aakaari_brand_newsletter_signup" />
                            <?php wp_nonce_field( 'newsletter_signup', 'newsletter_nonce' ); ?>
                            <input
                                type="email"
                                name="newsletter_email"
                                class="footer-newsletter-input"
                                placeholder="<?php esc_attr_e( 'Enter your email', 'aakaari-brand' ); ?>"
                                required
                            />
                            <button type="submit" class="footer-newsletter-btn" aria-label="<?php esc_attr_e( 'Subscribe', 'aakaari-brand' ); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </button>
                        </form>
                        <p class="footer-newsletter-privacy">
                            <?php esc_html_e( 'We respect your privacy. Unsubscribe anytime.', 'aakaari-brand' ); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="footer-payment">
                <p class="footer-payment-title"><?php esc_html_e( 'Secure Payment Methods', 'aakaari-brand' ); ?></p>
                <div class="footer-payment-icons">
                    <div class="footer-payment-icon">VISA</div>
                    <div class="footer-payment-icon">MC</div>
                    <div class="footer-payment-icon">AMEX</div>
                    <div class="footer-payment-icon">PayPal</div>
                    <div class="footer-payment-icon">GPay</div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.
                    <?php esc_html_e( 'All rights reserved.', 'aakaari-brand' ); ?>
                </p>
                <div class="footer-bottom-links">
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'menu_class'     => 'footer-bottom-menu',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                    } else {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" class="footer-bottom-link"><?php esc_html_e( 'Privacy', 'aakaari-brand' ); ?></a>
                        <span class="footer-divider">•</span>
                        <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="footer-bottom-link"><?php esc_html_e( 'Terms', 'aakaari-brand' ); ?></a>
                        <span class="footer-divider">•</span>
                        <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="footer-bottom-link"><?php esc_html_e( 'Support', 'aakaari-brand' ); ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button id="back-to-top" class="footer-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aakaari-brand' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="19" x2="12" y2="5"></line>
                <polyline points="5 12 12 5 19 12"></polyline>
            </svg>
        </button>
    </footer>

    <!-- Mobile Navigation -->
    <?php get_template_part( 'template-parts/mobile-navigation' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
