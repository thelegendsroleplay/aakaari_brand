    </div><!-- #content -->

    <footer class="footer-main" id="colophon">
        <!-- Main Footer -->
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand -->
                <div class="footer-brand">
                    <div class="footer-brand-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <div class="footer-logo-icon">
                                <span class="footer-logo-text">ST</span>
                            </div>
                            <span class="footer-brand-name"><?php bloginfo( 'name' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="footer-brand-description">
                        <?php
                        $description = get_bloginfo( 'description', 'display' );
                        echo $description ? esc_html( $description ) : esc_html__( 'Premium streetwear essentials for the modern lifestyle. Quality you can feel.', 'aakaari-brand' );
                        ?>
                    </p>
                    <div class="footer-social">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Instagram">
                            <svg class="footer-social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Twitter">
                            <svg class="footer-social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Facebook">
                            <svg class="footer-social-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Links -->
                <div class="footer-links-grid">
                    <!-- Shop -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title"><?php esc_html_e( 'Shop', 'aakaari-brand' ); ?></h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'T-Shirts', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Hoodies', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'New Arrivals', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Sale', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Help -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title"><?php esc_html_e( 'Help', 'aakaari-brand' ); ?></h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php echo esc_url( home_url( '/support/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Support Center', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Contact Us', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/shipping/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Shipping', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/track-order/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Track Order', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/support/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'FAQ', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title"><?php esc_html_e( 'Company', 'aakaari-brand' ); ?></h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'About', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Privacy', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>" class="footer-link">
                                    <?php esc_html_e( 'Terms', 'aakaari-brand' ); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <div class="footer-bottom-container">
                <div class="footer-bottom-content">
                    <p class="footer-copyright">
                        <?php
                        /* translators: %s: Current year */
                        printf( esc_html__( '&copy; %s StreetStyle. All rights reserved.', 'aakaari-brand' ), date( 'Y' ) );
                        ?>
                    </p>
                    <div class="footer-payments">
                        <span class="footer-payments-label"><?php esc_html_e( 'Secure payments via', 'aakaari-brand' ); ?></span>
                        <div class="footer-payment-badges">
                            <div class="footer-payment-badge">VISA</div>
                            <div class="footer-payment-badge">MASTERCARD</div>
                            <div class="footer-payment-badge">PAYPAL</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
