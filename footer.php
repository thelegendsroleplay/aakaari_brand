    </div><!-- #content -->

    <footer class="footer-main">
        <!-- Main Footer -->
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand -->
                <div class="footer-brand">
                    <div class="footer-brand-logo">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/herrenn-wordmark-black-1.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="footer-logo-img" style="height: 32px; width: auto;">
                    </div>
                    <p class="footer-brand-description">
                        Built for men who move different.
                    </p>
                    <div class="footer-social">
                        <a href="https://instagram.com/herrenn_official" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Instagram">
                            <svg class="footer-social-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                        </a>
                        
                    </div>
                </div>

                <!-- Links -->
                <div class="footer-links-grid">
                    <!-- Shop -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Shop</h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php
                                    $hoodies_link = home_url('/shop/');
                                    if ( function_exists( 'get_term_link' ) ) {
                                        $hoodies_term_link = get_term_link( 'hoodies', 'product_cat' );
                                        if ( ! is_wp_error( $hoodies_term_link ) ) {
                                            $hoodies_link = $hoodies_term_link;
                                        }
                                    }
                                    echo esc_url( $hoodies_link );
                                ?>" class="footer-link">
                                    Shop
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/shop/?orderby=date') ); ?>" class="footer-link">
                                    New Arrivals
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/shop/?on_sale=yes') ); ?>" class="footer-link">
                                    Sale
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Help -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Help</h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="footer-link">
                                    Contact Us
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/shipping/') ); ?>" class="footer-link">
                                    Shipping
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/track-order/') ); ?>" class="footer-link">
                                    Track Order
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/FAQ/') ); ?>" class="footer-link">
                                    FAQ
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div class="footer-links-column">
                        <h4 class="footer-links-title">Company</h4>
                        <ul class="footer-links-list">
                            <li>
                                <a href="<?php echo esc_url( home_url('/about/') ); ?>" class="footer-link">
                                    About
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/privacy-policy/') ); ?>" class="footer-link">
                                    Privacy
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url( home_url('/terms-conditions/') ); ?>" class="footer-link">
                                    Terms
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
                        &copy; <?php echo date('Y'); ?> Herrenn. All rights reserved.
                    </p>
                    <div class="footer-payments">
                        <span class="footer-payments-label">Secure payments</span>
                        <div class="footer-payment-badges">
                            <div class="footer-payment-icon" title="Visa">
                                <svg viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="32" rx="4" fill="#1434CB"/>
                                    <path d="M20.8 21.5h-2.7l-1.7-6.6c-.1-.3-.2-.5-.4-.6-.4-.2-1.1-.4-1.7-.5l0-.3h2.9c.4 0 .7.3.8.7l.7 3.9 1.9-4.6h2.6l-4.4 8zm5.8 0h-2.5l2-8h2.5l-2 8zm8.3-7.8c-.5-.2-1.3-.4-2.3-.4-2.5 0-4.3 1.3-4.3 3.2 0 1.4 1.2 2.1 2.2 2.6 1 .5 1.4.8 1.4 1.2 0 .6-.8.9-1.5.9-1 0-1.5-.1-2.3-.5l-.3-.2-.3 1.9c.6.3 1.7.5 2.9.5 2.7 0 4.4-1.3 4.4-3.3 0-1.1-.7-1.9-2.1-2.6-.9-.4-1.4-.7-1.4-1.2 0-.4.5-.8 1.5-.8.8 0 1.5.2 1.9.3l.2.1.3-1.7zm4.5 7.8h-2.2c-.4 0-.7-.2-.8-.6l-3-7.4h2.6l.5 1.5h3.2l.3-1.5h2.3l-2 8zm-2.8-3.2l1.3-3.5.7 3.5h-2z" fill="white"/>
                                </svg>
                            </div>
                            <div class="footer-payment-icon" title="Mastercard">
                                <svg viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="32" rx="4" fill="#EB001B"/>
                                    <circle cx="18" cy="16" r="10" fill="#FF5F00"/>
                                    <circle cx="30" cy="16" r="10" fill="#F79E1B" opacity="0.8"/>
                                </svg>
                            </div>
                            <div class="footer-payment-icon" title="UPI">
                                <svg viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="32" rx="4" fill="#097939"/>
                                    <text x="24" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="12" font-weight="bold">UPI</text>
                                </svg>
                            </div>
                            <div class="footer-payment-icon" title="RuPay">
                                <svg viewBox="0 0 48 32" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="32" rx="4" fill="#097939"/>
                                    <text x="24" y="12" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="7" font-weight="bold">RuPay</text>
                                    <path d="M14 16h20v2h-20z" fill="#FF6B00"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php
/**
 * Custom Live Chat Widget
 * The live chat widget is automatically loaded via enqueued scripts in functions.php
 * - CSS: assets/css/live-chat.css
 * - JS: assets/js/live-chat.js
 * - Backend: inc/live-chat-system.php
 */
?>

<?php wp_footer(); ?>

</body>
</html>
