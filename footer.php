    </div><!-- #content -->

    <footer class="footer-main">
        <!-- Main Footer -->
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand -->
                <div class="footer-brand">
                    <div class="footer-brand-logo">
                        <div class="footer-logo-icon">
                            <span class="footer-logo-text">ST</span>
                        </div>
                        <span class="footer-brand-name">StreetStyle</span>
                    </div>
                    <p class="footer-brand-description">
                        Premium streetwear essentials for the modern lifestyle. Quality you can feel.
                    </p>
                    <div class="footer-social">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Instagram">
                            <svg class="footer-social-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Twitter">
                            <svg class="footer-social-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
                            </svg>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Facebook">
                            <svg class="footer-social-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
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
                                    $tshirts_link = home_url('/shop/');
                                    if ( function_exists( 'get_term_link' ) ) {
                                        $tshirts_term_link = get_term_link( 't-shirts', 'product_cat' );
                                        if ( ! is_wp_error( $tshirts_term_link ) ) {
                                            $tshirts_link = $tshirts_term_link;
                                        }
                                    }
                                    echo esc_url( $tshirts_link );
                                ?>" class="footer-link">
                                    T-Shirts
                                </a>
                            </li>
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
                                    Hoodies
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
                                <a href="<?php echo esc_url( home_url('/support/') ); ?>" class="footer-link">
                                    Support Center
                                </a>
                            </li>
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
                                <a href="<?php echo esc_url( home_url('/support/') ); ?>" class="footer-link">
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
                        &copy; <?php echo date('Y'); ?> StreetStyle. All rights reserved.
                    </p>
                    <div class="footer-payments">
                        <span class="footer-payments-label">Secure payments via</span>
                        <div class="footer-payment-badges">
                            <div class="footer-payment-badge">VISA</div>
                            <div class="footer-payment-badge">MASTERCARD</div>
                            <div class="footer-payment-badge">PAYPAL</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/690db028fab74e1958c08028/1j9eng85i';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

<?php wp_footer(); ?>

</body>
</html>
