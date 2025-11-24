<?php
/**
 * Template Name: Track Order
 * Template Post Type: page
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main track-order-page">
    <div class="page-container">
        <div class="track-order-content">
            <div class="track-order-header">
                <div class="track-order-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                        <rect x="2" y="9" width="4" height="12"></rect>
                        <circle cx="4" cy="4" r="2"></circle>
                    </svg>
                </div>
                <h1 class="track-order-title">Track Your Order</h1>
                <p class="track-order-subtitle">Enter your order details below to track your shipment</p>
            </div>

            <div class="track-order-form-container">
                <form id="trackOrderForm" class="track-order-form">
                    <div class="form-group">
                        <label for="order_number">Order Number *</label>
                        <input type="text" id="order_number" name="order_number" placeholder="e.g., #12345" required>
                        <small>You can find this in your order confirmation email</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                        <small>The email address used during checkout</small>
                    </div>

                    <button type="submit" class="track-order-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Track Order
                    </button>
                </form>

                <div id="trackingResult" class="tracking-result" style="display: none;"></div>
            </div>

            <div class="track-order-info">
                <h2>How to Track Your Order</h2>
                <div class="info-steps">
                    <div class="info-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3>Check Your Email</h3>
                            <p>Once your order is shipped, you'll receive a confirmation email with your tracking number and link.</p>
                        </div>
                    </div>

                    <div class="info-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3>Enter Details</h3>
                            <p>Enter your order number and email address in the form above to track your shipment in real-time.</p>
                        </div>
                    </div>

                    <div class="info-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3>Track Status</h3>
                            <p>View detailed tracking information including current location and estimated delivery date.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="track-order-help">
                <h3>Need Help?</h3>
                <p>Can't find your order or having trouble tracking? We're here to help!</p>
                <div class="help-options">
                    <a href="mailto:support@herrenn.com" class="help-option">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <span>support@herrenn.com</span>
                    </a>
                    <a href="/contact" class="help-option">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>

            <div class="track-order-faq">
                <h3>Frequently Asked Questions</h3>
                <div class="faq-item">
                    <h4>When will I receive my tracking number?</h4>
                    <p>You'll receive your tracking number via email within 24-48 hours after your order is placed, once it has been shipped from our warehouse.</p>
                </div>
                <div class="faq-item">
                    <h4>How long does delivery take?</h4>
                    <p>Standard delivery takes 5-7 business days. Express delivery takes 2-3 business days for metro cities.</p>
                </div>
                <div class="faq-item">
                    <h4>My tracking isn't updating. What should I do?</h4>
                    <p>Tracking information may take 24 hours to update after shipment. If it's been longer, please contact our support team.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#trackOrderForm').on('submit', function(e) {
        e.preventDefault();

        var orderNumber = $('#order_number').val();
        var email = $('#email').val();
        var $result = $('#trackingResult');
        var $btn = $('.track-order-btn');

        // Disable button and show loading
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Tracking...');

        // AJAX request to track order
        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'track_order',
                order_number: orderNumber,
                email: email,
                nonce: '<?php echo wp_create_nonce('track_order_nonce'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $result.html(response.data.html).slideDown();
                } else {
                    $result.html('<div class="tracking-error">' + response.data.message + '</div>').slideDown();
                }
            },
            error: function() {
                $result.html('<div class="tracking-error">An error occurred. Please try again.</div>').slideDown();
            },
            complete: function() {
                $btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg> Track Order');
            }
        });
    });
});
</script>

<?php
get_footer();
