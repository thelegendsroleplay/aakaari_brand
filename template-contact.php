<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * @package Aakaari_Brand
 */

get_header();
?>

<main id="main" class="site-main contact-page">
    <div class="page-container">
        <div class="contact-content">
            <div class="contact-header">
                <h1 class="contact-title">Get in Touch</h1>
                <p class="contact-subtitle">Have a question or need assistance? We'd love to hear from you!</p>
            </div>

            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-section">
                    <h2>Send Us a Message</h2>
                    <form id="contactForm" class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_name">Full Name *</label>
                                <input type="text" id="contact_name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="contact_email">Email Address *</label>
                                <input type="email" id="contact_email" name="email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_phone">Phone Number</label>
                                <input type="tel" id="contact_phone" name="phone">
                            </div>

                            <div class="form-group">
                                <label for="contact_subject">Subject *</label>
                                <select id="contact_subject" name="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="order">Order Inquiry</option>
                                    <option value="product">Product Question</option>
                                    <option value="return">Return/Exchange</option>
                                    <option value="shipping">Shipping Issue</option>
                                    <option value="payment">Payment Problem</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact_order">Order Number (if applicable)</label>
                            <input type="text" id="contact_order" name="order_number" placeholder="e.g., #12345">
                        </div>

                        <div class="form-group">
                            <label for="contact_message">Message *</label>
                            <textarea id="contact_message" name="message" rows="6" required></textarea>
                        </div>

                        <button type="submit" class="contact-submit-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                            Send Message
                        </button>
                    </form>

                    <div id="contactFormMessage" class="form-message" style="display: none;"></div>
                </div>

                <!-- Contact Info -->
                <div class="contact-info-section">
                    <h2>Contact Information</h2>

                    <div class="contact-info-card">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </div>
                        <div class="info-content">
                            <h3>Email</h3>
                            <p><a href="mailto:support@herrenn.com">support@herrenn.com</a></p>
                            <small>We'll respond within 24 hours</small>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </div>
                        <div class="info-content">
                            <h3>Phone</h3>
                            <p>Coming Soon</p>
                            <small>Customer support hotline</small>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="info-content">
                            <h3>Business Hours</h3>
                            <p>Monday - Saturday</p>
                            <p>10:00 AM - 6:00 PM IST</p>
                            <small>Closed on Sundays and public holidays</small>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <div class="info-content">
                            <h3>Location</h3>
                            <p>Serving customers across India</p>
                            <small>Free shipping on orders over ₹499</small>
                        </div>
                    </div>

                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon instagram" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                            <a href="#" class="social-icon twitter" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                </svg>
                            </a>
                            <a href="#" class="social-icon facebook" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="quick-links">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="/track-order">Track Your Order</a></li>
                            <li><a href="/faq">Frequently Asked Questions</a></li>
                            <li><a href="/shipping-delivery-policy">Shipping Policy</a></li>
                            <li><a href="/cancellation-refund-policy">Return & Refund Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            action: 'submit_contact_form',
            name: $('#contact_name').val(),
            email: $('#contact_email').val(),
            phone: $('#contact_phone').val(),
            subject: $('#contact_subject').val(),
            order_number: $('#contact_order').val(),
            message: $('#contact_message').val(),
            nonce: '<?php echo wp_create_nonce('contact_form_nonce'); ?>'
        };

        var $btn = $('.contact-submit-btn');
        var $message = $('#contactFormMessage');

        // Disable button and show loading
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Sending...');

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $message.html('<div class="success-message">' + response.data.message + '</div>').fadeIn();
                    $('#contactForm')[0].reset();
                } else {
                    $message.html('<div class="error-message">' + response.data.message + '</div>').fadeIn();
                }
            },
            error: function() {
                $message.html('<div class="error-message">An error occurred. Please try again or email us directly.</div>').fadeIn();
            },
            complete: function() {
                $btn.prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> Send Message');

                setTimeout(function() {
                    $message.fadeOut();
                }, 5000);
            }
        });
    });
});
</script>

<?php
get_footer();
