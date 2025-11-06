    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            <?php if (is_active_sidebar('footer-widgets')) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-widgets'); ?>
                </div>
            <?php else : ?>
                <!-- Default Footer Content -->
                <div class="footer-grid">
                    <div class="footer-column">
                        <h4><?php esc_html_e('About FashionMen', 'fashionmen'); ?></h4>
                        <p><?php esc_html_e('Premium men\'s fashion crafted for the modern gentleman.', 'fashionmen'); ?></p>
                    </div>

                    <div class="footer-column">
                        <h4><?php esc_html_e('Quick Links', 'fashionmen'); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About Us', 'fashionmen'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'fashionmen'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/faq')); ?>"><?php esc_html_e('FAQ', 'fashionmen'); ?></a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4><?php esc_html_e('Customer Service', 'fashionmen'); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/shipping')); ?>"><?php esc_html_e('Shipping Info', 'fashionmen'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/returns')); ?>"><?php esc_html_e('Returns', 'fashionmen'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy')); ?>"><?php esc_html_e('Privacy Policy', 'fashionmen'); ?></a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4><?php esc_html_e('Follow Us', 'fashionmen'); ?></h4>
                        <div class="social-links">
                            <a href="#" aria-label="Facebook">Facebook</a>
                            <a href="#" aria-label="Instagram">Instagram</a>
                            <a href="#" aria-label="Twitter">Twitter</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'fashionmen'); ?></p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

<style>
    /* Footer Styles */
    .site-footer {
        background: #000;
        color: #fff;
        padding: 3rem 0 1rem;
        margin-top: 4rem;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-column h4 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #fff;
    }

    .footer-column p {
        color: #999;
        line-height: 1.6;
    }

    .footer-column ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-column ul li {
        margin-bottom: 0.5rem;
    }

    .footer-column a {
        color: #999;
        transition: color 0.2s;
    }

    .footer-column a:hover {
        color: #fff;
    }

    .social-links {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .footer-bottom {
        border-top: 1px solid #333;
        padding-top: 1.5rem;
        text-align: center;
        color: #666;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
</style>

</body>
</html>
