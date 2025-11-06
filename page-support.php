<?php
/**
 * Template Name: Support Page
 */

get_header();

// Redirect to login if not logged in
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

$current_user = wp_get_current_user();
?>

<div class="support-page" style="min-height: 100vh; background: #f9fafb; padding: 3rem 0;">
    <div class="support-container" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <div class="support-header" style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; margin: 0 0 0.5rem; font-weight: 700;">Support Center</h1>
            <p style="color: #666; font-size: 1.125rem; margin: 0;">We're here to help you</p>
        </div>

        <div class="support-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
            <!-- Quick Links -->
            <div class="support-links" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h2 style="font-size: 1.5rem; margin: 0 0 1.5rem; font-weight: 600;">Quick Links</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; text-decoration: none; color: #000; font-weight: 500; text-align: center; transition: background 0.2s;">
                        Track Orders
                    </a>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; text-decoration: none; color: #000; font-weight: 500; text-align: center; transition: background 0.2s;">
                        Browse Products
                    </a>
                    <a href="<?php echo esc_url( home_url('/contact/') ); ?>" style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; text-decoration: none; color: #000; font-weight: 500; text-align: center; transition: background 0.2s;">
                        Contact Us
                    </a>
                    <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; text-decoration: none; color: #000; font-weight: 500; text-align: center; transition: background 0.2s;">
                        My Account
                    </a>
                </div>
            </div>

            <!-- FAQs -->
            <div class="support-faqs" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h2 style="font-size: 1.5rem; margin: 0 0 1.5rem; font-weight: 600;">Frequently Asked Questions</h2>
                <div class="faq-list" style="display: flex; flex-direction: column; gap: 1rem;">
                    <details style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How do I track my order?</summary>
                        <p style="margin-top: 1rem; color: #666;">You can track your order by logging into your account and visiting the Orders page. You'll see tracking information for all your recent orders.</p>
                    </details>
                    <details style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">What is your return policy?</summary>
                        <p style="margin-top: 1rem; color: #666;">We offer a 30-day return policy for all unworn items with tags attached. Returns are free and easy through our returns portal.</p>
                    </details>
                    <details style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">How long does shipping take?</summary>
                        <p style="margin-top: 1rem; color: #666;">Standard shipping takes 3-5 business days. Express shipping is available at checkout for 1-2 day delivery.</p>
                    </details>
                    <details style="padding: 1rem; background: #f8f9fa; border-radius: 0.5rem; cursor: pointer;">
                        <summary style="font-weight: 600; cursor: pointer;">Do you ship internationally?</summary>
                        <p style="margin-top: 1rem; color: #666;">Yes, we ship to over 50 countries worldwide. International shipping times vary by location.</p>
                    </details>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="support-contact" style="background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h2 style="font-size: 1.5rem; margin: 0 0 1rem; font-weight: 600;">Still Need Help?</h2>
                <p style="color: #666; margin: 0 0 1.5rem;">Can't find what you're looking for? Our support team is here to help.</p>
                <a href="<?php echo esc_url( home_url('/contact/') ); ?>" style="display: inline-block; padding: 0.75rem 1.5rem; background: #000; color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 600; transition: background 0.2s;">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media (min-width: 768px) {
    .support-grid {
        grid-template-columns: 1fr !important;
    }
}

details summary {
    list-style: none;
}

details summary::-webkit-details-marker {
    display: none;
}
</style>

<?php
get_footer();
