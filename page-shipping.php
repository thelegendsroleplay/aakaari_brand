<?php
/**
 * Template Name: Shipping Page
 * Description: Shipping Information page template
 *
 * @package FashionMen
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="static-page">
    <div class="static-container">
        <!-- Hero Section -->
        <div class="static-hero">
            <h1 class="static-hero-title">Shipping Information</h1>
            <p class="static-hero-subtitle">Fast, reliable shipping to your doorstep.</p>
        </div>

        <!-- Main Content -->
        <div class="static-content">
            <!-- Shipping Methods -->
            <section class="shipping-section">
                <h2>Domestic Shipping Options</h2>
                <div class="shipping-options">
                    <div class="shipping-option">
                        <div class="shipping-option-header">
                            <h3 class="shipping-option-name">Standard Shipping</h3>
                            <span class="shipping-option-price">Free on orders over $50</span>
                        </div>
                        <p class="shipping-option-duration">⏱️ 5-7 business days</p>
                        <p class="shipping-option-details">Our most economical option. Perfect for non-urgent orders. Tracking included.</p>
                    </div>

                    <div class="shipping-option">
                        <div class="shipping-option-header">
                            <h3 class="shipping-option-name">Express Shipping</h3>
                            <span class="shipping-option-price">$15.00</span>
                        </div>
                        <p class="shipping-option-duration">⏱️ 2-3 business days</p>
                        <p class="shipping-option-details">Faster delivery for when you need your order sooner. Full tracking and insurance included.</p>
                    </div>

                    <div class="shipping-option">
                        <div class="shipping-option-header">
                            <h3 class="shipping-option-name">Overnight Shipping</h3>
                            <span class="shipping-option-price">$25.00</span>
                        </div>
                        <p class="shipping-option-duration">⏱️ Next business day</p>
                        <p class="shipping-option-details">Get your order as fast as possible. Orders placed before 2PM EST ship same day.</p>
                    </div>
                </div>
            </section>

            <!-- International Shipping -->
            <section class="shipping-section">
                <h2>International Shipping</h2>
                <p>We ship to over 25 countries worldwide. International shipping times vary by location but typically take 7-14 business days. Customs fees and import duties may apply and are the responsibility of the customer.</p>

                <h3>Countries We Ship To:</h3>
                <ul>
                    <li>United States</li>
                    <li>Canada</li>
                    <li>United Kingdom</li>
                    <li>Australia</li>
                    <li>Germany</li>
                    <li>France</li>
                    <li>Italy</li>
                    <li>Spain</li>
                    <li>Japan</li>
                    <li>+ 16 more countries</li>
                </ul>
                <p><em>Don't see your country listed? <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Contact us</a> to inquire about international shipping options.</em></p>
            </section>

            <!-- Shipping Policies -->
            <section class="shipping-section">
                <h2>Shipping Policies</h2>

                <h3>Processing Time</h3>
                <p>All orders are processed within 24 hours of receipt. Orders placed on weekends or holidays will be processed the next business day.</p>

                <h3>Order Tracking</h3>
                <p>Once your order ships, you'll receive a tracking number via email. You can also track your order through your account dashboard. Tracking information is typically updated within 24 hours of shipment.</p>

                <h3>Delivery Signature</h3>
                <p>For security purposes, orders over $200 require a signature upon delivery. If you won't be available to sign, you can arrange for the package to be held at your local carrier facility for pickup.</p>

                <h3>Lost or Damaged Items</h3>
                <p>If your order arrives damaged or goes missing in transit, please contact us immediately at <a href="mailto:support@mensafashion.com">support@mensafashion.com</a>. We'll work with the carrier and send you a replacement or refund.</p>

                <h3>Shipping Restrictions</h3>
                <p>We cannot ship to P.O. boxes for express or overnight shipping. Some remote locations may require additional shipping time.</p>
            </section>

            <!-- Delivery Map Placeholder -->
            <div class="delivery-map">
                <h3>🌎 Delivering Excellence Worldwide</h3>
                <p>We partner with trusted carriers to ensure your order arrives safely and on time.</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="static-cta">
            <h2>Ready to Upgrade Your Wardrobe?</h2>
            <p>Discover our latest collection of premium men's fashion.</p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="cta-button">Shop Now</a>
        </div>
    </div>
</div>

<?php
get_footer();
