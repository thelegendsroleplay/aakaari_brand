<?php
/**
 * Template Name: Contact Page
 * Description: Contact Us page template with contact form
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
            <h1 class="static-hero-title">Get in Touch</h1>
            <p class="static-hero-subtitle">Have a question? We're here to help. Reach out to our team.</p>
        </div>

        <!-- Contact Grid -->
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form id="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <input type="hidden" name="action" value="fashionmen_contact_form">
                    <?php wp_nonce_field('fashionmen_contact_form', 'contact_nonce'); ?>

                    <div class="form-group">
                        <label for="contact-name" class="form-label">Full Name *</label>
                        <input type="text" id="contact-name" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-email" class="form-label">Email Address *</label>
                        <input type="email" id="contact-email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-phone" class="form-label">Phone Number</label>
                        <input type="tel" id="contact-phone" name="phone" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="contact-subject" class="form-label">Subject *</label>
                        <input type="text" id="contact-subject" name="subject" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-message" class="form-label">Message *</label>
                        <textarea id="contact-message" name="message" class="form-textarea" required></textarea>
                    </div>

                    <div class="form-response" id="form-response"></div>

                    <button type="submit" class="submit-button">Send Message</button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Contact Information</h2>

                <div class="contact-info-item">
                    <div class="contact-info-icon">📍</div>
                    <div class="contact-info-details">
                        <div class="contact-info-label">Address</div>
                        <div class="contact-info-value">123 Fashion Avenue<br>New York, NY 10001<br>USA</div>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">📧</div>
                    <div class="contact-info-details">
                        <div class="contact-info-label">Email</div>
                        <div class="contact-info-value"><a href="mailto:support@mensafashion.com">support@mensafashion.com</a></div>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">📞</div>
                    <div class="contact-info-details">
                        <div class="contact-info-label">Phone</div>
                        <div class="contact-info-value"><a href="tel:+15551234567">+1 (555) 123-4567</a></div>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">🕐</div>
                    <div class="contact-info-details">
                        <div class="contact-info-label">Business Hours</div>
                        <div class="contact-info-value">
                            Monday - Friday: 9AM - 6PM EST<br>
                            Saturday: 10AM - 4PM EST<br>
                            Sunday: Closed
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook" title="Facebook">
                        <span>f</span>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram" title="Instagram">
                        <span>📷</span>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter" title="Twitter">
                        <span>🐦</span>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn" title="LinkedIn">
                        <span>in</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Content -->
        <div class="static-content" style="margin-top: 3rem;">
            <h2>Department Contacts</h2>
            <div class="contact-departments">
                <div class="department-item">
                    <h3>Customer Service</h3>
                    <p>For questions about orders, returns, and general inquiries</p>
                    <a href="mailto:support@mensafashion.com">support@mensafashion.com</a>
                </div>
                <div class="department-item">
                    <h3>Sales</h3>
                    <p>For bulk orders and corporate sales</p>
                    <a href="mailto:sales@mensafashion.com">sales@mensafashion.com</a>
                </div>
                <div class="department-item">
                    <h3>Press & Media</h3>
                    <p>For media inquiries and press releases</p>
                    <a href="mailto:press@mensafashion.com">press@mensafashion.com</a>
                </div>
                <div class="department-item">
                    <h3>Partnerships</h3>
                    <p>For business partnerships and collaborations</p>
                    <a href="mailto:partners@mensafashion.com">partners@mensafashion.com</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
